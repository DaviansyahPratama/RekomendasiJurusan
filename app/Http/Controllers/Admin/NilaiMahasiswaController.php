<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNilaiMahasiswaRequest;
use App\Models\MataKuliah;
use App\Models\NilaiMahasiswa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NilaiMahasiswaController extends Controller
{
    public function index(): View
    {
        return view('admin.nilai.index', [
            'nilai' => NilaiMahasiswa::with(['user', 'mataKuliah'])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.nilai.create', [
            'mahasiswa' => User::where('role', 'mahasiswa')->orderBy('name')->get(),
            'mataKuliah' => MataKuliah::orderBy('nama_mk')->get(),
        ]);
    }

    public function store(StoreNilaiMahasiswaRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['grade'] = NilaiMahasiswa::hitungGrade((float) $data['nilai_angka']);

        NilaiMahasiswa::updateOrCreate(
            ['user_id' => $data['user_id'], 'mata_kuliah_id' => $data['mata_kuliah_id']],
            $data
        );

        return redirect()->route('admin.nilai.index')->with('success', 'Nilai mahasiswa berhasil disimpan.');
    }

    public function edit(NilaiMahasiswa $nilai): View
    {
        return view('admin.nilai.edit', [
            'nilai' => $nilai,
            'mahasiswa' => User::where('role', 'mahasiswa')->orderBy('name')->get(),
            'mataKuliah' => MataKuliah::orderBy('nama_mk')->get(),
        ]);
    }

    public function update(StoreNilaiMahasiswaRequest $request, NilaiMahasiswa $nilai): RedirectResponse
    {
        $data = $request->validated();
        $data['grade'] = NilaiMahasiswa::hitungGrade((float) $data['nilai_angka']);
        $nilai->update($data);

        return redirect()->route('admin.nilai.index')->with('success', 'Nilai mahasiswa berhasil diperbarui.');
    }

    public function destroy(NilaiMahasiswa $nilai): RedirectResponse
    {
        $nilai->delete();

        return redirect()->route('admin.nilai.index')->with('success', 'Nilai mahasiswa berhasil dihapus.');
    }
}
