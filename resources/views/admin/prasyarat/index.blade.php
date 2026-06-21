@extends('layouts.app')

@section('content')
<h2 class="mb-4">Prasyarat Mata Kuliah</h2>
<div class="row">
    <div class="col-md-5 mb-4">
        <div class="card border-0 shadow-sm"><div class="card-body">
            <h5 class="card-title">Tambah Prasyarat</h5>
            <form action="{{ route('admin.prasyarat.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Mata Kuliah</label>
                    <select name="mata_kuliah_id" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        @foreach($mataKuliah as $mk)
                            <option value="{{ $mk->id }}" @selected(old('mata_kuliah_id') == $mk->id)>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Prasyarat (harus lulus dulu)</label>
                    <select name="prasyarat_id" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        @foreach($mataKuliah as $mk)
                            <option value="{{ $mk->id }}" @selected(old('prasyarat_id') == $mk->id)>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div></div>
    </div>
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>Mata Kuliah</th><th>Prasyarat</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($prasyarat as $p)
                    <tr>
                        <td>{{ $p->mataKuliah->kode_mk }} - {{ $p->mataKuliah->nama_mk }}</td>
                        <td>{{ $p->prasyaratMataKuliah->kode_mk }} - {{ $p->prasyaratMataKuliah->nama_mk }}</td>
                        <td>
                            <form action="{{ route('admin.prasyarat.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">Belum ada prasyarat</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($prasyarat->hasPages())<div class="card-footer">{{ $prasyarat->links() }}</div>@endif
        </div>
    </div>
</div>
@endsection
