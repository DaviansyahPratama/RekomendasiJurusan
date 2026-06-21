<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Minat;
use App\Models\NilaiMahasiswa;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalMataKuliah' => MataKuliah::count(),
            'totalMinat' => Minat::count(),
            'totalMahasiswa' => User::where('role', 'mahasiswa')->count(),
            'totalNilai' => NilaiMahasiswa::count(),
        ]);
    }
}
