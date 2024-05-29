<?php

namespace App\Http\Requests\User;

use App\Http\Requests\AdminOnlyRequest;

class AddRequest extends AdminOnlyRequest
{
    public function rules() {
        return [
            'email' => getValidationRule('email', mergeRules: ['unique:user']),
            'name' => getValidationRule('name'),
            'password' => getValidationRule('password'),
            'repeat_password' => getValidationRule('repeat_password'),
            'user_flg' => getValidationRule('user_flg'),
            'date_of_birth' => getValidationRule('date_of_birth'),
            'phone' => getValidationRule('phone'),
            'address' => getValidationRule('address'),
        ];
    }
}
