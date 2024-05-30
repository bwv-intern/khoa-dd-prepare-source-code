<?php

namespace App\Http\Requests\User;

use App\Extensions\Interfaces\IExportRequest;
use App\Http\Requests\AdminOnlyRequest;
use App\Libs\ValueUtil;
use App\Rules\CheckInValueList;
use App\Rules\CheckMailRFC;

class ExportRequest extends SearchRequest implements IExportRequest {
    public function getExportType(): string
    {
        return 'admin_user_search_export';
    }
}