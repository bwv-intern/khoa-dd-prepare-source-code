<?php

namespace App\Http\Requests\User;

use App\Http\Requests\AdminOnlyRequest;
use Illuminate\Validation\Rule;

class EditRequest extends AdminOnlyRequest
{
    public function rules() {
        $userId = $this->route('id');
        return [
            'email' => getValidationRule('email', mergeRules: [Rule::unique('user', 'email')->ignore($userId)]),
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
