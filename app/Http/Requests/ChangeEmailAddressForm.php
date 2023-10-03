<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeEmailAddressForm extends FormRequest
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
            'current_email' => 'required|email',
            'new_email' => 'required|email',
            'confirm_new_email' => 'required|email|lte:new_email',
            'password_new_email' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //
            'current_email.required' => __('profile.enter_the_current_email_address_please'),
            'current_email.email' => __('profile.enter_the_current_email_address_please'),

            'new_email.required' => __('profile.enter_the_new_email_address_please'),
            'new_email.email' => __('profile.enter_the_new_email_address_please'),

            'confirm_new_email.required' => __('profile.enter_the_new_email_address_please'),
            'confirm_new_email.email' => __('profile.enter_the_new_email_address_please'),
            'confirm_new_email.lte' => __('profile.email_addresses_must_be_identical'),
            
            'password_new_email' => __('auth.enter_your_password_please'),
        ];
    }
}
