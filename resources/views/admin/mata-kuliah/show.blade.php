@extends('layouts.app')

@section('content')
<h2 class="mb-4">{{ $mataKuliah->nama_mk }}</h2>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Kode</dt><dd class="col-sm-9">{{ $mataKuliah->kode_mk }}</dd>
            <dt class="col-sm-3">SKS</dt><dd class="col-sm-9">{{ $mataKuliah->sks }}</dd>
            <dt class="col-sm-3">Semester</dt><dd class="col-sm-9">{{ $mataKuliah->semester }}</dd>
            <dt class="col-sm-3">Kategori</dt><dd class="col-sm-9">{{ $mataKuliah->kategori }}</dd>
            <dt class="col-sm-3">Kesulitan</dt><dd class="col-sm-9">{{ $mataKuliah->tingkat_kesulitan }}</dd>
            <dt class="col-sm-3">Deskripsi</dt><dd class="col-sm-9">{{ $mataKuliah->deskripsi ?? '-' }}</dd>
        </dl>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Prasyarat</strong></div>
    <ul class="list-group list-group-flush">
        @forelse($mataKuliah->prasyarat as $p)
            <li class="list-group-item">{{ $p->kode_mk }} - {{ $p->nama_mk }}</li>
        @empty
            <li class="list-group-item text-muted">Tidak ada prasyarat</li>
        @endforelse
    </ul>
</div>
<a href="{{ route('admin.mata-kuliah.index') }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
