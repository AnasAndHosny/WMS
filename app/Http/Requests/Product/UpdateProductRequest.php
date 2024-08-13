<?php

namespace App\Http\Requests\Product;

use App\Http\Responses\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => ['image', 'nullable', 'mimes:jpeg,png,bmp,jpg,gif,svg', 'max:256'],
            'name_ar' => ['sometimes', 'string', 'unique:products'],
            'name_en' => ['sometimes', 'string', 'unique:products'],
            'description_ar' => ['sometimes', 'string'],
            'description_en' => ['sometimes', 'string'],
            'manufacturer_id' => ['sometimes', 'string', 'exists:manufacturers,id'],
            'price' => ['sometimes', 'regex:/^\d+(\.\d{1,2})?$/', 'min:0'],
            'subcategory_id' => ['sometimes', 'exists:sub_categories,id'],
            'barcode' => ['nullable', 'string', 'numeric'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'price.regex' => __("The price's value is invalid."),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, Response::Validation([], $validator->errors()));
    }
}
