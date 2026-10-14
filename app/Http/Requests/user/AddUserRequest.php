<?php

namespace App\Http\Requests\user;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddUserRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|min:6',
            'role_id' => 'required|min:1',
            'profile_picture' => 'mimes:jpg,jpeg,png|max:2048',
//            'profile_picture' => 'mimes:jpg,png,jpeg,gif|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
        ];
    }
}
