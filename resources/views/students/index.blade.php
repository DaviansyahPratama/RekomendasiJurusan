@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Data Mahasiswa</h1>
                <p class="mt-1 text-slate-600">Kelola nama, minat, preferensi, dan akses rekomendasi jurusan.</p>
            </div>
            <a href="{{ route('students.create') }}" class="rounded-xl px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-lg shadow-indigo-200 hover:scale-[1.02] transition-transform">
                + Tambah
            </a>
        </div>

        <div class="mt-6 rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-left text-slate-500">
                            <th class="px-5 py-3 font-semibold">Nama</th>
                            <th class="px-5 py-3 font-semibold">Minat</th>
                            <th class="px-5 py-3 font-semibold">Preferensi</th>
                            <th class="px-5 py-3 font-semibold">Rata-rata Nilai</th>
                            <th class="px-5 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($students as $student)
                            @php $avg = $student->scores->avg('score'); @endphp
                            <tr class="hover:bg-indigo-50/50 transition">
                                <td class="px-5 py-4 font-semibold text-slate-900">{{ $student->name }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($student->interest ?? [] as $tag)
                                            <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-medium">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 text-xs font-medium">{{ $student->preference }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    @if($avg !== null)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg font-bold text-sm
                                            {{ $avg >= 85 ? 'bg-emerald-100 text-emerald-700' : ($avg >= 70 ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                            {{ number_format((float) $avg, 1) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <a href="{{ route('recommendation.show', $student->id) }}" class="inline-flex items-center rounded-lg px-3 py-2 bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-500 transition">
                                        Rekomendasi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-slate-500">Belum ada data mahasiswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
