<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\PengambilanMataKuliah;
use App\Services\RekomendasiMataKuliahService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SimulasiController extends Controller
{
    public function index(RekomendasiMataKuliahService $service): View
    {
        $user = auth()->user()->load('pengambilanMataKuliah.mataKuliah');
        $semesterBerikutnya = ($user->semester_aktif ?? 1) + 1;

        $pengambilan = $user->pengambilanMataKuliah()
            ->where('semester_ambil', $semesterBerikutnya)
            ->with('mataKuliah')
            ->get();

        $totalSks = $pengambilan->sum(fn ($p) => $p->mataKuliah->sks);
        $rekomendasi = $service->rekomendasikan($user, $totalSks);
        $eligible = $rekomendasi->where('direkomendasikan', true);

        return view('mahasiswa.simulasi', [
            'user' => $user,
            'semesterBerikutnya' => $semesterBerikutnya,
            'pengambilan' => $pengambilan,
            'totalSks' => $totalSks,
            'eligible' => $eligible,
            'rekomendasi' => $rekomendasi,
        ]);
    }

    public function store(Request $request, RekomendasiMataKuliahService $service): RedirectResponse
    {
        $request->validate([
            'mata_kuliah_id' => ['required', 'exists:mata_kuliah,id'],
        ]);

        $user = auth()->user();
        $semesterBerikutnya = ($user->semester_aktif ?? 1) + 1;

        PengambilanMataKuliah::firstOrCreate([
            'user_id' => $user->id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'semester_ambil' => $semesterBerikutnya,
        ]);

        return redirect()->route('mahasiswa.simulasi')->with('success', 'Mata kuliah ditambahkan ke simulasi.');
    }

    public function destroy(PengambilanMataKuliah $pengambilan): RedirectResponse
    {
        abort_unless($pengambilan->user_id === auth()->id(), 403);
        $pengambilan->delete();

        return redirect()->route('mahasiswa.simulasi')->with('success', 'Mata kuliah dihapus dari simulasi.');
    }
}
