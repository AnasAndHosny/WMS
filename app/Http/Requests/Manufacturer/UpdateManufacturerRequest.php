<?php

namespace App\Http\Requests\Manufacturer;

use App\Http\Responses\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateManufacturerRequest extends FormRequest
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
            'name_ar' => ['sometimes', 'string', 'unique:manufacturers'],
            'name_en' => ['sometimes', 'string', 'unique:manufacturers'],
            'state_id' => ['sometimes', 'exists:states,id'],
            'street_address_ar' => ['sometimes', 'string'],
            'street_address_en' => ['sometimes', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, Response::Validation([], $validator->errors()));
    }
}
