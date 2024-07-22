<?php

namespace App\Http\Requests\Order;

use App\Models\Warehouse;
use App\Rules\ValidQuantity;
use Illuminate\Validation\Rule;
use App\Http\Responses\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreWarehouseOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        $user = Auth::user();
        if ($user->roles->first()->type == 0) {
            $this->replace(['warehouse_id' => Auth::user()->employee->employable->id]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $warehouseId = $this->warehouse_id;
        $user = Auth::user();
        $employee = $user->employee;
        if (!$user->roles->first()->type) {
            $warehouseId = $employee->employable->warehouse_id;
        }

        $rules = [
            'warehouse_id' => [
                Rule::excludeIf($employee->employable_type == 'DistributionCenter'),
                Rule::requiredIf($employee->employable_type == 'Warehouse'),
                Rule::exists('warehouses', 'id')
                    ->whereNot('id', $employee->employable_id)
            ],
            'products' => ['array', 'present'],
        ];

        foreach ($this->input('products', []) as $key => $product) {
            $rules["products.$key.id"] = [
                'required',
                Rule::exists('stored_products', 'id')
                    ->where('storable_type', Warehouse::class)
                    ->where('storable_id', $warehouseId)
                    ->whereNot('valid_quantity', 0)
                    ->where('active', true)
            ];
            $rules["products.$key.quantity"] = [
                'required',
                'integer',
                'min:1',
                new ValidQuantity($warehouseId, $product['id']),
            ];
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, Response::Validation([], $validator->errors()));
    }
}
