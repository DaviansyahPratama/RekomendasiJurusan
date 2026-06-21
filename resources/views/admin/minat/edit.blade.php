@extends('layouts.app')

@section('content')
<h2 class="mb-4">Edit Minat</h2>
<div class="card border-0 shadow-sm"><div class="card-body">
    <form action="{{ route('admin.minat.update', $minat) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nama Minat</label>
            <input type="text" name="nama_minat" class="form-control" value="{{ old('nama_minat', $minat->nama_minat) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.minat.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div></div>
@endsection
