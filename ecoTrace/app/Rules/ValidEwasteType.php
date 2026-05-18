<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEwasteType implements ValidationRule
{
    /**
     * Permitted e-waste categories / types.
     */
    protected array $allowedTypes = [
        'smartphone',
        'laptop',
        'tablet',
        'battery',
        'charger',
        'cable',
        'screen',
        'monitor',
        'keyboard',
        'mouse',
        'appliance',
        'other'
    ];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Value can be a string or an array of strings
        $values = is_array($value) ? $value : [$value];

        foreach ($values as $val) {
            if (!in_array(strtolower($val), $this->allowedTypes)) {
                $fail("The :attribute contains an invalid e-waste type: '{$val}'. Allowed types: " . implode(', ', $this->allowedTypes));
                return;
            }
        }
    }
}
