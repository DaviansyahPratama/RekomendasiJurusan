<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMataKuliahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('mata_kuliah')?->id;

        return [
            'kode_mk' => ['required', 'string', 'max:20', 'unique:mata_kuliah,kode_mk,'.$id],
            'nama_mk' => ['required', 'string', 'max:255'],
            'sks' => ['required', 'integer', 'min:1', 'max:6'],
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
            'kategori' => ['required', 'string', 'max:100'],
            'tingkat_kesulitan' => ['required', 'integer', 'min:1', 'max:5'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }
}
