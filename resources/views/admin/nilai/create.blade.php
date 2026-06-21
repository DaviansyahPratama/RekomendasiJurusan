@extends('layouts.app')

@section('content')
<h2 class="mb-4">Tambah Nilai Mahasiswa</h2>
<div class="card border-0 shadow-sm"><div class="card-body">
    <form action="{{ route('admin.nilai.store') }}" method="POST">
        @csrf
        @include('admin.nilai._form')
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.nilai.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div></div>
@endsection
