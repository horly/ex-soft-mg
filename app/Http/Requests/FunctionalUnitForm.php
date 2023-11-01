<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FunctionalUnitForm extends FormRequest
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
            'unit_name' => 'required',
            'unit_address' => 'required',
            'unit_phone' => 'required|numeric',
            'unit_email' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            //
            'unit_name.required' => __('entreprise.enter_your_functional_unit_name_please'),
            'unit_address.required' => __('entreprise.enter_your_functional_unit_address_please'),

            'unit_phone.required' => __('entreprise.enter_your_functional_unit_phone_number_please'),
            'unit_phone.numeric' => __('main.enter_a_valid_phone_number_please'),

            'unit_email.required' => __('entreprise.enter_your_functional_unit_phone_number_please'),
            'unit_email.email' => __('auth.enter_a_valid_email_please'),
        ];
    }
}
