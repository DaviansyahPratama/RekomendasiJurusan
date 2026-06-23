<?php

namespace App\Services;

use App\Models\MataKuliah;
use Illuminate\Support\Collection;

/**
 * Perhitungan skor rekomendasi berbasis Relasi Matematika Diskrit.
 *
 * Himpunan:
 * - M = himpunan minat mahasiswa
 * - K = himpunan kategori mata kuliah
 * - S = {1, 2, ..., 7} (semester)
 * - N = himpunan nilai mahasiswa pada semester 1–7
 *
 * Relasi:
 * - R_minat ⊆ M × K        : (m, k) ∈ R_minat ⟺ m = k
 * - R_nilai ⊆ S × K        : (s, k) ∈ R_nilai ⟺ ∃ nilai lulus MK kategori k di semester s
 * - R_prasyarat ⊆ MK × MK  : (a, b) ∈ R_prasyarat ⟺ b prasyarat dari a
 *
 * Skor (maks. 100):
 * score = skor_minat (maks. 20) + skor_nilai (maks. 80)
 */
class RelasiDiskritService
{
    public const SEMESTER_MIN = 1;

    public const SEMESTER_MAX = 7;

    public const SKOR_MINAT = 20;

    public const SKOR_NILAI_A = 80;

    public const SKOR_NILAI_B = 53;

    public const SKOR_NILAI_C = 27;

    /**
     * Produk kartesian M × K.
     *
     * @return array<int, array{0: string, 1: string}>
     */
    public function produkKartesianMinatKategori(array $minat, array $kategori): array
    {
        $hasil = [];

        foreach ($minat as $m) {
            foreach ($kategori as $k) {
                $hasil[] = [$m, $k];
            }
        }

        return $hasil;
    }

    /**
     * Cek keanggotaan relasi minat: (minat, kategori_mk) ∈ R_minat.
     */
    public function dalamRelasiMinat(array $minatMahasiswa, string $kategoriMk): bool
    {
        return in_array($kategoriMk, $minatMahasiswa, true);
    }

    /**
     * Ambil himpunan nilai mahasiswa pada interval semester S = {1..7}.
     */
    public function nilaiSemesterSatuSampaiTujuh(Collection $nilaiMahasiswa): Collection
    {
        return $nilaiMahasiswa->filter(
            fn ($nilai) => $nilai->semester_lulus >= self::SEMESTER_MIN
                && $nilai->semester_lulus <= self::SEMESTER_MAX
        );
    }

    /**
     * Relasi R_nilai untuk kategori tertentu: pasangan (semester, kategori) dari riwayat nilai.
     */
    public function relasiNilaiPerKategori(Collection $nilaiSem1s7, string $kategoriMk): Collection
    {
        return $nilaiSem1s7->filter(
            fn ($nilai) => $nilai->mataKuliah
                && $nilai->mataKuliah->kategori === $kategoriMk
        );
    }

    /**
     * Komposisi relasi minat & nilai untuk satu mata kuliah → skor rekomendasi.
     *
     * @return array{
     *     skor: float,
     *     skor_minat: int,
     *     skor_nilai: int,
     *     relasi_minat: bool,
     *     jumlah_nilai_kategori: int,
     *     grade_terbaik: string|null
     * }
     */
    public function hitungSkorRelasi(
        MataKuliah $mataKuliah,
        array $minatMahasiswa,
        Collection $nilaiSem1s7
    ): array {
        $skorMinat = $this->dalamRelasiMinat($minatMahasiswa, $mataKuliah->kategori) ? self::SKOR_MINAT : 0;

        $nilaiKategori = $this->relasiNilaiPerKategori($nilaiSem1s7, $mataKuliah->kategori);
        $gradeTerbaik = null;
        $skorNilai = 0;

        if ($nilaiKategori->isNotEmpty()) {
            $terbaik = $nilaiKategori->sortByDesc(
                fn ($nilai) => self::bobotSkorNilai($nilai->grade)
            )->first();
            $gradeTerbaik = $terbaik->grade;
            $skorNilai = self::bobotSkorNilai($gradeTerbaik);
        }

        return [
            'skor' => round($skorMinat + $skorNilai, 2),
            'skor_minat' => $skorMinat,
            'skor_nilai' => $skorNilai,
            'relasi_minat' => $this->dalamRelasiMinat($minatMahasiswa, $mataKuliah->kategori),
            'jumlah_nilai_kategori' => $nilaiKategori->count(),
            'grade_terbaik' => $gradeTerbaik,
        ];
    }

    public static function bobotSkorNilai(string $grade): int
    {
        return match ($grade) {
            'A' => self::SKOR_NILAI_A,
            'B' => self::SKOR_NILAI_B,
            'C' => self::SKOR_NILAI_C,
            default => 0,
        };
    }
}
