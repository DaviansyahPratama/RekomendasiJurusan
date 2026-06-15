@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Rekomendasi Jurusan</h1>
                <p class="mt-1 text-slate-600">
                    Mahasiswa: <span class="font-bold">{{ $student->name }}</span>
                </p>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($student->interest ?? [] as $tag)
                        <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-sm font-semibold">{{ $tag }}</span>
                    @endforeach
                    <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-sm font-semibold">{{ $student->preference }}</span>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 items-center">
                <form method="GET" class="flex gap-2 items-center">
                    <select name="method" onchange="this.form.submit()" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        @foreach($methods as $key => $label)
                            <option value="{{ $key }}" {{ $method === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('students.index') }}" class="rounded-xl px-4 py-2 border border-slate-200 text-slate-700 hover:bg-slate-50 transition text-sm font-semibold">Kembali</a>
            </div>
        </div>

        <div class="mt-6 grid lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 space-y-4">
                <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-bold text-slate-900">Ranking Jurusan</div>
                            <div class="text-xs text-slate-500 mt-1">Metode: {{ $methods[$method] ?? $method }}</div>
                        </div>
                        @if($best)
                            <div class="px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-bold shadow-lg shadow-indigo-200">
                                Terbaik: {{ $best['major_name'] }}
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 space-y-3">
                        @foreach($ranked as $idx => $row)
                            <div class="rounded-2xl p-4 border border-slate-200 bg-gradient-to-br from-white to-indigo-50/30 hover:shadow-md hover:-translate-y-0.5 transition-all">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-600 text-white text-xs font-bold">#{{ $idx + 1 }}</span>
                                            <span class="font-bold text-slate-900">{{ $row['major_name'] }}</span>
                                        </div>
                                        <div class="text-xs text-slate-500 mt-2">
                                            Total skor: <span class="font-bold text-indigo-600 text-base">{{ $row['score_total'] }}</span>
                                        </div>
                                    </div>
                                    <div class="text-xs text-right space-y-1">
                                        <div><span class="font-semibold text-slate-600">Minat</span> +{{ $row['breakdown']['interest_match'] }}</div>
                                        <div><span class="font-semibold text-slate-600">Preferensi</span> +{{ $row['breakdown']['preference_match'] }}</div>
                                        <div><span class="font-semibold text-slate-600">Nilai</span> +{{ $row['breakdown']['average_points'] }}</div>
                                    </div>
                                </div>

                                @if(in_array($method, ['scoring', 'decision_tree'], true))
                                    <div class="mt-3">
                                        <div class="text-xs font-bold text-slate-700">Decision Tree Explanation</div>
                                        <pre class="mt-2 text-xs whitespace-pre-wrap rounded-xl bg-slate-50 p-3 text-slate-700 border border-slate-200 font-mono">{{ $row['explanation'] }}</pre>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($method === 'truth_table' && $truth_table)
                    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm overflow-x-auto">
                        <div class="text-sm font-bold text-slate-900 mb-4">Tabel Kebenaran</div>
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50">
                                <tr class="text-left text-slate-500">
                                    <th class="px-3 py-2">Jurusan</th>
                                    <th class="px-3 py-2">Minat</th>
                                    <th class="px-3 py-2">Preferensi</th>
                                    <th class="px-3 py-2">Nilai</th>
                                    <th class="px-3 py-2">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($truth_table as $row)
                                    <tr class="hover:bg-indigo-50/50">
                                        <td class="px-3 py-2 font-medium">{{ $row['major'] }}</td>
                                        <td class="px-3 py-2">{{ $row['minat'] }}</td>
                                        <td class="px-3 py-2">{{ $row['preferensi'] }}</td>
                                        <td class="px-3 py-2">{{ $row['nilai'] }}</td>
                                        <td class="px-3 py-2 font-bold text-indigo-600">{{ $row['total'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if($method === 'graph' && $relation_graph)
                    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                        <div class="text-sm font-bold text-slate-900 mb-4">Graf Relasi Mahasiswa → Jurusan</div>
                        <div class="space-y-2">
                            @foreach($relation_graph['edges'] ?? [] as $edge)
                                @php
                                    $toNode = collect($relation_graph['nodes'])->firstWhere('id', $edge['to']);
                                @endphp
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                                    <span class="font-semibold text-indigo-700">{{ $student->name }}</span>
                                    <span class="text-slate-400">→</span>
                                    <span class="font-medium">{{ $toNode['label'] ?? $edge['to'] }}</span>
                                    <span class="ml-auto px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">{{ $edge['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-4">
                <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm sticky top-6">
                    <div class="text-sm font-bold text-slate-900">Analisis Keputusan</div>
                    <ul class="mt-3 text-sm text-slate-600 space-y-2 list-disc pl-5">
                        <li>Skor dihitung per jurusan lalu diranking otomatis.</li>
                        <li>Decision tree menampilkan langkah IF/THEN secara eksplisit.</li>
                        <li>Relasi: <span class="font-semibold">students → scores → recommendations → majors</span></li>
                    </ul>

                    <div class="mt-4 rounded-xl p-4 bg-indigo-50 border border-indigo-100">
                        <div class="text-sm font-bold text-indigo-800">Efisiensi Perhitungan</div>
                        <div class="mt-1 text-xs text-indigo-700">
                            O(m) per mahasiswa dengan m = jumlah jurusan. Hasil dipersist via updateOrCreate.
                        </div>
                    </div>

                    <div class="mt-4 rounded-xl p-4 bg-purple-50 border border-purple-100">
                        <div class="text-sm font-bold text-purple-800">Validasi Hasil</div>
                        <div class="mt-1 text-xs text-purple-700">
                            @if($best)
                                Jurusan {{ $best['major_name'] }} memiliki skor tertinggi ({{ $best['score_total'] }}).
                            @else
                                Belum ada hasil validasi.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
