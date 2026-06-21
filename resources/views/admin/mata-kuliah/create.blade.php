@extends('layouts.app')

@section('content')
<h2 class="mb-4">Tambah Mata Kuliah</h2>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.mata-kuliah.store') }}" method="POST">
            @csrf
            @include('admin.mata-kuliah._form')
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.mata-kuliah.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
