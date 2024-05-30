<?php

namespace App\Http\Requests\User;

use App\Extensions\Interfaces\IImportRequest;
use App\Http\Requests\AdminOnlyRequest;
use App\Rules\CheckCSVMime;

class ImportRequest extends AdminOnlyRequest implements IImportRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'import_file' => ['required', 'file', 'extensions:csv', new CheckCSVMime(), 'max:5120'],
        ];
    }

    public function getImportType(): string
    {
        return 'admin_user_import';
    }
}
