@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Kombinasi Kelompok</h1>
                <p class="mt-1 text-slate-600">
                    Pembentukan kelompok mahasiswa dengan rumus <span class="font-bold">C(n,r) = n! / (r!(n-r)!)</span>
                </p>
                @if($studentCount > 0)
                    <p class="mt-1 text-xs text-slate-500">Data mahasiswa tersedia: {{ $studentCount }} orang (nama digunakan pada kombinasi).</p>
                @endif
            </div>
            <form method="GET" action="{{ route('groups.combinations') }}" class="flex flex-wrap gap-2 items-end">
                <div>
                    <label class="block text-xs text-slate-500 mb-1">n (mahasiswa)</label>
                    <input type="number" name="n" value="{{ $n }}" min="1" max="15" class="w-20 rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none" />
                </div>
                <div>
                    <label class="block text-xs text-slate-500 mb-1">r (ukuran kelompok)</label>
                    <input type="number" name="r" value="{{ $r }}" min="1" class="w-20 rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none" />
                </div>
                <div>
                    <label class="block text-xs text-slate-500 mb-1">team sizes</label>
                    <input type="text" name="team_sizes" value="{{ implode(',', $teamSizes) }}" class="w-28 rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="2,3" />
                </div>
                <button class="rounded-xl px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold text-sm hover:scale-[1.02] transition-transform">
                    Hitung
                </button>
            </form>
        </div>

        <div class="mt-6 grid md:grid-cols-3 gap-4">
            <div class="rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 p-5 text-white shadow-xl shadow-indigo-200">
                <div class="text-sm font-semibold text-white/80">Rumus Kombinatorika</div>
                <div class="mt-2 text-2xl font-bold">C({{ $n }}, {{ $r }}) = {{ $nCr }}</div>
                <div class="mt-2 text-xs text-white/80">{{ $n }}! / ({{ $r }}! × {{ $n - $r }}!)</div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                <div class="text-sm font-bold text-slate-900">Parameter</div>
                <div class="mt-3 text-sm text-slate-600 space-y-1">
                    <div><span class="font-semibold">n</span> = {{ $n }} mahasiswa</div>
                    <div><span class="font-semibold">r</span> = {{ $r }} per kelompok</div>
                    <div><span class="font-semibold">Jumlah kombinasi</span> = {{ $nCr }}</div>
                </div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                <div class="text-sm font-bold text-slate-900">Kompleksitas Kasus</div>
                <div class="mt-3 text-sm text-slate-600">
                    @if($n <= 3)
                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">Sederhana</span>
                        <p class="mt-2">3 mahasiswa, kombinasi mudah divisualisasikan.</p>
                    @elseif($n <= 5)
                        <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">Menengah</span>
                        <p class="mt-2">5 mahasiswa, C(n,r) mulai bertambah signifikan.</p>
                    @else
                        <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 text-xs font-bold">Luas</span>
                        <p class="mt-2">10+ mahasiswa, analisis efisiensi perhitungan penting.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-8">
            <div class="flex items-center justify-between">
                <div class="text-sm font-bold text-slate-900">Semua Kemungkinan Kombinasi C(n,r)</div>
                <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">{{ count($combi['combinations']) }} kombinasi</span>
            </div>
            <div class="mt-4 grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                @foreach($combi['combinations'] as $idx => $combo)
                    <div class="rounded-2xl p-4 border border-slate-200 bg-white hover:shadow-md hover:-translate-y-0.5 hover:border-indigo-300 transition-all">
                        <div class="text-xs font-bold text-indigo-600">Kombinasi #{{ $idx + 1 }}</div>
                        <div class="mt-2 flex flex-wrap gap-1">
                            @foreach($combo as $member)
                                <span class="px-2 py-0.5 rounded-lg bg-purple-100 text-purple-700 text-xs font-medium">{{ $member }}</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            @if(count($combi['combinations']) === 0)
                <div class="text-center text-slate-500 py-10">Tidak ada kombinasi untuk parameter saat ini.</div>
            @endif
        </div>

        <div class="mt-8">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-bold text-slate-900">Formasi Kelompok (Partisi)</div>
                    <div class="text-xs text-slate-500">Ukuran tim: {{ implode(', ', $result['team_sizes']) }}</div>
                </div>
                <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-bold">{{ $result['count'] }} formasi</span>
            </div>
            <div class="mt-4 grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($result['formations'] as $fIdx => $formation)
                    <div class="rounded-2xl p-4 border border-slate-200 bg-gradient-to-br from-white to-purple-50/50 hover:shadow-md transition">
                        <div class="text-xs font-bold text-purple-700">Formasi #{{ $fIdx + 1 }}</div>
                        <div class="mt-2 space-y-1 text-xs text-slate-600">
                            @foreach($formation['teams'] as $team)
                                <div class="flex items-center gap-1">
                                    <span class="text-purple-500">●</span>
                                    [{{ implode(', ', $team) }}]
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            @if(count($result['formations']) === 0)
                <div class="text-center text-slate-500 py-10">Tidak ada formasi untuk parameter saat ini.</div>
            @endif
        </div>
    </div>
@endsection
