@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Bidang Minat</h2>
    <a href="{{ route('admin.minat.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<div class="card border-0 shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>Nama Minat</th><th>Jumlah Mahasiswa</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($minat as $m)
            <tr>
                <td>{{ $m->nama_minat }}</td>
                <td>{{ $m->mahasiswa_count }}</td>
                <td>
                    <a href="{{ route('admin.minat.edit', $m) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('admin.minat.destroy', $m) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus minat?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($minat->hasPages())<div class="card-footer">{{ $minat->links() }}</div>@endif
</div>
@endsection
