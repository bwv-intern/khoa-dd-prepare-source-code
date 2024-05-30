<?php

namespace App\Extensions\Interfaces;

/**
 * Force implementing import requests to have an import type
 */
interface IImportRequest
{
    /**
     * Specify the import type used to identify and retrieve configs
     *
     * @return string
     */
    public function getImportType(): string;
}
