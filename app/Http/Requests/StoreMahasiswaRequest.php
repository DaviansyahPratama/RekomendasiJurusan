<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('mahasiswa')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$id],
            'nim' => ['required', 'string', 'max:20', 'unique:users,nim,'.$id],
            'semester_aktif' => ['required', 'integer', 'min:1', 'max:14'],
            'ipk' => ['nullable', 'numeric', 'min:0', 'max:4'],
            'password' => [$id ? 'nullable' : 'required', 'string', 'min:6'],
        ];
    }
}
