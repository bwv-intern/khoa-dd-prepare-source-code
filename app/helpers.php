<?php

use App\Libs\{ConfigUtil, DateUtil, ValidationUtil, ValueUtil};
use Illuminate\Http\UploadedFile;

if (! function_exists('getConstToValue')) {
    /**
     * Get value from constant
     *
     * @param string $key
     * @return int|string|null
     */
    function getConstToValue($key) {
        return ValueUtil::constToValue($key);
    }
}

if (! function_exists('getConstToText')) {
    /**
     * Get text from const (in Yml config file)
     *
     * @param $key
     * @return int|string|null
     */
    function getConstToText($key) {
        return ValueUtil::constToText($key);
    }
}

if (! function_exists('getList')) {
    /**
     * Get value for select/checkbox/radio option from key
     *
     * @param string $key
     * @return array|string|null
     */
    function getList($key) {
        return ValueUtil::getList($key);
    }
}

if (! function_exists('getMessage')) {
    /**
     * Get message from key
     *
     * @param string $messId
     * @param array $options
     * @param mixed $paramArray
     * @return mixed|string|null
     */
    function getMessage($messId, $paramArray = []) {
        return ConfigUtil::getMessage($messId, $paramArray);
    }
}

if (! function_exists('getValueToText')) {
    /**
     * Convert from value into text in view
     *
     * @param string|int $value property value Ex: 1
     * @param string $listKey list defined in yml Ex: web.type
     * @return string|null text if exists else blank
     */
    function getValueToText($value, $listKey) {
        return ValueUtil::valueToText($value, $listKey);
    }
}

if (! function_exists('formatDate')) {
    /**
     * Format date
     * @param string|object $date
     * @param string $format
     * @return string;
     */
    function formatDate($date, $format = 'Y/m/d') {
        return DateUtil::formatDate($date, $format);
    }
}

if (! function_exists('getValidationRule')) {
    /**
     * Shorthand function to get validation rule
     * @param mixed $validationKey
     */
    function getValidationRule($validationKey, $bail = true, $mergeRules = []) {
        return array_merge($bail? ['bail'] : [], ValidationUtil::getValidationRule($validationKey), $mergeRules);
    }
}

if (! function_exists('toSentenceCase')) {
    /**
     * Converts str sentence case, works with both snake or kebab case
     *
     * @param string $str
     */
    function toSentenceCase(string $str) {
        return str($str)->ucfirst()->replace(['-', '_'], ' ');
    }
}

if (! function_exists('getSizeForValidation')) {
    /**
     * Get "size" of an input field depending on its type
     * @param mixed $input
     */
    function getSizeForValidation($input) {
        if ($input instanceof UploadedFile) {
            return $input->getSize();
        }
        else if (is_string($input)) {
            return mb_strlen($input);
        }
        // IMPROVE: add support for other compatible types
    }
}
