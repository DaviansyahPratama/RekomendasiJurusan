@extends('layouts.app')

@section('content')
<h2 class="mb-4">Edit Mata Kuliah</h2>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.mata-kuliah.update', $mataKuliah) }}" method="POST">
            @csrf @method('PUT')
            @include('admin.mata-kuliah._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.mata-kuliah.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
