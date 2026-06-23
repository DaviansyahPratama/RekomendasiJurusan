{{-- Komponen skor berbasis relasi matematika diskrit --}}
@php $k = $item['komponen'] ?? []; @endphp
<div class="small text-muted mt-1">
    Minat: <span class="badge {{ ($k['relasi_minat'] ?? false) ? 'bg-success' : 'bg-secondary' }}">
        {{ ($k['relasi_minat'] ?? false) ? '∈ R_minat (+'.($k['skor_minat'] ?? 0).'/20)' : '∉ R_minat' }}
    </span>
    Nilai S1-7: +{{ $k['skor_nilai'] ?? 0 }}/80
    @if(!empty($k['grade_terbaik']))
        (grade {{ $k['grade_terbaik'] }})
    @endif
</div>
