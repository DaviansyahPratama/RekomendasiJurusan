<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/mahasiswa', [StudentController::class, 'index'])->name('students.index');
Route::get('/mahasiswa/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/mahasiswa', [StudentController::class, 'store'])->name('students.store');

Route::get('/nilai', [ScoreController::class, 'index'])->name('scores.index');

Route::get('/rekomendasi', [RecommendationController::class, 'index'])->name('recommendations.index');
Route::get('/rekomendasi/{studentId}', [RecommendationController::class, 'show'])->name('recommendation.show');

Route::get('/kombinasi', [GroupController::class, 'combinations'])->name('groups.combinations');
