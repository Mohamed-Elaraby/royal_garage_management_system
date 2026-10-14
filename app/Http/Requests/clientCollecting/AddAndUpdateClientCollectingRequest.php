<?php

namespace App\Http\Requests\clientCollecting;

use Illuminate\Foundation\Http\FormRequest;

class AddAndUpdateClientCollectingRequest extends FormRequest
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
            'amount_paid'           => 'required_without:amount_paid_bank',
            'amount_paid_bank'      => 'required_without:amount_paid',
            'client_id'             => 'required',
            'branch_id'             => 'required',
            'collecting_date'       => 'required',
        ];
    }
}
