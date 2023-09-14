<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEntrepriseForm extends FormRequest
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
            'name_entreprise' => 'required',
            'rccm_entreprise' => 'required',
            'idnat_entreprise' => 'required',
            'nif_entreprise' => 'required',
            'address_entreprise' => 'required',
            'country_entreprise' => 'required',
            'phone_entreprise' => 'required|numeric',
            'email_entreprise' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            //
            'name_entreprise.required' => __('main.enter_your_company_name_please'),
            'rccm_entreprise.required' => __('main.enter_your_companys_rccm_please'),
            'idnat_entreprise.required' => __('main.enter_your_companys_national_identification_please'),
            'nif_entreprise.required' => __('main.enter_your_companys_tax_id_number_please'),
            'address_entreprise.required' => __('main.enter_your_company_address_please'),
            'country_entreprise.required' => __('main.select_your_company_country_please'),

            'phone_entreprise.required' => __('main.enter_a_valid_phone_number_please'),
            'phone_entreprise.numeric' => __('main.enter_a_valid_phone_number_please'),

            'email_entreprise.required' => __('main.enter_a_valid_company_email_address_please'),
            'email_entreprise.email' => __('main.enter_a_valid_company_email_address_please'),

        ];
    }
}
