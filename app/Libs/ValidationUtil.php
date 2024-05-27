<?php

namespace App\Libs;

use Symfony\Component\Yaml\Yaml;

class ValidationUtil
{
    public static function getPath() {
        return __DIR__ . '/../' . 'Constant/' . 'Validation/';
    }

    public static function getCustomRuleClassNamespace() {
        return 'App\\Rules\\';
    }

    public static function getValidationRule($validationKey) {
        return static::innerGetValidationRule($validationKey);
    }

    public static function resolveStringRule(string $ruleString) {
        $parts = explode(':', $ruleString);
        if (is_array($parts) && count($parts) == 1) {
            array_push($parts, '');
        } else {
            return $ruleString;
        }
        [$ruleName, $args] = $parts;
        $args = explode(',', $args);
        $fullCustomRuleClassName = static::getCustomRuleClassNamespace() . str($ruleName)->camel();
        if (class_exists($fullCustomRuleClassName)) {
            return app()->make($fullCustomRuleClassName, $args);
        }

        return $ruleString;
    }

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
