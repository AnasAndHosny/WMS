<?php

namespace App\Http\Requests\DistributionCenter;

use App\Http\Responses\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateDistributionCenterRequest extends FormRequest
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
            'name_ar' => ['string', 'unique:distribution_centers'],
            'name_en' => ['string', 'unique:distribution_centers'],
            'state_id' => ['exists:states,id'],
            'street_address_ar' => ['string'],
            'street_address_en' => ['string'],
            'warehouse_id' => ['exists:warehouses,id']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, Response::Validation([], $validator->errors()));
    }
}
