<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCTDTRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'ma_ctdt' => 'required|string|unique:chuong_trinh_dao_tao|max:50',
            'ten' => 'required|string|max:255',
            'khoa_id' => 'required|exists:khoa,id',
            'nganh_id' => 'required|exists:nganh,id',
            'chuyen_nganh_id' => 'nullable|exists:chuyen_nganh,id',
            'he_dao_tao_id' => 'required|exists:he_dao_tao,id',
            'nien_khoa_id' => 'required|exists:nien_khoa,id',
            'hieu_luc_tu' => 'required|date',
            'hieu_luc_den' => 'nullable|date|after:hieu_luc_tu',
            'mo_ta' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'ma_ctdt.unique' => 'Mã CTĐT này đã tồn tại',
            'hieu_luc_den.after' => 'Hiệu lực đến phải sau hiệu lực từ',
        ];
    }
}
