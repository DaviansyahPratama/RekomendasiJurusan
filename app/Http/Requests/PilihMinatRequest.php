<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PilihMinatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'minat_ids' => ['required', 'array', 'min:1'],
            'minat_ids.*' => ['exists:minat,id'],
        ];
    }
}
