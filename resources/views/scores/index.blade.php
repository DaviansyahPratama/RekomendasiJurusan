@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Nilai Mata Kuliah</h1>
                <p class="mt-1 text-slate-600">Daftar nilai per mahasiswa — digunakan untuk perhitungan rata-rata skor rekomendasi.</p>
            </div>
            <a href="{{ route('students.create') }}" class="rounded-xl px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold text-sm hover:scale-[1.02] transition-transform">
                + Input Mahasiswa
            </a>
        </div>

        <div class="mt-6 grid md:grid-cols-2 gap-4">
            @forelse($students as $student)
                @php
                    $avg = $student->scores->avg('score');
                    $avgPoints = $avg >= 85 ? 30 : ($avg >= 70 ? 20 : 10);
                @endphp
                <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="font-bold text-slate-900">{{ $student->name }}</div>
                            <div class="text-xs text-slate-500 mt-1">{{ implode(', ', $student->interest ?? []) }} · {{ $student->preference }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-slate-500">Rata-rata</div>
                            <div class="text-lg font-bold text-indigo-600">{{ $avg ? number_format($avg, 1) : '-' }}</div>
                            <div class="text-xs text-slate-500">Poin: +{{ $avgPoints }}</div>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2">
                        @forelse($student->scores as $score)
                            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2 text-sm">
                                <span class="text-slate-700">{{ $score->subject_name }}</span>
                                <span class="font-bold px-2 py-0.5 rounded-lg
                                    {{ $score->score >= 85 ? 'bg-emerald-100 text-emerald-700' : ($score->score >= 70 ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                    {{ $score->score }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Belum ada nilai.</p>
                        @endforelse
                    </div>

                    <a href="{{ route('recommendation.show', $student->id) }}" class="inline-block mt-4 text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                        Lihat rekomendasi →
                    </a>
                </div>
            @empty
                <div class="md:col-span-2 rounded-2xl bg-white border border-slate-200 p-10 text-center text-slate-500">
                    Belum ada data nilai. Tambahkan mahasiswa terlebih dahulu.
                </div>
            @endforelse
        </div>
    </div>
@endsection
