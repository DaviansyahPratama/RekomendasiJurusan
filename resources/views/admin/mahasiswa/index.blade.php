@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Data Mahasiswa</h2>
    <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<div class="card border-0 shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>NIM</th><th>Nama</th><th>Semester</th><th>IPK</th><th>Nilai</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($mahasiswa as $m)
            <tr>
                <td>{{ $m->nim }}</td>
                <td>{{ $m->name }}</td>
                <td>{{ $m->semester_aktif }}</td>
                <td>{{ number_format($m->ipk, 2) }}</td>
                <td>{{ $m->nilai_mahasiswa_count }}</td>
                <td>
                    <a href="{{ route('admin.mahasiswa.show', $m) }}" class="btn btn-sm btn-outline-info">Detail</a>
                    <a href="{{ route('admin.mahasiswa.edit', $m) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('admin.mahasiswa.destroy', $m) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus mahasiswa?')">
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
    @if($mahasiswa->hasPages())<div class="card-footer">{{ $mahasiswa->links() }}</div>@endif
</div>
@endsection
