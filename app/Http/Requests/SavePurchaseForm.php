<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavePurchaseForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'supplier_purchase' => 'required',
            'designation_purchase' => 'required',
            'amount_purchase' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //
            'supplier_purchase.required' => __('supplier.select_a_supplier_please'),
            'designation_purchase.required' => __('expenses.please_enter_the_purchase_name'),
            'amount_purchase.required' => __('expenses.please_enter_the_purchase_amount'),
        ];
    }
}
