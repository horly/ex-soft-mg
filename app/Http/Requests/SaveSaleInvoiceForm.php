<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveSaleInvoiceForm extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
            'client_sales_invoice' => 'required',
            'invoice_concern_sales' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //
            'client_sales_invoice.required' => __('invoice.select_customer_please'),
            'invoice_concern_sales.required' => __('invoice.please_enter_the_invoice_subject'),
        ];
    }
}
