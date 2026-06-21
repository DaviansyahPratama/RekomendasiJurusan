@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-mortarboard-fill text-primary" style="font-size: 3rem;"></i>
                    <h4 class="mt-2">Sistem Rekomendasi Mata Kuliah</h4>
                    <p class="text-muted small">Silakan login untuk melanjutkan</p>
                </div>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <div class="mt-4 p-3 bg-light rounded small">
                    <strong>Demo Akun:</strong><br>
                    Admin: admin@demo.test / password<br>
                    Mahasiswa: aulia@demo.test / password
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
