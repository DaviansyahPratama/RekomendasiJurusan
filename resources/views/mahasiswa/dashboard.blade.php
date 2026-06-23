@extends('layouts.app')

@section('content')
<h2 class="mb-4">Dashboard Mahasiswa</h2>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-stat border-0 shadow-sm"><div class="card-body">
            <div class="text-muted small">Semester Aktif</div>
            <div class="fs-3 fw-bold text-primary">{{ $user->semester_aktif }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat border-0 shadow-sm"><div class="card-body">
            <div class="text-muted small">IPK</div>
            <div class="fs-3 fw-bold text-success">{{ number_format($user->ipk, 2) }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat border-0 shadow-sm"><div class="card-body">
            <div class="text-muted small">Total SKS Lulus</div>
            <div class="fs-3 fw-bold text-info">{{ $totalSksLulus }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat border-0 shadow-sm"><div class="card-body">
            <div class="text-muted small">MK Direkomendasikan</div>
            <div class="fs-3 fw-bold text-warning">{{ $jumlahDirekomendasikan }}</div>
        </div></div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Rekomendasi Mata Kuliah</strong></div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Kategori</th>
                    <th>Tingkat Kesulitan</th>
                    <th>Skor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekomendasi as $item)
                @php $mk = $item['mata_kuliah']; @endphp
                <tr>
                    <td>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</td>
                    <td>{{ $mk->sks }}</td>
                    <td>{{ $mk->kategori }}</td>
                    <td>{{ $mk->tingkat_kesulitan }}</td>
                    <td>
                        <strong>{{ number_format($item['skor'], 1) }}</strong>
                        @include('mahasiswa._komponen-skor')
                    </td>
                    <td>
                        @if($item['direkomendasikan'])
                            <span class="badge bg-success">Direkomendasikan</span>
                        @else
                            <span class="badge bg-danger" title="{{ $item['alasan_tidak'] }}">Tidak Direkomendasikan</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data rekomendasi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($rekomendasi->hasPages())<div class="card-footer">{{ $rekomendasi->links() }}</div>@endif
</div>
@endsection
