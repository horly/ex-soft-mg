<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubCategoryForm extends FormRequest
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
            'name_subcat' => 'required',
            'cat_art_sub' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //
            'name_subcat.required' => __('article.enter_the_category_name_please'),
            'cat_art_sub.required' => __('article.select_a_category_please'),
        ];
    }
}
