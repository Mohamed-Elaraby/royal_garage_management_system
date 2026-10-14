<?php

namespace App\Http\Requests\SaleOrder;

use Illuminate\Foundation\Http\FormRequest;

class EditOpenSaleOrder extends FormRequest
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
            'branch_id' => 'required',
            'invoice_date' => 'required',
            'product_data' => 'required|array|min:1',
            'product_data.*.item_quantity' => 'required',
            'product_data.*.item_price' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'product_data.required' => 'يجب اضافة صنف واحد على الاقل الى الفاتورة .',
        ];
    }
}
