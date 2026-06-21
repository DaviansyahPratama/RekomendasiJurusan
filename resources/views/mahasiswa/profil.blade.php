@extends('layouts.app')

@section('content')
<h2 class="mb-4">Profil Saya</h2>
<div class="card border-0 shadow-sm"><div class="card-body">
    <form action="{{ route('mahasiswa.profil.update') }}" method="POST">
        @csrf @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">NIM</label>
                <input type="text" class="form-control" value="{{ $user->nim }}" disabled>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Semester Aktif</label>
                <input type="text" class="form-control" value="{{ $user->semester_aktif }}" disabled>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">IPK</label>
                <input type="text" class="form-control" value="{{ number_format($user->ipk, 2) }}" disabled>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Password Baru (opsional)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">Minat</label>
                <div>
                    @forelse($user->minat as $minat)
                        <span class="badge bg-primary me-1">{{ $minat->nama_minat }}</span>
                    @empty
                        <span class="text-muted">Belum memilih minat. <a href="{{ route('mahasiswa.pilih-minat') }}">Pilih minat</a></span>
                    @endforelse
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div></div>
@endsection
