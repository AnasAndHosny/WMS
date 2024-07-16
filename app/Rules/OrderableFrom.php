<?php

namespace App\Rules;

use App\Models\Order;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OrderableFrom implements ValidationRule
{
    private $parameter;

    /**
     * Create a new rule instance.
     *
     * @param $parameter
     */
    public function __construct($parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $orderableFromTypes = Order::getAvailableOrderableFrom();

        // Check if the provided employable type exists in the available orderable from types
        if (!isset($orderableFromTypes[$this->parameter])) {
            $fail('validation.exists')->translate();
            return;
        }

        // Get the model class corresponding to the employable type
        $modelClass = $orderableFromTypes[$this->parameter];

        // Check if the provided employable ID exists in the database
        $modelExist = $modelClass::find($value) !== null;

        // If the employable ID does not exist, trigger validation failure with a custom message
        if (!$modelExist) {
            $fail('validation.exists')->translate();
        }
    }
}
