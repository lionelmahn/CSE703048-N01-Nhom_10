<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCTDTRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ctdt = $this->route('ctdt');

        return [
            'ma_ctdt' => ['required', 'string', 'max:50', Rule::unique('chuong_trinh_dao_tao')->ignore($ctdt->id)],
            'ten' => 'required|string|max:255',
            'khoa_id' => 'required|exists:khoa,id',
            'bac_hoc_id' => 'required|exists:bac_hoc,id',
            'loai_hinh_dao_tao_id' => 'required|exists:loai_hinh_dao_tao,id',
            'khoa_hoc_id' => 'required|exists:khoa_hoc,id',
            'nganh_id' => 'required|exists:nganh,id',
            'chuyen_nganh_id' => 'nullable|exists:chuyen_nganh,id',
            'nien_khoa_id' => 'required|exists:nien_khoa,id',
            'hieu_luc_tu' => 'required|date',
            'hieu_luc_den' => 'nullable|date|after:hieu_luc_tu',
            'mo_ta' => 'nullable|string',
        ];
    }
}
