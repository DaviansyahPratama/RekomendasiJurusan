@extends('layouts.app')

@section('content')
<h2 class="mb-4">Rekomendasi Mata Kuliah</h2>

<div class="alert alert-info small mb-4">
    <strong>Konsep Relasi Matematika Diskrit:</strong>
    Skor dihitung dari relasi <code>R_minat ⊆ Minat × Kategori</code> (+20 jika cocok, maks. 20)
    dan relasi <code>R_nilai ⊆ Semester(1–7) × Kategori</code> dari nilai mahasiswa (+80/+53/+27).
    Total skor maksimum: <strong>100</strong>.
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Mata Kuliah</th><th>SKS</th><th>Kategori</th><th>Kesulitan</th><th>Skor</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse($rekomendasi as $item)
                @php $mk = $item['mata_kuliah']; @endphp
                <tr>
                    <td>{{ ($rekomendasi->currentPage() - 1) * $rekomendasi->perPage() + $loop->iteration }}</td>
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
                            <span class="badge bg-danger">{{ $item['alasan_tidak'] }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data rekomendasi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($rekomendasi->hasPages())<div class="card-footer">{{ $rekomendasi->links() }}</div>@endif
</div>
@endsection
