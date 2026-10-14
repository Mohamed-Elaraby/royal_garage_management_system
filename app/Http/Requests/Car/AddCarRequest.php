<?php

namespace App\Http\Requests\Car;

use App\Models\Car;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddCarRequest extends FormRequest
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
            'client_id' => 'required',
            'car_type_id' => 'required',
            'car_size_id' => 'required',
            'car_engine_id' => 'required',
            'car_model_id' => 'required',
            'chassis_number' => ['required', Rule::unique('cars', 'chassis_number')],
            'plate_number' => 'required' ,
            'car_color' => 'required',
        ];
    }
}
