<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Services\RekomendasiMataKuliahService;
use Illuminate\View\View;

class RekomendasiController extends Controller
{
    public function index(RekomendasiMataKuliahService $service): View
    {
        $user = auth()->user()->load(['minat', 'nilaiMahasiswa']);
        $rekomendasiAll = $service->rekomendasikan($user);

        return view('mahasiswa.rekomendasi', [
            'rekomendasi' => $this->paginateCollection($rekomendasiAll),
            'user' => $user,
        ]);
    }
}
