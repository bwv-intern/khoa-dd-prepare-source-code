<?php

namespace App\Extensions\Validation;

use App\Libs\ConfigUtil;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\{Number, Str};
use Illuminate\Validation\Validator;

class SpecValidator extends Validator
{
    /**
     * Create a new Validator instance.
     *
     * @param Translator $translator
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $attributes
     * @return void
     */
    public function __construct(
        Translator $translator,
        array $data,
        array $rules,
        array $messages = [],
        array $attributes = [],
    ) {
        parent::__construct($translator, $data, $rules, $messages, $attributes);
        $this->setCustomMessages($this->specMessages());
        $this->addReplacers($this->specReplacers());
    }

    /**
     * Hold custom validation messages
     */
    protected function specMessages() {
        return [
            '*.required' => ConfigUtil::getMessage('E001', [':Field']),
            '*.App\\Rules\\CheckMailRFC' => ConfigUtil::getMessage('E004'),
            '*_file.max' => ConfigUtil::getMessage('E006', [':max']),
            '*.max' => ConfigUtil::getMessage('E002', [':Field', ':max', ':size']),
            '*.max_digits' => ConfigUtil::getMessage('E002', [':Field', ':max', ':size']),
            '*.min' => ConfigUtil::getMessage('E003', [':Field', ':min', ':size']),
            '*.numeric' => ConfigUtil::getMessage('E012', [':Field', 'number']),
            '*.date_format' => ConfigUtil::getMessage('E012', [':Field', 'date']),
            'repeat_password.same' => ConfigUtil::getMessage('E011'),
            '*.extensions' => ConfigUtil::getMessage('E007', [':values']),
            'email.unique' => ConfigUtil::getMessage('E009', [':Field']),
        ];
    }

    /**
     * Hold custom replacers
     */
    protected function specReplacers() {
        return [
            'max' => function ($message, $attribute, $rule, $parameters, Validator $validator) {
                return str_replace([':max', ':size'], [
                    ($validator->getData()[$attribute] instanceof UploadedFile)? str_replace(' ', '', Number::fileSize(intval($parameters[0]) * 1024)) : $parameters[0],
                    getSizeForValidation($validator->getData()[$attribute])], $message);
            },
            'max_digits' => function ($message, $attribute, $rule, $parameters, Validator $validator) {
                return str_replace([':max', ':size'], [
                    $parameters[0],
                    getSizeForValidation($validator->getData()[$attribute])], $message);
            },
            'min' => function ($message, $attribute, $rule, $parameters, Validator $validator) {
                return str_replace([':min', ':size'], [
                    ($validator->getData()[$attribute] instanceof UploadedFile)? str_replace(' ', '', Number::fileSize(intval($parameters[0]) * 1024)) : $parameters[0],
                    getSizeForValidation($validator->getData()[$attribute])], $message);
            },
        ];
    }

    /**
     * Replace the :attribute placeholder in the given message.
     * This overrides whatever the trait method is and add a slightly better ucfirst/sentence case formatting
     *
     * @param string $message
     * @param string $value
     * @return string
     */
    protected function replaceAttributePlaceholder($message, $value) {
        $nameLabelMappings = ConfigUtil::get('name_label_mappings');
        $value2 = Str::snake($value);
        if (array_key_exists($value2, $nameLabelMappings)) {
            $value2 = $nameLabelMappings[$value2];
        }

        return str_replace(
            [':attribute', ':ATTRIBUTE', ':Attribute', ':Field'],
            [$value, Str::upper($value), Str::ucfirst($value), Str::sentence($value2)],
            $message,
        );
    }
}
