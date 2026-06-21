<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMinatRequest;
use App\Models\Minat;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MinatController extends Controller
{
    public function index(): View
    {
        return view('admin.minat.index', [
            'minat' => Minat::withCount('mahasiswa')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.minat.create');
    }

    public function store(StoreMinatRequest $request): RedirectResponse
    {
        Minat::create($request->validated());

        return redirect()->route('admin.minat.index')->with('success', 'Minat berhasil ditambahkan.');
    }

    public function edit(Minat $minat): View
    {
        return view('admin.minat.edit', compact('minat'));
    }

    public function update(StoreMinatRequest $request, Minat $minat): RedirectResponse
    {
        $minat->update($request->validated());

        return redirect()->route('admin.minat.index')->with('success', 'Minat berhasil diperbarui.');
    }

    public function destroy(Minat $minat): RedirectResponse
    {
        $minat->delete();

        return redirect()->route('admin.minat.index')->with('success', 'Minat berhasil dihapus.');
    }
}
