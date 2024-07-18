<?php

namespace App\Http\Requests\Sale;

use App\Http\Responses\Response;
use App\Models\StoredProduct;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreSaleRequest extends FormRequest
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
        $user = Auth::user();
        $employable = $user->employee->employable;

        $rules = [
            'buyer_name' => ['nullable', 'string'],
            'products' => ['array', 'present'],
        ];

        foreach ($this->input('products', []) as $key => $product) {
            $rules["products.$key.id"] = [
                'required',
                Rule::exists('stored_products', 'id')
                    ->where('storable_type', get_class($employable))
                    ->where('storable_id', $employable->id)
            ];

            $storedProduct = StoredProduct::where('storable_type', get_class($employable))
                ->where('storable_id', $employable->id)
                ->find($product['id']);
            if ($storedProduct) {
                $maxQuantity = $storedProduct->max < $storedProduct->valid_quantity ? $storedProduct->max : $storedProduct->valid_quantity;
                $rules["products.$key.quantity"] = [
                    'required',
                    'integer',
                    'min:1',
                    'max:' . $maxQuantity,
                ];
            }
        }

        return $rules;;
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw a validationException with the translated error messages
        throw new ValidationException($validator, Response::Validation([], $validator->errors()));
    }
}
