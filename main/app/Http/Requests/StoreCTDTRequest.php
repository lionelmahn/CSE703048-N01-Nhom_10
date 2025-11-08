<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\ChuongTrinhDaoTao;

class StoreCTDTRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }
    
    public function rules(): array
    {
        return [
            'ma_ctdt' => [
                'required',
                'string',
                'max:50',
                'unique:chuong_trinh_dao_tao',
                'regex:/^[A-Z0-9\-]+$/', // Only uppercase letters, numbers, dash
            ],
            'ten' => 'required|string|max:255',
            'bac_hoc_id' => 'required|exists:bac_hoc,id',
            'loai_hinh_dao_tao_id' => 'required|exists:loai_hinh_dao_tao,id',
            'khoa_id' => 'required|exists:khoa,id',
            'nganh_id' => 'required|exists:nganh,id',
            'chuyen_nganh_id' => 'nullable|exists:chuyen_nganh,id',
            'nien_khoa_id' => 'required|exists:nien_khoa,id',
            'khoa_hoc_id' => 'required|exists:khoa_hoc,id',
            'hieu_luc_tu' => 'required|date',
            'hieu_luc_den' => 'nullable|date|after:hieu_luc_tu',
            'mo_ta' => 'nullable|string',
            'source_ctdt_id' => 'nullable|exists:chuong_trinh_dao_tao,id',
        ];
    }
    
    public function messages(): array
    {
        return [
            'ma_ctdt.unique' => 'Mã CTĐT này đã tồn tại trong hệ thống',
            'ma_ctdt.regex' => 'Mã CTĐT chỉ được chứa chữ in hoa, số và dấu gạch ngang',
            'hieu_luc_den.after' => 'Hiệu lực đến phải sau hiệu lực từ',
            'bac_hoc_id.required' => 'Vui lòng chọn bậc học',
            'loai_hinh_dao_tao_id.required' => 'Vui lòng chọn loại hình đào tạo',
            'khoa_hoc_id.required' => 'Vui lòng chọn khóa học',
            'khoa_id.required' => 'Vui lòng chọn khoa quản lý',
            'ten.required' => 'Vui lòng nhập tên CTĐT',
        ];
    }
    
    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validate ma_ctdt format
            if ($this->ma_ctdt && !ChuongTrinhDaoTao::isValidMaCtdtFormat($this->ma_ctdt)) {
                $validator->errors()->add(
                    'ma_ctdt',
                    'Mã CTĐT không đúng định dạng. Chỉ được sử dụng chữ in hoa, số và dấu gạch ngang.'
                );
            }
        });
    }
}
