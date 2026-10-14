<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends FormRequest
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
            'car_type_id' => 'required',
            'car_size_id' => 'required',
            'car_model_id' => 'required',
            'chassis_number' => ['required', Rule::unique('cars', 'chassis_number')->ignore($this->car)],
            'plate_number' => 'required' ,
            'car_color' => 'required',
        ];
    }
}
