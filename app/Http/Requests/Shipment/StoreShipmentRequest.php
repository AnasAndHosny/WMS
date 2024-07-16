<?php

namespace App\Http\Requests\Shipment;

use App\Http\Responses\Response;
use App\Models\OrderStatus;
use App\Models\Warehouse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StoreShipmentRequest extends FormRequest
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
        $employable = Auth::user()->employee->employable;
        $employableType = get_class($employable);
        return [
            'order_id' => [
                'required',
                Rule::exists('orders', 'id')
                    ->where('orderable_from_type', $employableType)
                    ->where('orderable_from_id', $employable->id)
                    ->where('status_id', OrderStatus::findByName('Under Preparing')->id)
            ],
            'shipping_company_id' => ['required', 'exists:Shipping_companies,id'],
            'driver_name' => ['nullable', 'string'],
            'cost' => ['required', 'regex:/^\d+(\.\d{1,2})?$/', 'min:0']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, Response::Validation([], $validator->errors()));
    }
}
