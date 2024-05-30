<?php

namespace App\Rules;

use App\Libs\{ConfigUtil, ValueUtil};
use Illuminate\Contracts\Validation\{Rule};

/**
 * Check value or array of values is in a value list
 */
class CheckInValueList implements Rule
{
    /**
     * Construct a new instance
     *
     * @param string $valueListKey
     * @return void
     */
    public function __construct(private string $valueListKey) {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value) {
        if (! is_array($value)) {
            $value = [$value];
        }
        $values = $value;

        $ruleFail = false;
        $possibleValues = ValueUtil::getValues($this->valueListKey);
        foreach ($values as $value) {
            if (! in_array($value, $possibleValues)) {
                $ruleFail = true;
                break;
            }
        }

        return empty($value)
            || ! $ruleFail;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        $spaceSeparatedValues = implode(', ', ValueUtil::getValues($this->valueListKey));

        return ConfigUtil::getMessage('E012', [':Field', $spaceSeparatedValues]);
    }
}
