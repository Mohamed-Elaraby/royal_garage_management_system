<?php

namespace App\Http\Requests\Check;

use Illuminate\Foundation\Http\FormRequest;

class AddAndUpdateCheckRequest extends FormRequest
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
            'counter_number' => 'required' ,
            'chassis_number' => 'required',
            'plate_number' => 'required' ,
            'car_type' => 'required',
            'car_size' => 'required',
            'car_color' => 'required',
            'car_status_report' => 'required',
        ];
    }
}
