<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHocPhanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'ma_hp' => 'required|string|unique:hoc_phan|max:50',
            'ten_hp' => 'required|string|max:255',
            'so_tinchi' => 'required|integer|min:1|max:12',
            'khoa_id' => 'required|exists:khoa,id',
            'bo_mon_id' => 'nullable|exists:bo_mon,id',
            'mo_ta' => 'nullable|string',
            'active' => 'boolean',
        ];
    }
}
