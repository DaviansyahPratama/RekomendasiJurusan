<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MataKuliahController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\MinatController;
use App\Http\Controllers\Admin\NilaiMahasiswaController;
use App\Http\Controllers\Admin\PrasyaratController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\GrafikController;
use App\Http\Controllers\Mahasiswa\MinatController as MahasiswaMinatController;
use App\Http\Controllers\Mahasiswa\ProfilController;
use App\Http\Controllers\Mahasiswa\RekomendasiController;
use App\Http\Controllers\Mahasiswa\RiwayatNilaiController;
use App\Http\Controllers\Mahasiswa\SimulasiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('mahasiswa.dashboard');
    }

    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('mata-kuliah', MataKuliahController::class);
    Route::resource('minat', MinatController::class)->except(['show']);
    Route::get('/prasyarat', [PrasyaratController::class, 'index'])->name('prasyarat.index');
    Route::post('/prasyarat', [PrasyaratController::class, 'store'])->name('prasyarat.store');
    Route::delete('/prasyarat/{prasyarat}', [PrasyaratController::class, 'destroy'])->name('prasyarat.destroy');
    Route::resource('nilai', NilaiMahasiswaController::class)->except(['show']);
    Route::resource('mahasiswa', MahasiswaController::class);
});

Route::middleware(['auth', 'mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::get('/riwayat-nilai', [RiwayatNilaiController::class, 'index'])->name('riwayat-nilai');
    Route::get('/pilih-minat', [MahasiswaMinatController::class, 'index'])->name('pilih-minat');
    Route::post('/pilih-minat', [MahasiswaMinatController::class, 'store'])->name('pilih-minat.store');
    Route::get('/rekomendasi', [RekomendasiController::class, 'index'])->name('rekomendasi');
    Route::get('/simulasi', [SimulasiController::class, 'index'])->name('simulasi');
    Route::post('/simulasi', [SimulasiController::class, 'store'])->name('simulasi.store');
    Route::delete('/simulasi/{pengambilan}', [SimulasiController::class, 'destroy'])->name('simulasi.destroy');
    Route::get('/grafik', [GrafikController::class, 'index'])->name('grafik');
});
