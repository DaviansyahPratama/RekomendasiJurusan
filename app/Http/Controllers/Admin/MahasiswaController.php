<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMahasiswaRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MahasiswaController extends Controller
{
    public function index(): View
    {
        return view('admin.mahasiswa.index', [
            'mahasiswa' => User::where('role', 'mahasiswa')
                ->withCount('nilaiMahasiswa')
                ->orderBy('name')
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.mahasiswa.create');
    }

    public function store(StoreMahasiswaRequest $request): RedirectResponse
    {
        User::create([
            ...$request->validated(),
            'role' => 'mahasiswa',
            'ipk' => $request->input('ipk', 0),
        ]);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function show(User $mahasiswa): View
    {
        abort_unless($mahasiswa->isMahasiswa(), 404);

        $mahasiswa->load(['minat']);

        $nilai = $mahasiswa->nilaiMahasiswa()
            ->with('mataKuliah')
            ->orderBy('semester_lulus')
            ->paginate(10);

        return view('admin.mahasiswa.show', compact('mahasiswa', 'nilai'));
    }

    public function edit(User $mahasiswa): View
    {
        abort_unless($mahasiswa->isMahasiswa(), 404);

        return view('admin.mahasiswa.edit', compact('mahasiswa'));
    }

    public function update(StoreMahasiswaRequest $request, User $mahasiswa): RedirectResponse
    {
        abort_unless($mahasiswa->isMahasiswa(), 404);

        $data = $request->validated();
        if (empty($data['password'])) {
            unset($data['password']);
        }
        $mahasiswa->update($data);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(User $mahasiswa): RedirectResponse
    {
        abort_unless($mahasiswa->isMahasiswa(), 404);
        $mahasiswa->delete();

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
