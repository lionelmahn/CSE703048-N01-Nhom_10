<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoMonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'ma' => 'required|string|unique:bo_mon|max:50',
            'ten' => 'required|string|max:255',
            'khoa_id' => 'required|exists:khoa,id',
        ];
    }
}
