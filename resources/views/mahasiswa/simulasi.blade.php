@extends('layouts.app')

@section('content')
<h2 class="mb-4">Simulasi Pengambilan Mata Kuliah</h2>
<p class="text-muted">Semester berikutnya: <strong>Semester {{ $semesterBerikutnya }}</strong> | Total SKS simulasi: <strong>{{ $totalSks }}</strong>
@if($totalSks > 24)<span class="badge bg-danger ms-2">Melebihi 24 SKS (-20 poin penalty)</span>@endif
</p>

<div class="row">
    <div class="col-md-5 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>Mata Kuliah Dipilih</strong></div>
            <ul class="list-group list-group-flush">
                @forelse($pengambilan as $p)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $p->mataKuliah->kode_mk }} - {{ $p->mataKuliah->nama_mk }} ({{ $p->mataKuliah->sks }} SKS)</span>
                    <form action="{{ route('mahasiswa.simulasi.destroy', $p) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">&times;</button>
                    </form>
                </li>
                @empty
                <li class="list-group-item text-muted">Belum ada mata kuliah dipilih</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>Tambah ke Simulasi</strong></div>
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-sm mb-0">
                    <thead class="table-light sticky-top"><tr><th>Mata Kuliah</th><th>SKS</th><th>Skor</th><th></th></tr></thead>
                    <tbody>
                        @foreach($eligible as $item)
                        @php $mk = $item['mata_kuliah']; @endphp
                        <tr>
                            <td>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</td>
                            <td>{{ $mk->sks }}</td>
                            <td>{{ number_format($item['skor'], 1) }}</td>
                            <td>
                                <form action="{{ route('mahasiswa.simulasi.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="mata_kuliah_id" value="{{ $mk->id }}">
                                    <button class="btn btn-sm btn-primary">+</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
