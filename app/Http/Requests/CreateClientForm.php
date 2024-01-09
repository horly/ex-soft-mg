<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateClientForm extends FormRequest
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
            'customer_type_cl' => 'required',
            
            'full_name_cl' => 'required|regex:/^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/',
            'grade_cl' => 'required',

            'email_cl' => 'required|email',
            'phone_number_cl' => 'required|numeric',
            'address_cl' => 'required',
            'department_cl' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //
            'customer_type_cl.required' => __('client.please_select_customer_type'),

            'full_name_cl.required' => __('client.enter_customers_full_name_please'),
            'full_name_cl.regex' => __('client.enter_a_valid_customers_full_name_please'),

            'grade_cl.required' => __('client.enter_customer_grade_please'),

            'email_cl.required' => __('client.enter_the_customers_email_address_please'),

            'phone_number_cl.required' => __('client.enter_the_customers_phone_number_please'), 
            'phone_number_cl.numeric' => __('client.enter_a_valid_customers_phone_number_please'),

            'address_cl.required' => __('client.enter_the_customers_business_address_please'),
            'department_cl.required' => __('client.please_enter_the_contact_department'),
        ];
    }
}
