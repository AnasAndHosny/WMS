<?php

namespace App\Http\Requests\Employee;

use App\Http\Responses\Response;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class BanEmployeeRequest extends FormRequest
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
            'expired_at' => ['date', 'after:' . Carbon::today()],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, Response::Validation([], $validator->errors()));
    }
}
