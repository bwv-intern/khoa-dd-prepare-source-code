<?php

namespace App\Libs;

use Illuminate\Validation\ValidationRuleParser;
use Symfony\Component\Yaml\Yaml;

/**
 * Validation utilities class used to retrieve preconfigured
 * validation rules on common fields
 */
class ValidationUtil
{
    /**
     * Store the validation config path
     */
    public static function getPath() {
        return __DIR__ . '/../' . 'Constant/' . 'Validation/';
    }

    /**
     * Get the known common namepsace of custom rules
     */
    public static function getCustomRuleClassNamespace() {
        return 'App\\Rules\\';
    }

    /**
     * Get validation rules defined in app\Constant\Validation\FILE.yml
     *
     * @param mixed $validationKey, should be in the form of "FILE.FIELD", FILE is assumed to be common if supplied with only "FIELD"
     */
    public static function getValidationRule($validationKey) {
        $rules = static::innerGetValidationRule($validationKey);
        foreach ($rules as $index => $rule) {
            $rules[$index] = static::resolveRuleString($rule);
        }

        return $rules;
    }

    /**
     * Parse a rule string, i.e. max:50 into either a concrete rule object
     * if it is a custom rule, or preserve it otherwise
     * @param string $ruleString
     */
    public static function resolveRuleString(string $ruleString) {
        $parts = ValidationRuleParser::parse($ruleString);
        [$ruleName, $args] = $parts;
        $fullCustomRuleClassName = static::getCustomRuleClassNamespace() . $ruleName;
        if (class_exists($fullCustomRuleClassName)) {
            return new $fullCustomRuleClassName(...$args);
        }

        return $ruleString;
    }

    /**
     * Parse yaml and handle internal caching
     * @param mixed $validationKey
     */
    private static function innerGetValidationRule($validationKey) {
        global $validationFileCache;
        global $validationKeyCache;

        if (! isset($validationFileCache)) {
            $validationFileCache = [];
        }

        if (! isset($validationKeyCache)) {
            $validationKeyCache = [];
        }

        if (isset($validationKeyCache[$validationKey])) {
            return $validationKeyCache[$validationKey];
        }

        $keyParts = explode('.', $validationKey);

        if (is_array($keyParts) && count($keyParts) == 1) {
            array_push($keyParts, 'common');
            $keyParts = array_reverse($keyParts);
        }

        [$fileName, $field] = $keyParts;

        $fullPath = static::getPath() . $fileName . '.yml';

        if (! isset($validationFileCache[$fullPath])) {
            $content = Yaml::parse(file_get_contents($fullPath));
            $validationFileCache[$fullPath] = $content;
        } else {
            $content = $validationFileCache[$fullPath];
        }

        $validationKeyCache[$validationKey] = $content[$field];

        return $validationKeyCache[$validationKey];
    }
}
