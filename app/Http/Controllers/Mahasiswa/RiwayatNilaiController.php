<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class RiwayatNilaiController extends Controller
{
    public function index(): View
    {
        $nilai = auth()->user()
            ->nilaiMahasiswa()
            ->with('mataKuliah')
            ->orderBy('semester_lulus')
            ->paginate(10);

        return view('mahasiswa.riwayat-nilai', compact('nilai'));
    }
}
