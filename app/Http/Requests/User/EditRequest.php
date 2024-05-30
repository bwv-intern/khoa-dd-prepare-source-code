<?php

namespace App\Http\Requests\User;

use App\Http\Requests\AdminOnlyRequest;
use Illuminate\Validation\Rule;

/**
 * Validate incoming ADMIN_USER_EDIT submit request
 */
class EditRequest extends AdminOnlyRequest
{
    public function rules() {
        $userId = $this->route('id');

        return [
            'email' => getValidationRule('user.email', mergeRules: [Rule::unique('user', 'email')->ignore($userId)]),
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
