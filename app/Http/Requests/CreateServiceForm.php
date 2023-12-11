<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateServiceForm extends FormRequest
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
            'description_serv' => 'required',
            'cat_serv' => 'required',
            'unit_price_serv' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            //
            'description_serv.required' => __('service.enter_the_service_description_please'),
            'cat_serv.required' => __('service.select_a_category_please'),
            'unit_price_serv.required' => __('service.enter_the_service_unit_price_please'),
            'unit_price_serv.numeric' => __('service.enter_a_valid_service_unit_price_please'),
        ];
    }
}
