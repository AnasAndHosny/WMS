<?php

namespace App\Rules;

use Closure;
use App\Models\Warehouse;
use App\Models\StoredProduct;
use Illuminate\Support\Facades\Lang;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidQuantity implements ValidationRule
{
    private $warehouseId;
    private $productId;

    public function __construct($warehouseId, $productId)
    {
        $this->warehouseId = $warehouseId;
        $this->productId = $productId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $storedProduct = StoredProduct::query()
            ->where('id', $this->productId)
            ->where('storable_type', Warehouse::class)
            ->where('storable_id', $this->warehouseId)
            ->whereNot('valid_quantity', 0)
            ->where('active', true)
            ->first();

        if (!$storedProduct) {
            return;
        }

        $maxAllowed = min($storedProduct->valid_quantity, $storedProduct->max);
        if ($value > $maxAllowed) {
            $fail('The ordered quantity exceeds the available stock.')->translate();
        }
    }
}
