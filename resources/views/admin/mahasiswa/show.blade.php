@extends('layouts.app')

@section('content')
<h2 class="mb-4">{{ $mahasiswa->name }}</h2>
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm"><div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-4">NIM</dt><dd class="col-sm-8">{{ $mahasiswa->nim }}</dd>
                <dt class="col-sm-4">Email</dt><dd class="col-sm-8">{{ $mahasiswa->email }}</dd>
                <dt class="col-sm-4">Semester</dt><dd class="col-sm-8">{{ $mahasiswa->semester_aktif }}</dd>
                <dt class="col-sm-4">IPK</dt><dd class="col-sm-8">{{ number_format($mahasiswa->ipk, 2) }}</dd>
                <dt class="col-sm-4">Minat</dt>
                <dd class="col-sm-8">
                    @forelse($mahasiswa->minat as $minat)
                        <span class="badge bg-primary me-1">{{ $minat->nama_minat }}</span>
                    @empty
                        -
                    @endforelse
                </dd>
            </dl>
        </div></div>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Riwayat Nilai</strong></div>
    <table class="table mb-0">
        <thead class="table-light"><tr><th>Mata Kuliah</th><th>Nilai</th><th>Grade</th><th>Semester</th></tr></thead>
        <tbody>
            @forelse($mahasiswa->nilaiMahasiswa as $n)
            <tr>
                <td>{{ $n->mataKuliah->nama_mk }}</td>
                <td>{{ $n->nilai_angka }}</td>
                <td>{{ $n->grade }}</td>
                <td>{{ $n->semester_lulus }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-muted text-center py-3">Belum ada nilai</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
