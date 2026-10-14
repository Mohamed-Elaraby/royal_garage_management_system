<?php

namespace App\Http\Requests\user;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user)],
            'password' => 'nullable|min:6|confirmed',
            'role_id' => 'required|min:1',
            'profile_picture' => 'mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
