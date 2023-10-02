<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileInfoForm extends FormRequest
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
            'name_profile' => 'required|regex:/^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/',
            'function_profile' => 'required',
            'country_profile' => 'required',
            'phone_number_profile' => 'required|numeric',
            'registration_number_profile' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name_profile.required' => __('auth.enter_your_name_please'),
            'name_profile.regex' => __('auth.enter_your_name_please'),

            'function_profile.required' => __('auth.error_function_register_message'),

            'function_profile.required' => __('auth.error_country_register_message'),

            'phone_number_profile.required' => __('auth.error_phone_number_register_message'),
            'phone_number_profile.numeric' => __('auth.error_phone_number_register_message'),

            'registration_number_profile.required' => __('auth.error_matriculer_register_message'),
        ];
    }
}
