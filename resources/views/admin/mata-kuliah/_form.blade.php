<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Kode MK</label>
        <input type="text" name="kode_mk" class="form-control" value="{{ old('kode_mk', optional($mataKuliah ?? null)->kode_mk) }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Nama MK</label>
        <input type="text" name="nama_mk" class="form-control" value="{{ old('nama_mk', optional($mataKuliah ?? null)->nama_mk) }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">SKS</label>
        <input type="number" name="sks" class="form-control" value="{{ old('sks', optional($mataKuliah ?? null)->sks ?? 3) }}" min="1" max="6" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Semester</label>
        <input type="number" name="semester" class="form-control" value="{{ old('semester', optional($mataKuliah ?? null)->semester ?? 1) }}" min="1" max="14" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Kategori</label>
        <select name="kategori" class="form-select" required>
            @foreach($kategoriOptions as $opt)
                <option value="{{ $opt }}" @selected(old('kategori', optional($mataKuliah ?? null)->kategori) === $opt)>{{ $opt }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Tingkat Kesulitan (1-5)</label>
        <input type="number" name="tingkat_kesulitan" class="form-control" value="{{ old('tingkat_kesulitan', optional($mataKuliah ?? null)->tingkat_kesulitan ?? 1) }}" min="1" max="5" required>
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', optional($mataKuliah ?? null)->deskripsi) }}</textarea>
    </div>
</div>
