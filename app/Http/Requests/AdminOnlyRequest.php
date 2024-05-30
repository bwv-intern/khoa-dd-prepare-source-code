<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Base request class for most admin requests
 */
abstract class AdminOnlyRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return $this->user()['user_flg'] == getConstToValue('user.user_flg.ADMIN');
    }
}
