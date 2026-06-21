@extends('layouts.app')

@section('content')
<h2 class="mb-4">Tambah Mahasiswa</h2>
<div class="card border-0 shadow-sm"><div class="card-body">
    <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
        @csrf
        @include('admin.mahasiswa._form')
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div></div>
@endsection
