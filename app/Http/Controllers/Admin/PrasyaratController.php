<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrasyaratRequest;
use App\Models\MataKuliah;
use App\Models\Prasyarat;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PrasyaratController extends Controller
{
    public function index(): View
    {
        return view('admin.prasyarat.index', [
            'prasyarat' => Prasyarat::with(['mataKuliah', 'prasyaratMataKuliah'])->paginate(10),
            'mataKuliah' => MataKuliah::orderBy('nama_mk')->get(),
        ]);
    }

    public function store(StorePrasyaratRequest $request): RedirectResponse
    {
        $exists = Prasyarat::where('mata_kuliah_id', $request->mata_kuliah_id)
            ->where('prasyarat_id', $request->prasyarat_id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['prasyarat_id' => 'Prasyarat sudah terdaftar.']);
        }

        Prasyarat::create($request->validated());

        return redirect()->route('admin.prasyarat.index')->with('success', 'Prasyarat berhasil ditambahkan.');
    }

    public function destroy(Prasyarat $prasyarat): RedirectResponse
    {
        $prasyarat->delete();

        return redirect()->route('admin.prasyarat.index')->with('success', 'Prasyarat berhasil dihapus.');
    }
}
