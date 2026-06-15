@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Hasil Rekomendasi</h1>
                <p class="mt-1 text-slate-600">Ranking jurusan terbaik per mahasiswa berdasarkan metode analisis terpilih.</p>
            </div>
            <form method="GET" class="flex flex-wrap gap-2 items-center">
                <label class="text-sm font-medium text-slate-600">Metode:</label>
                <select name="method" onchange="this.form.submit()" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    @foreach($methods as $key => $label)
                        <option value="{{ $key }}" {{ $method === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="mt-6 grid md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse($overview as $item)
                @php
                    $student = $item['student'];
                    $best = $item['best'];
                    $avg = $student->scores->avg('score');
                @endphp
                <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <div class="font-bold text-slate-900">{{ $student->name }}</div>
                            <div class="text-xs text-slate-500 mt-1">{{ implode(', ', $student->interest ?? []) }}</div>
                        </div>
                        @if($best)
                            <span class="shrink-0 px-3 py-1 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-xs font-bold shadow">
                                {{ $best['score_total'] }} pts
                            </span>
                        @endif
                    </div>

                    @if($best)
                        <div class="mt-4 p-4 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-100">
                            <div class="text-xs text-indigo-600 font-semibold uppercase tracking-wide">Jurusan Terbaik</div>
                            <div class="mt-1 text-lg font-bold text-slate-900">{{ $best['major_name'] }}</div>
                            <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                <span class="px-2 py-0.5 rounded-full bg-white border border-slate-200">Minat +{{ $best['breakdown']['interest_match'] }}</span>
                                <span class="px-2 py-0.5 rounded-full bg-white border border-slate-200">Pref +{{ $best['breakdown']['preference_match'] }}</span>
                                <span class="px-2 py-0.5 rounded-full bg-white border border-slate-200">Nilai +{{ $best['breakdown']['average_points'] }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="mt-3 text-xs text-slate-500">
                        Rata-rata nilai: <span class="font-semibold text-slate-700">{{ $avg ? number_format($avg, 1) : '-' }}</span>
                        · Preferensi: <span class="font-semibold">{{ $student->preference }}</span>
                    </div>

                    <a href="{{ route('recommendation.show', ['studentId' => $student->id, 'method' => $method]) }}" class="inline-flex mt-4 text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                        Detail ranking & decision tree →
                    </a>
                </div>
            @empty
                <div class="md:col-span-2 xl:col-span-3 rounded-2xl bg-white border border-slate-200 p-10 text-center text-slate-500">
                    Belum ada rekomendasi. Tambahkan mahasiswa dan nilai terlebih dahulu.
                </div>
            @endforelse
        </div>
    </div>
@endsection
