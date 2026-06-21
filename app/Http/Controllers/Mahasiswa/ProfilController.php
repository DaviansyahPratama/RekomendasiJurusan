<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfilRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function show(): View
    {
        $user = auth()->user()->load('minat');

        return view('mahasiswa.profil', compact('user'));
    }

    public function update(UpdateProfilRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (empty($data['password'])) {
            unset($data['password']);
        }
        auth()->user()->update($data);

        return redirect()->route('mahasiswa.profil')->with('success', 'Profil berhasil diperbarui.');
    }
}
