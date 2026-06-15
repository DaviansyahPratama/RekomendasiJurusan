@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-indigo-700 to-purple-700 bg-clip-text text-transparent">
                    Sistem Rekomendasi Jurusan Mahasiswa
                </h1>
                <p class="mt-2 text-slate-600 max-w-2xl">
                    Berbasis relasi data, scoring system, decision tree, dan kombinatorika C(n,r) untuk demo akademik.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('students.create') }}" class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-lg shadow-indigo-200 hover:scale-[1.02] transition-transform">
                    + Input Mahasiswa
                </a>
                <a href="{{ route('recommendations.index') }}" class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-semibold hover:border-indigo-300 hover:text-indigo-700 transition">
                    Lihat Ranking
                </a>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
                <div class="text-sm text-slate-500">Mahasiswa</div>
                <div class="mt-2 text-3xl font-bold text-indigo-700">{{ $studentCount }}</div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
                <div class="text-sm text-slate-500">Jurusan</div>
                <div class="mt-2 text-3xl font-bold text-purple-700">{{ $majorCount }}</div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
                <div class="text-sm text-slate-500">Nilai MK</div>
                <div class="mt-2 text-3xl font-bold text-violet-700">{{ $scoreCount }}</div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
                <div class="text-sm text-slate-500">Rekomendasi</div>
                <div class="mt-2 text-3xl font-bold text-fuchsia-700">{{ $recommendationCount }}</div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-4">
            <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
                <div class="text-sm font-bold text-slate-900">1) Scoring System</div>
                <div class="mt-3 text-sm text-slate-600 space-y-1">
                    <div>Minat cocok <span class="font-bold text-indigo-600">+40</span></div>
                    <div>Preferensi cocok <span class="font-bold text-indigo-600">+30</span></div>
                    <div>Nilai rata-rata: ≥85 <span class="font-bold">+30</span>, 70–84 <span class="font-bold">+20</span>, &lt;70 <span class="font-bold">+10</span></div>
                </div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
                <div class="text-sm font-bold text-slate-900">2) Decision Tree</div>
                <div class="mt-3 text-sm text-slate-600 space-y-1">
                    <div>IF minat cocok → +40</div>
                    <div>IF preferensi cocok → +30</div>
                    <div>IF nilai tinggi → +30</div>
                </div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
                <div class="text-sm font-bold text-slate-900">3) Kombinatorika</div>
                <div class="mt-3 text-sm text-slate-600">
                    Rumus <span class="font-bold">C(n,r)</span> untuk semua kemungkinan kelompok mahasiswa.
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-4">
            <div class="rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 p-6 text-white shadow-xl shadow-indigo-200">
                <div class="text-lg font-bold">Kompleksitas Kasus: {{ ucfirst($complexity) }}</div>
                <p class="mt-2 text-sm text-white/90">
                    @if($complexity === 'sederhana')
                        Dataset kecil (≤4 mahasiswa) — cocok untuk penjelasan konsep dasar.
                    @elseif($complexity === 'menengah')
                        Dataset menengah (5–9 mahasiswa) — simulasi ranking multi-jurusan.
                    @else
                        Dataset luas (10+ mahasiswa) — analisis skala besar & efisiensi perhitungan.
                    @endif
                </p>
                <a href="{{ route('groups.combinations') }}?n={{ min($studentCount ?: 5, 5) }}&r=2" class="inline-block mt-4 rounded-xl bg-white/15 border border-white/25 px-4 py-2 text-sm font-semibold hover:bg-white/25 transition">
                    Simulasi Kombinasi →
                </a>
            </div>

            <div class="rounded-2xl bg-white border border-slate-200 p-6 shadow-sm">
                <div class="text-sm font-bold text-slate-900 mb-4">Mahasiswa Terbaru</div>
                <div class="space-y-3">
                    @forelse($recentStudents as $student)
                        @php $avg = $student->scores->avg('score'); @endphp
                        <div class="flex items-center justify-between gap-3 p-3 rounded-xl bg-slate-50 hover:bg-indigo-50 transition">
                            <div>
                                <div class="font-semibold text-sm">{{ $student->name }}</div>
                                <div class="text-xs text-slate-500">{{ implode(', ', $student->interest ?? []) }} · {{ $student->preference }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-slate-500">Rata-rata</div>
                                <div class="font-bold text-indigo-600">{{ $avg ? number_format($avg, 1) : '-' }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada data. Jalankan seeder atau tambah mahasiswa.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
