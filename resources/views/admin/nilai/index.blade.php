@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Nilai Mahasiswa</h2>
    <a href="{{ route('admin.nilai.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<div class="card border-0 shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>Mahasiswa</th><th>Mata Kuliah</th><th>Nilai</th><th>Grade</th><th>Semester</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($nilai as $n)
            <tr>
                <td>{{ $n->user->name }} ({{ $n->user->nim }})</td>
                <td>{{ $n->mataKuliah->nama_mk }}</td>
                <td>{{ $n->nilai_angka }}</td>
                <td><span class="badge bg-primary">{{ $n->grade }}</span></td>
                <td>{{ $n->semester_lulus }}</td>
                <td>
                    <a href="{{ route('admin.nilai.edit', $n) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('admin.nilai.destroy', $n) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($nilai->hasPages())<div class="card-footer">{{ $nilai->links() }}</div>@endif
</div>
@endsection
