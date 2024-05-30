<?php

namespace App\Http\Requests\User;

use App\Http\Requests\AdminOnlyRequest;

/**
 * Validate incoming ADMIN_USER_ADD submit request
 */
class AddRequest extends AdminOnlyRequest
{
    public function rules() {
        return [
            'email' => getValidationRule('user.email', mergeRules: ['unique:user']),
            'name' => getValidationRule('user.name'),
            'password' => getValidationRule('user.password'),
            'repeat_password' => getValidationRule('user.repeat_password'),
            'user_flg' => getValidationRule('user.user_flg'),
            'date_of_birth' => getValidationRule('user.date_of_birth'),
            'phone' => getValidationRule('user.phone'),
            'address' => getValidationRule('user.address'),
        ];
    }
}
