@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Mata Kuliah</h2>
    <a href="{{ route('admin.mata-kuliah.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Kode</th><th>Nama</th><th>SKS</th><th>Semester</th><th>Kategori</th><th>Kesulitan</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($mataKuliah as $mk)
                <tr>
                    <td>{{ $mk->kode_mk }}</td>
                    <td>{{ $mk->nama_mk }}</td>
                    <td>{{ $mk->sks }}</td>
                    <td>{{ $mk->semester }}</td>
                    <td><span class="badge bg-secondary">{{ $mk->kategori }}</span></td>
                    <td>{{ $mk->tingkat_kesulitan }}</td>
                    <td>
                        <a href="{{ route('admin.mata-kuliah.show', $mk) }}" class="btn btn-sm btn-outline-info">Detail</a>
                        <a href="{{ route('admin.mata-kuliah.edit', $mk) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('admin.mata-kuliah.destroy', $mk) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus mata kuliah?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($mataKuliah->hasPages())<div class="card-footer">{{ $mataKuliah->links() }}</div>@endif
</div>
@endsection
