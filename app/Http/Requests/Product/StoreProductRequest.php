<?php

namespace App\Http\Requests\Product;

use App\Http\Responses\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreProductRequest extends FormRequest
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
            'name_ar' => ['required', 'string', 'unique:products'],
            'name_en' => ['required', 'string', 'unique:products'],
            'description_ar' => ['nullable','string'],
            'description_en' => ['nullable','string'],
            'manufacturer_id' => ['required','exists:manufacturers,id'],
            'price' => ['required', 'regex:/^\d+(\.\d{1,2})?$/', 'min:0'],
            'subcategory_id' => ['required', 'exists:sub_categories,id']
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
