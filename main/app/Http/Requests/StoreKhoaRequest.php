<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKhoaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'ma' => 'required|string|unique:khoa|max:50',
            'ten' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
        ];
    }
}
