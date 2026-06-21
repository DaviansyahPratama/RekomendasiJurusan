<div class="mb-3">
    <label class="form-label">Mahasiswa</label>
    <select name="user_id" class="form-select" required>
        <option value="">-- Pilih --</option>
        @foreach($mahasiswa as $m)
            <option value="{{ $m->id }}" @selected(old('user_id', optional($nilai ?? null)->user_id) == $m->id)>{{ $m->name }} ({{ $m->nim }})</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Mata Kuliah</label>
    <select name="mata_kuliah_id" class="form-select" required>
        <option value="">-- Pilih --</option>
        @foreach($mataKuliah as $mk)
            <option value="{{ $mk->id }}" @selected(old('mata_kuliah_id', optional($nilai ?? null)->mata_kuliah_id) == $mk->id)>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
        @endforeach
    </select>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nilai Angka (0-100)</label>
        <input type="number" step="0.01" name="nilai_angka" class="form-control" value="{{ old('nilai_angka', optional($nilai ?? null)->nilai_angka) }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Semester Lulus</label>
        <input type="number" name="semester_lulus" class="form-control" value="{{ old('semester_lulus', optional($nilai ?? null)->semester_lulus ?? 1) }}" min="1" max="14" required>
    </div>
</div>
