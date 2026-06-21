<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Services\RekomendasiMataKuliahService;
use Illuminate\View\View;

class GrafikController extends Controller
{
    public function index(RekomendasiMataKuliahService $service): View
    {
        $user = auth()->user()->load(['nilaiMahasiswa.mataKuliah', 'minat']);
        $rekomendasi = $service->rekomendasikan($user);
        $direkomendasikan = $rekomendasi->where('direkomendasikan', true);

        return view('mahasiswa.grafik', [
            'barLabels' => $direkomendasikan->map(fn ($r) => $r['mata_kuliah']->kode_mk)->values(),
            'barData' => $direkomendasikan->map(fn ($r) => $r['skor'])->values(),
            'kategoriChart' => $service->chartKategori($rekomendasi),
            'ipChart' => $service->riwayatIpPerSemester($user),
        ]);
    }
}
