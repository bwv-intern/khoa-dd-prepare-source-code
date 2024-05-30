<?php

namespace App\Http\Requests\User;

use App\Http\Requests\AdminOnlyRequest;
use App\Rules\{CheckInValueList, CheckMailRFC};

class SearchRequest extends AdminOnlyRequest
{
    public function rules() {
        return [
            'email' => ['nullable', new CheckMailRFC()],
            'name' => ['nullable'],
            'phone' => ['nullable', 'numeric'],
            'date_of_birth' => ['nullable', 'date_format:d/m/Y'],
            'user_flg' => ['nullable', new CheckInValueList('user.user_flg')],
        ];
    }
}
