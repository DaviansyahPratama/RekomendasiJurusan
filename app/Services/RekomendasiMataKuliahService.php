<?php

namespace App\Services;

use App\Models\MataKuliah;
use App\Models\RekomendasiMataKuliah;
use App\Models\User;
use Illuminate\Support\Collection;

class RekomendasiMataKuliahService
{
    public function __construct(
        private RelasiDiskritService $relasiDiskrit
    ) {}

    public function rekomendasikan(User $user): Collection
    {
        $user->load(['minat', 'nilaiMahasiswa.mataKuliah']);

        $semesterAktif = $user->semester_aktif ?? 1;
        $mataKuliahDiambil = $user->nilaiMahasiswa->pluck('mata_kuliah_id')->toArray();
        $nilaiMap = $user->nilaiMahasiswa->keyBy('mata_kuliah_id');
        $minatMahasiswa = $user->minat->pluck('nama_minat')->toArray();
        $nilaiSem1s7 = $this->relasiDiskrit->nilaiSemesterSatuSampaiTujuh($user->nilaiMahasiswa);

        $semesterBerikutnya = $semesterAktif + 1;

        // MK semester berikutnya + MK semester ≤ aktif yang belum diambil
        $semuaMataKuliah = MataKuliah::with('prasyarat')
            ->where('semester', '<=', $semesterBerikutnya)
            ->get();

        $hasil = collect();

        foreach ($semuaMataKuliah as $mk) {
            $eligible = $this->isEligible($mk, $semesterAktif, $mataKuliahDiambil, $nilaiMap);

            $komponen = $eligible
                ? $this->relasiDiskrit->hitungSkorRelasi($mk, $minatMahasiswa, $nilaiSem1s7)
                : [
                    'skor' => 0,
                    'skor_minat' => 0,
                    'skor_nilai' => 0,
                    'relasi_minat' => false,
                    'jumlah_nilai_kategori' => 0,
                    'grade_terbaik' => null,
                ];

            $hasil->push([
                'mata_kuliah' => $mk,
                'skor' => $komponen['skor'],
                'komponen' => $komponen,
                'direkomendasikan' => $eligible,
                'alasan_tidak' => $eligible ? null : $this->alasanTidakEligible($mk, $semesterAktif, $mataKuliahDiambil, $nilaiMap),
            ]);

            RekomendasiMataKuliah::updateOrCreate(
                ['user_id' => $user->id, 'mata_kuliah_id' => $mk->id],
                [
                    'skor' => $komponen['skor'],
                    'skor_minat' => $komponen['skor_minat'],
                    'skor_nilai' => $komponen['skor_nilai'],
                    'direkomendasikan' => $eligible,
                ]
            );
        }

        return $hasil->sortByDesc('skor')->values();
    }

    public function rekomendasiFiltered(User $user): Collection
    {
        return $this->rekomendasikan($user)
            ->where('direkomendasikan', true)
            ->values();
    }

    private function isEligible(MataKuliah $mk, int $semesterAktif, array $diambil, Collection $nilaiMap): bool
    {
        // MK hanya dapat direkomendasikan hingga semester berikutnya (aktif + 1)
        if ($mk->semester > $semesterAktif + 1) {
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
        if ($mk->semester > $semesterAktif + 1) {
            return 'Belum dapat diambil (semester mata kuliah melebihi rencana semester berikutnya)';
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

    public function riwayatIpPerSemester(User $user): array
    {
        $nilai = $this->relasiDiskrit->nilaiSemesterSatuSampaiTujuh(
            $user->nilaiMahasiswa()->with('mataKuliah')->get()
        );

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
