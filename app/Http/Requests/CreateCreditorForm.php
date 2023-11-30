<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCreditorForm extends FormRequest
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
            'customer_type_cr' => 'required',
            
            'full_name_cr' => 'required|regex:/^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/',
            'grade_cr' => 'required',

            'email_cr' => 'required|email',
            'phone_number_cr' => 'required|numeric',
            'address_cr' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //
            'customer_type_cr.required' => __('creditor.please_select_creditor_type'),

            'full_name_cr.required' => __('creditor.enter_creditors_full_name_please'),
            'full_name_cr.regex' => __('creditor.enter_a_valid_creditors_full_name_please'),

            'grade_cr.required' => __('creditor.enter_creditors_grade_please'),

            'email_cr.required' => __('creditor.enter_the_creditors_email_address_please'),

            'phone_number_cr.required' => __('creditor.enter_the_creditors_phone_number_please'), 
            'phone_number_cr.numeric' => __('creditor.enter_a_valid_creditors_phone_number_please'),

            'address_cr.required' => __('creditor.enter_the_creditors_business_address_please'),
        ];
    }
}
