@extends('layouts.app')

@section('content')
<h2 class="mb-4">Rekomendasi Mata Kuliah</h2>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Mata Kuliah</th><th>SKS</th><th>Kategori</th><th>Kesulitan</th><th>Skor</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($rekomendasi as $i => $item)
                @php $mk = $item['mata_kuliah']; @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</td>
                    <td>{{ $mk->sks }}</td>
                    <td>{{ $mk->kategori }}</td>
                    <td>{{ $mk->tingkat_kesulitan }}</td>
                    <td><strong>{{ number_format($item['skor'], 1) }}</strong></td>
                    <td>
                        @if($item['direkomendasikan'])
                            <span class="badge bg-success">Direkomendasikan</span>
                        @else
                            <span class="badge bg-danger">{{ $item['alasan_tidak'] }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
