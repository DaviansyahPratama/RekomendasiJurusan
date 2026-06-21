<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\PilihMinatRequest;
use App\Models\Minat;
use App\Services\RekomendasiMataKuliahService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MinatController extends Controller
{
    public function index(): View
    {
        $user = auth()->user()->load('minat');

        return view('mahasiswa.pilih-minat', [
            'user' => $user,
            'minatList' => Minat::orderBy('nama_minat')->get(),
        ]);
    }

    public function store(PilihMinatRequest $request, RekomendasiMataKuliahService $service): RedirectResponse
    {
        $user = auth()->user();
        $user->minat()->sync($request->minat_ids);
        $service->rekomendasikan($user->fresh('minat'));

        return redirect()->route('mahasiswa.pilih-minat')->with('success', 'Minat berhasil disimpan.');
    }
}
