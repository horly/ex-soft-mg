<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class changePasswordForm extends FormRequest
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
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ];
    }

    public function messages()
    {
        return [
            //
            'new_password.required' => __('auth.create_the_password_please'),
            'new_password.min' => __('auth.error_password_register_message'),

            'confirm_password.required' => __('auth.password_confirmation_register_message'),
            'confirm_password.same' => __('auth.password_confirmation_register_message'),
        ];
    }
}
