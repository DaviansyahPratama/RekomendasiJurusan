@extends('layouts.app')

@section('content')
<h2 class="mb-4">Riwayat Nilai</h2>
<div class="card border-0 shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>Semester</th><th>Kode MK</th><th>Mata Kuliah</th><th>SKS</th><th>Nilai</th><th>Grade</th></tr></thead>
        <tbody>
            @forelse($nilai as $n)
            <tr>
                <td>{{ $n->semester_lulus }}</td>
                <td>{{ $n->mataKuliah->kode_mk }}</td>
                <td>{{ $n->mataKuliah->nama_mk }}</td>
                <td>{{ $n->mataKuliah->sks }}</td>
                <td>{{ $n->nilai_angka }}</td>
                <td><span class="badge bg-primary">{{ $n->grade }}</span></td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada riwayat nilai</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($nilai->hasPages())<div class="card-footer">{{ $nilai->links() }}</div>@endif
</div>
@endsection
