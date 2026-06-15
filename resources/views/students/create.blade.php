@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Input Mahasiswa</h1>
        <p class="mt-1 text-slate-600">Masukkan data minat, preferensi, dan nilai mata kuliah (0–100).</p>

        <form method="POST" action="{{ route('students.store') }}" class="mt-6 rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700">Nama Mahasiswa</label>
                <input name="name" value="{{ old('name') }}" class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" />
                @error('name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700">Minat</label>
                <div class="mt-2 grid grid-cols-2 gap-2">
                    @foreach($interestOptions as $opt)
                        <label class="flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2.5 hover:border-indigo-300 hover:bg-indigo-50 cursor-pointer transition">
                            <input type="checkbox" name="interest[]" value="{{ $opt }}" class="accent-indigo-600" {{ in_array($opt, old('interest', []), true) ? 'checked' : '' }}>
                            <span class="text-sm font-medium">{{ $opt }}</span>
                        </label>
                    @endforeach
                </div>
                @error('interest')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700">Preferensi</label>
                <select name="preference" class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    <option value="">-- pilih preferensi --</option>
                    @foreach($preferenceOptions as $opt)
                        <option value="{{ $opt }}" {{ old('preference') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('preference')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mt-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Mata Kuliah & Nilai</div>
                        <div class="text-xs text-slate-500 mt-1">Tambahkan beberapa mata kuliah beserta nilainya.</div>
                    </div>
                    <button type="button" id="addRow" class="rounded-lg px-3 py-2 bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-500 transition">+ Tambah</button>
                </div>

                <div id="rows" class="mt-4 space-y-3">
                    @php
                        $subjects = old('subjects', ['Pemrograman Web']);
                        $scores = old('scores', [85]);
                    @endphp
                    @for($i = 0; $i < max(count($subjects), count($scores)); $i++)
                        <div class="flex gap-3 items-start score-row">
                            <div class="flex-1">
                                <label class="block text-xs text-slate-500">Mata Kuliah</label>
                                <input name="subjects[]" value="{{ $subjects[$i] ?? '' }}" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="mis. Machine Learning" />
                            </div>
                            <div class="w-28">
                                <label class="block text-xs text-slate-500">Nilai</label>
                                <input type="number" name="scores[]" value="{{ $scores[$i] ?? '' }}" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none" min="0" max="100" />
                            </div>
                            <button type="button" class="removeRow mt-5 rounded-lg px-3 py-2 border border-slate-200 text-slate-600 text-xs hover:bg-slate-50">Hapus</button>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('students.index') }}" class="rounded-xl px-4 py-2 border border-slate-200 text-slate-700 hover:bg-slate-50 transition text-sm font-semibold">Batal</a>
                <button class="rounded-xl px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-lg shadow-indigo-200 hover:scale-[1.02] transition-transform text-sm" type="submit">
                    Simpan & Rekomendasikan
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('addRow')?.addEventListener('click', () => {
            const div = document.createElement('div');
            div.className = 'flex gap-3 items-start score-row';
            div.innerHTML = `
                <div class="flex-1">
                    <label class="block text-xs text-slate-500">Mata Kuliah</label>
                    <input name="subjects[]" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" placeholder="mis. Basis Data" />
                </div>
                <div class="w-28">
                    <label class="block text-xs text-slate-500">Nilai</label>
                    <input type="number" name="scores[]" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2" min="0" max="100" />
                </div>
                <button type="button" class="removeRow mt-5 rounded-lg px-3 py-2 border border-slate-200 text-slate-600 text-xs hover:bg-slate-50">Hapus</button>
            `;
            document.getElementById('rows').appendChild(div);
        });

        document.getElementById('rows')?.addEventListener('click', (e) => {
            const btn = e.target.closest('.removeRow');
            if (!btn) return;
            const rows = document.querySelectorAll('.score-row');
            if (rows.length <= 1) return;
            btn.closest('.score-row').remove();
        });
    </script>
@endsection
