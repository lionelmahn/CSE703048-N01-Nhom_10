<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKhoaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        $khoa = $this->route('khoa');
        
        return [
            'ma' => ['required', 'string', 'max:50', Rule::unique('khoa')->ignore($khoa->id)],
            'ten' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
        ];
    }
}
