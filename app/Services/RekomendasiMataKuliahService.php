<?php

namespace App\Services;

use App\Models\MataKuliah;
use App\Models\NilaiMahasiswa;
use App\Models\RekomendasiMataKuliah;
use App\Models\User;
use Illuminate\Support\Collection;

class RekomendasiMataKuliahService
{
    public function rekomendasikan(User $user, int $totalSksSimulasi = 0): Collection
    {
        $user->load(['minat', 'nilaiMahasiswa.mataKuliah', 'pengambilanMataKuliah']);

        $semesterAktif = $user->semester_aktif ?? 1;
        $mataKuliahDiambil = $user->nilaiMahasiswa->pluck('mata_kuliah_id')->toArray();
        $nilaiMap = $user->nilaiMahasiswa->keyBy('mata_kuliah_id');
        $minatMahasiswa = $user->minat->pluck('nama_minat')->toArray();

        $semesterBerikutnya = $semesterAktif + 1;
        $totalSks = $totalSksSimulasi > 0
            ? $totalSksSimulasi
            : (int) $user->pengambilanMataKuliah()
                ->where('semester_ambil', $semesterBerikutnya)
                ->join('mata_kuliah', 'pengambilan_mata_kuliah.mata_kuliah_id', '=', 'mata_kuliah.id')
                ->sum('mata_kuliah.sks');

        $semuaMataKuliah = MataKuliah::with('prasyarat')->get();
        $hasil = collect();

        foreach ($semuaMataKuliah as $mk) {
            $eligible = $this->isEligible($mk, $semesterAktif, $mataKuliahDiambil, $nilaiMap);
            $skor = $this->hitungSkor($mk, $minatMahasiswa, $user, $totalSks, $nilaiMap);

            $hasil->push([
                'mata_kuliah' => $mk,
                'skor' => $skor,
                'direkomendasikan' => $eligible,
                'alasan_tidak' => $eligible ? null : $this->alasanTidakEligible($mk, $semesterAktif, $mataKuliahDiambil, $nilaiMap),
            ]);

            RekomendasiMataKuliah::updateOrCreate(
                ['user_id' => $user->id, 'mata_kuliah_id' => $mk->id],
                ['skor' => $skor, 'direkomendasikan' => $eligible]
            );
        }

        return $hasil->sortByDesc('skor')->values();
    }

    public function rekomendasiFiltered(User $user, int $totalSksSimulasi = 0): Collection
    {
        return $this->rekomendasikan($user, $totalSksSimulasi)
            ->where('direkomendasikan', true)
            ->values();
    }

    private function isEligible(MataKuliah $mk, int $semesterAktif, array $diambil, Collection $nilaiMap): bool
    {
        if ($mk->semester > $semesterAktif) {
            return false;
        }

        if (in_array($mk->id, $diambil, true)) {
            return false;
        }

        foreach ($mk->prasyarat as $prasyarat) {
            $nilai = $nilaiMap->get($prasyarat->id);
            if (! $nilai || ! in_array($nilai->grade, ['A', 'B', 'C'], true)) {
                return false;
            }
        }

        return true;
    }

    private function alasanTidakEligible(MataKuliah $mk, int $semesterAktif, array $diambil, Collection $nilaiMap): string
    {
        if ($mk->semester > $semesterAktif) {
            return 'Semester mata kuliah melebihi semester aktif';
        }

        if (in_array($mk->id, $diambil, true)) {
            return 'Sudah pernah diambil';
        }

        foreach ($mk->prasyarat as $prasyarat) {
            $nilai = $nilaiMap->get($prasyarat->id);
            if (! $nilai) {
                return 'Prasyarat belum lulus: '.$prasyarat->nama_mk;
            }
            if (! in_array($nilai->grade, ['A', 'B', 'C'], true)) {
                return 'Prasyarat grade minimal C: '.$prasyarat->nama_mk;
            }
        }

        return 'Tidak memenuhi syarat';
    }

    private function hitungSkor(MataKuliah $mk, array $minatMahasiswa, User $user, int $totalSks, Collection $nilaiMap): float
    {
        $scoreMinat = in_array($mk->kategori, $minatMahasiswa, true) ? 40 : 0;

        $scoreNilai = $this->scoreNilaiKategori($mk->kategori, $user);

        $penaltyKesulitan = $mk->tingkat_kesulitan * 5;

        $penaltySks = $totalSks > 24 ? 20 : 0;

        return $scoreMinat + $scoreNilai - $penaltyKesulitan - $penaltySks;
    }

    private function scoreNilaiKategori(string $kategori, User $user): int
    {
        $nilaiKategori = $user->nilaiMahasiswa
            ->filter(fn ($n) => $n->mataKuliah && $n->mataKuliah->kategori === $kategori);

        if ($nilaiKategori->isEmpty()) {
            return 0;
        }

        $gradeTerbaik = $nilaiKategori->sortByDesc(fn ($n) => NilaiMahasiswa::bobotGrade($n->grade))->first();

        return NilaiMahasiswa::bobotGrade($gradeTerbaik->grade);
    }

    public function riwayatIpPerSemester(User $user): array
    {
        $nilai = $user->nilaiMahasiswa()->with('mataKuliah')->get();

        $perSemester = $nilai->groupBy('semester_lulus')->sortKeys();

        $labels = [];
        $ipData = [];
        $ipkData = [];
        $akumulasiBobot = 0;
        $akumulasiSks = 0;

        foreach ($perSemester as $semester => $items) {
            $totalBobot = 0;
            $totalSks = 0;

            foreach ($items as $item) {
                $sks = $item->mataKuliah->sks ?? 0;
                $bobot = $this->bobotMutu($item->grade);
                $totalBobot += $bobot * $sks;
                $totalSks += $sks;
            }

            $ip = $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;
            $akumulasiBobot += $totalBobot;
            $akumulasiSks += $totalSks;
            $ipk = $akumulasiSks > 0 ? round($akumulasiBobot / $akumulasiSks, 2) : 0;

            $labels[] = 'Semester '.$semester;
            $ipData[] = $ip;
            $ipkData[] = $ipk;
        }

        return compact('labels', 'ipData', 'ipkData');
    }

    public function chartKategori(Collection $rekomendasi): array
    {
        $direkomendasikan = $rekomendasi->where('direkomendasikan', true);

        $grouped = $direkomendasikan->groupBy(fn ($r) => $r['mata_kuliah']->kategori);

        return [
            'labels' => $grouped->keys()->values()->toArray(),
            'data' => $grouped->map->count()->values()->toArray(),
        ];
    }

    private function bobotMutu(string $grade): float
    {
        return match ($grade) {
            'A' => 4.0,
            'B' => 3.0,
            'C' => 2.0,
            'D' => 1.0,
            default => 0.0,
        };
    }
}
