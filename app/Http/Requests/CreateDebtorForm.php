<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDebtorForm extends FormRequest
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
            'customer_type_deb' => 'required',
            
            'full_name_deb' => 'required|regex:/^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/',
            'grade_deb' => 'required',

            'email_deb' => 'required|email',
            'phone_number_deb' => 'required|numeric',
            'address_deb' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //
            'customer_type_deb.required' => __('debtor.please_select_debtor_type'),

            'full_name_deb.required' => __('debtor.enter_debtors_full_name_please'),
            'full_name_deb.regex' => __('debtor.enter_a_valid_debtors_full_name_please'),

            'grade_deb.required' => __('debtor.enter_debtors_grade_please'),

            'email_deb.required' => __('debtor.enter_the_debtors_email_address_please'),

            'phone_number_deb.required' => __('debtor.enter_the_debtors_phone_number_please'), 
            'phone_number_deb.numeric' => __('debtor.enter_a_valid_debtors_phone_number_please'),

            'address_deb.required' => __('debtor.enter_the_debtors_business_address_please'),
        ];
    }
}
