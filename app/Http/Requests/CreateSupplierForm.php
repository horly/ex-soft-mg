<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSupplierForm extends FormRequest
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
            'customer_type_sup' => 'required',
            
            'full_name_sup' => 'required|regex:/^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/',
            'grade_sup' => 'required',

            'email_sup' => 'required|email',
            'phone_number_sup' => 'required|numeric',
            'address_sup' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //
            'customer_type_sup.required' => __('supplier.please_select_supplier_type'),

            'full_name_sup.required' => __('supplier.enter_suppliers_full_name_please'),
            'full_name_sup.regex' => __('supplier.enter_a_valid_suppliers_full_name_please'),

            'grade_sup.required' => __('supplier.enter_supplier_grade_please'),

            'email_sup.required' => __('supplier.enter_the_suppliers_email_address_please'),

            'phone_number_sup.required' => __('supplier.enter_the_suppliers_phone_number_please'), 
            'phone_number_sup.numeric' => __('supplier.enter_a_valid_suppliers_phone_number_please'),

            'address_sup.required' => __('supplier.enter_the_suppliers_business_address_please'),
        ];
    }
}
