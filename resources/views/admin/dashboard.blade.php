@extends('layouts.app')

@section('content')
<h2 class="mb-4">Dashboard Admin</h2>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-stat"><div class="card-body">
            <div class="text-muted small">Mata Kuliah</div>
            <div class="fs-3 fw-bold text-primary">{{ $totalMataKuliah }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat"><div class="card-body">
            <div class="text-muted small">Bidang Minat</div>
            <div class="fs-3 fw-bold text-success">{{ $totalMinat }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat"><div class="card-body">
            <div class="text-muted small">Mahasiswa</div>
            <div class="fs-3 fw-bold text-info">{{ $totalMahasiswa }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat"><div class="card-body">
            <div class="text-muted small">Data Nilai</div>
            <div class="fs-3 fw-bold text-warning">{{ $totalNilai }}</div>
        </div></div>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h5>Selamat datang, Admin!</h5>
        <p class="text-muted mb-0">Kelola mata kuliah, prasyarat, nilai mahasiswa, dan data minat melalui menu sidebar.</p>
    </div>
</div>
@endsection
