<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyForm extends FormRequest
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
            'currency_name_dev' => 'required',
            'rate_currency_dev' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            //
            'currency_name_dev.required' => __('dashboard.select_your_currency_please'),
            'rate_currency_dev.required' => __('dashboard.enter_the_valid_rate_please'),
            'rate_currency_dev.numeric' => __('dashboard.enter_the_valid_rate_please'),
        ];
    }
}
