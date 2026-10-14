<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeviceReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'report_device.*' => 'mimes:jpg,jpeg,png|max:2048',
            'report_device_file' => 'mimes:pdf,xls',
//            'check_id' => 'unique:files,check_id|unique:images,check_id'
        ];
    }

//    public function messages()
//    {
//        return ['check_id.unique' => 'تم رفع تقرير جهاز الفحص من قبل'];
//    }
}
