<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodForm extends FormRequest
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
            'designation_pay_meth' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //
            'designation_pay_meth.required' => __('payment_methods.enter_the_payment_method_designation_please'),
        ];
    }
}
