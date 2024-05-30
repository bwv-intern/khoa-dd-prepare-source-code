<?php

namespace App\Rules;

use App\Libs\ConfigUtil;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class CheckCSVMime implements Rule
{
    /**
     * Check if file has a submime of one of the valid csv submimes
     *
     * @param mixed $attribute
     * @param mixed $value
     */
    public function passes($attribute, $value) {
        if (! $value instanceof UploadedFile) {
            return false;
        }

        $mime = $value->getMimeType();
        $submime = explode('/', $mime)[1];

        return in_array($submime, static::getValidCSVSubMimes());
    }

    /**
     * Get the validation error message
     *
     * @return string
     */
    public function message() {
        return ConfigUtil::getMessage('E007', ['csv']);
    }

    /**
     * Contain all the valid submimes of a typical csv file
     */
    public static function getValidCSVSubMimes() {
        return ['csv', 'plain'];
    }
}
