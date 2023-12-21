<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateArticleForm extends FormRequest
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
            'description_art' => 'required',
            'subcat_art' => 'required',
            'purchase_price_art' => 'required|numeric',
            'sale_prise_art' => 'required|numeric',
            'number_in_stock_art' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            //
            'description_art.required' => __('article.enter_the_article_description_please'),
            'subcat_art.required' => __('article.select_a_subcategory_please'),

            'purchase_price_art.required' => __('invoice.enter_the_article_purchase_prise_please'),
            'purchase_price_art.numeric' => __('invoice.enter_a_valid_price_please'),

            'sale_prise_art.required' => __('invoice.enter_the_article_sale_prise_please'),
            'sale_prise_art.numeric' => __('invoice.enter_a_valid_price_please'),

            'number_in_stock_art.required' => __('article.enter_the_number_in_stock_please'),
            'number_in_stock_art.numeric' => __('article.enter_a_valid_number_please'),
        ];
    }
}
