<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrasyaratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mata_kuliah_id' => ['required', 'exists:mata_kuliah,id'],
            'prasyarat_id' => ['required', 'exists:mata_kuliah,id', 'different:mata_kuliah_id'],
        ];
    }
}
