<?php

namespace App\Http\Requests\Employee;

use App\Models\Employee;
use App\Rules\Employable;
use App\Http\Responses\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateEmployeeProfileRequest extends FormRequest
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
            'username' => ['sometimes', 'string', 'unique:users,name'],
            'email' => ['sometimes', 'unique:users,email', 'email'],
            'full_name' => ['sometimes', 'string'],
            'gender'=> ['sometimes', 'in:male,female'],
            'birthday' => ['sometimes', 'date_format:Y-m-d'],
            'phone_number' => ['nullable', 'numeric', 'digits:10', 'starts_with:09'],
            'state_id' => ['sometimes', 'exists:states,id'],
            'address' => ['nullable', 'string'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'role.required' => __('The role field is required when changing the employable type.'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, Response::Validation([], $validator->errors()));
    }
}
