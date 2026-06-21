<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMinatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('minat')?->id;

        return [
            'nama_minat' => ['required', 'string', 'max:100', 'unique:minat,nama_minat,'.$id],
        ];
    }
}
