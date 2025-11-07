<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBoMonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        $boMon = $this->route('bo_mon');

        return [
            'ma' => ['required', 'string', 'max:50', Rule::unique('bo_mon')->ignore($boMon->id)],
            'ten' => 'required|string|max:255',
            'khoa_id' => 'required|exists:khoa,id',
        ];
    }
}
