<?php

namespace App\Extensions\Interfaces;

interface IImportRequest
{
    /**
     * Specify the import type used to identify and retrieve configs
     * 
     * @return string
     */
    public function getImportType(): string;
}
