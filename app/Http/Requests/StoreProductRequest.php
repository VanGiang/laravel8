<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CustomDes;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'code' => 'required|unique:products',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            // 'description' => [new CustomDes],
        ];

        if ($this->method() == 'PUT') {
            $rules['code'] = [
                'required',
                Rule::unique('products')->ignore($this->product),
            ];
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'price.numeric' => 'The price field is numeric.',
        ];
    }
}
