<?php

namespace App\Http\Requests\Employee;

use App\Models\Employee;
use App\Rules\Employable;
use App\Http\Responses\Response;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreEmployeeRequest extends FormRequest
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
        // Get the available employable types from the Employee model
        $availableEmployableTypes = array_keys(Employee::getAvailableEmployables());
        $roleType = Role::ofType($this->employable_type);

        return [
            'image' => ['image', 'nullable', 'mimes:jpeg,png,bmp,jpg,gif,svg', 'max:256'],
            'username' => ['required', 'string'],
            'email' => ['required', 'unique:users,email', 'email'],
            'password' => ['required', 'min:8'],
            'full_name' => ['required', 'string'],
            'gender' => ['required', 'in:male,female'],
            'birthday' => ['required', 'date_format:Y-m-d'],
            'phone_number' => ['nullable', 'numeric', 'digits:10', 'starts_with:09'],
            'state_id' => ['required', 'exists:states,id'],
            'address' => ['nullable', 'string'],
            'ssn' => ['required', 'numeric', 'digits:11'],
            'role_id' => ['required', Rule::exists('roles', 'id')->where('type', $roleType)],
            'employable_type' => ['required', 'in:' . implode(',', $availableEmployableTypes)],
            'employable_id' => ['required', new Employable($this->employable_type)]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, Response::Validation([], $validator->errors()));
    }
}
