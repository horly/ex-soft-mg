<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addNewContactClientForm extends FormRequest
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
            'full_name_cl' => 'required',
            'phone_number_cl' => 'required|numeric',
            'address_cl' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //
            'full_name_cl.required' => __('client.enter_a_valid_customers_full_name_please'),
            'phone_number_cl.required' => __('client.enter_the_customers_phone_number_please'),
            'phone_number_cl.numeric' => __('client.enter_the_customers_phone_number_please'),
            'address_cl.required' => __('client.enter_the_customers_business_address_please'),
        ];
    }
}
