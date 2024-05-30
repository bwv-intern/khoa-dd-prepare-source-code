<?php

namespace App\Extensions\Interfaces;

interface IExportRequest
{
    /**
     * Specify the export type used to identify and retrieve configs
     *
     * @return string
     */
    public function getExportType(): string;
}
