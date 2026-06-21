<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $mahasiswa->name ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $mahasiswa->email ?? '') }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">NIM</label>
        <input type="text" name="nim" class="form-control" value="{{ old('nim', $mahasiswa->nim ?? '') }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Semester Aktif</label>
        <input type="number" name="semester_aktif" class="form-control" value="{{ old('semester_aktif', $mahasiswa->semester_aktif ?? 1) }}" min="1" max="14" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">IPK</label>
        <input type="number" step="0.01" name="ipk" class="form-control" value="{{ old('ipk', $mahasiswa->ipk ?? 0) }}" min="0" max="4">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Password {{ isset($mahasiswa) ? '(kosongkan jika tidak diubah)' : '' }}</label>
        <input type="password" name="password" class="form-control" {{ isset($mahasiswa) ? '' : 'required' }}>
    </div>
</div>
