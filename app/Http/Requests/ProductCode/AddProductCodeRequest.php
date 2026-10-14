<?php

namespace App\Http\Requests\ProductCode;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddProductCodeRequest extends FormRequest
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
            'data' => 'required|array|min:1',
            'data.*.code' => ['required', Rule::unique('product_codes', 'code')],
            'data.*.name' => 'required|string|min:3',
        ];
    }
}
