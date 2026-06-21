<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Services\RekomendasiMataKuliahService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(RekomendasiMataKuliahService $service): View
    {
        $user = auth()->user()->load(['nilaiMahasiswa.mataKuliah', 'minat']);
        $rekomendasi = $service->rekomendasikan($user);
        $direkomendasikan = $rekomendasi->where('direkomendasikan', true);

        return view('mahasiswa.dashboard', [
            'user' => $user,
            'rekomendasi' => $rekomendasi,
            'jumlahDirekomendasikan' => $direkomendasikan->count(),
            'totalSksLulus' => $user->totalSksLulus(),
        ]);
    }
}
