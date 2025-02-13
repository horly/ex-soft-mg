<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddUserForm extends FormRequest
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
            'full_name' => 'required|regex:/^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/',
            //'lastName' => 'required|regex:/^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/',
            'emailUsr' => 'required|email',
            //'passwordUsr' => 'required|min:8',
            //'passwordConfirm' => 'required|same:passwordUsr',
            'countryUsr' => 'required',
            'role' => 'required',
            //'function' => 'required',
            'phoneNumber' => 'required|numeric',
            'matricule' => 'required',

            'subscript_user' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //
            'full_name.required' => __('auth.please_enter_your_fullname'),
            //'lastName.required' => __('auth.enter_the_lastname_please'),

            'full_name.regex' => __('auth.enter_a_valid_firstname_please'),
            //'lastName.regex' => __('auth.enter_a_valid_lastname_please'),

            'emailUsr.required' => __('auth.enter_the_email_please'),
            'emailUsr:email' => __('auth.enter_a_valid_email_please'),

            //'passwordUsr.required' => __('auth.create_the_password_please'),
            //'passwordUsr.min' => __('auth.error_password_register_message'),

            //'passwordConfirm.required' => __('auth.password_confirmation_register_message'),
            //'passwordConfirm.same' => __('auth.password_confirmation_register_message'),

            'countryUsr.required' => __('auth.select_the_country_please'),

            'role.required' => __('auth.error_role_register_message'),

            //'function.required' => __('auth.error_function_register_message'),
            //'function.required' => __('main.enter_your_grade_please'),

            'phoneNumber.required' => __('auth.error_phone_number_register_message'),
            'phoneNumber.numeric' => __('auth.error_phone_number_register_message'),

            'matricule.required' => __('auth.error_matriculer_register_message'),

            'subscript_user.required' => __('super_admin.please_select_a_subscription'),
        ];
    }
}
