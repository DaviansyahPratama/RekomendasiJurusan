<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMataKuliahRequest;
use App\Models\MataKuliah;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MataKuliahController extends Controller
{
    public function index(): View
    {
        return view('admin.mata-kuliah.index', [
            'mataKuliah' => MataKuliah::orderBy('semester')->orderBy('kode_mk')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.mata-kuliah.create', [
            'kategoriOptions' => $this->kategoriOptions(),
        ]);
    }

    public function store(StoreMataKuliahRequest $request): RedirectResponse
    {
        MataKuliah::create($request->validated());

        return redirect()->route('admin.mata-kuliah.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function show(MataKuliah $mataKuliah): View
    {
        $mataKuliah->load('prasyarat');

        return view('admin.mata-kuliah.show', compact('mataKuliah'));
    }

    public function edit(MataKuliah $mataKuliah): View
    {
        return view('admin.mata-kuliah.edit', [
            'mataKuliah' => $mataKuliah,
            'kategoriOptions' => $this->kategoriOptions(),
        ]);
    }

    public function update(StoreMataKuliahRequest $request, MataKuliah $mataKuliah): RedirectResponse
    {
        $mataKuliah->update($request->validated());

        return redirect()->route('admin.mata-kuliah.index')->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy(MataKuliah $mataKuliah): RedirectResponse
    {
        $mataKuliah->delete();

        return redirect()->route('admin.mata-kuliah.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }

    private function kategoriOptions(): array
    {
        return [
            'Pemrograman',
            'Jaringan',
            'Data Science',
            'Artificial Intelligence',
            'Cyber Security',
            'Multimedia',
        ];
    }
}
