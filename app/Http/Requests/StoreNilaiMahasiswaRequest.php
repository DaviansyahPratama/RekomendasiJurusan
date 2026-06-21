<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNilaiMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('nilai_mahasiswa')?->id;

        return [
            'user_id' => ['required', 'exists:users,id'],
            'mata_kuliah_id' => ['required', 'exists:mata_kuliah,id'],
            'nilai_angka' => ['required', 'numeric', 'min:0', 'max:100'],
            'semester_lulus' => ['required', 'integer', 'min:1', 'max:14'],
        ];
    }
}
