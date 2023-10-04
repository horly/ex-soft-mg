<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequestForm extends FormRequest
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
            'emailPassReq' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            //
            'emailPassReq.required' => __('auth.enter_the_email_please'),
            'emailPassReq:email' => __('auth.enter_a_valid_email_please'),
        ];
    }
}
