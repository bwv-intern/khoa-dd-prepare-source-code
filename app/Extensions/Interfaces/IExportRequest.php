<?php

namespace App\Extensions\Interfaces;

/**
 * Force implementing export requests to have an export type
 */
interface IExportRequest
{
    /**
     * Specify the export type used to identify and retrieve configs
     *
     * @return string
     */
    public function getExportType(): string;
}
