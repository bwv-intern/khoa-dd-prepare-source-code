<?php

namespace App\Http\Requests\User;

use App\Extensions\Interfaces\IExportRequest;

/**
 * Validate an incoming export csv request
 */
class ExportRequest extends SearchRequest implements IExportRequest
{
    public function getExportType(): string {
        return 'admin_user_search_export';
    }
}
