<?php

namespace App\Helpers;

use App\Exceptions\WrongHeaderException;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\{File, Log};
use RuntimeException;
use Symfony\Component\Yaml\Yaml;
use Throwable;

class CSVHelper
{
    /**
     * Read file csv by $filePath, compare if wrong header, and process csv file with $callback
     *
     * @param string $filePath
     * @param array $header
     * @param callback $callback
     * @param mixed $callBack
     *
     * @throws RuntimeException
     * @return void
     */
    public static function readCSV($filePath, $header, $callBack) {
        if (! File::exists($filePath)) {
            throw new Exception('File not found');
        }

        $fileHandle = fopen($filePath, 'r');

        if ($fileHandle === false) {
            throw new Exception('Failed to open file');
        }

        $firstRow = fgetcsv($fileHandle);

        if (array_diff_assoc($header, $firstRow)) {
            throw new WrongHeaderException('Wrong header');
        }

        $lineNumber = 0;
        while (($row = fgetcsv($fileHandle)) !== false) {
            $callBack($lineNumber++, $row);
        }

        fclose($fileHandle);
    }

    /**
     * Export csv file to $filePath by $header and $data
     *
     * @param string $filePath
     * @param array $header
     * @param array $data
     * @param mixed $rows
     * @param string $exportType
     *
     * @return bool
     */
    public static function exportCSV($filePath, string $exportType, $rows) {
        try {
            $file = fopen($filePath, 'w');

            $exportConfigs = static::getExportConfigs($exportType);

            $columns = $exportConfigs['columns'];
            $formats = $exportConfigs['dt_formats'];

            $header = array_values($columns);
            $header = static::betterRowFormatter($header) . "\n";

            // write header
            fputs($file, $header);

            // extract only relevant columns, format and write
            foreach ($rows as $row) {
                $newRow = [];
                foreach ($columns as $fieldName => $exportHeaderName) {
                    $newRow[$fieldName] = $row[$fieldName];
                }
                foreach ($formats as $field => $format) {
                    $newRow[$field] = Carbon::parse($newRow[$field])->format($format);
                }
                fputs($file, static::betterRowFormatter($newRow) . "\n");
            }

            fclose($file);

            return true;
        } catch (Throwable $th) {
            Log::error($th);

            return false;
        }
    }

    /**
     * Format array into csv strings, wrapping everything in dquote,
     * escaping present dquotes
     * @param array $row
     */
    public static function betterRowFormatter(array $row): string {
        foreach ($row as $key => $item) {
            $item = str_replace('"', '""', $item);
            $row[$key] = '"' . $item . '"';
        }

        return implode(',', $row);
    }

    /**
     * Get export configuration including a column to header mapping
     * and format strings for date time columns for an export type
     * @param string $exportType
     */
    public static function getExportConfigs(string $exportType) {
        global $cache;

        if (! isset($cache)) {
            $cache = [];
        }

        if (! isset($cache[$exportType])) {
            $filePath = static::getExportConfigPath() . $exportType . '.yml';
            $cache[$exportType] = Yaml::parseFile($filePath);
        }

        return $cache[$exportType];
    }

    /**
     * Get a complete validation rules array for an import type
     * @param string $importType
     */
    public static function getImportValidationRules(string $importType) {
        global $importRuleCache;

        if (! isset($importRuleCache)) {
            $importRuleCache = [];
        }

        if (! isset($importRuleCache[$importType])) {
            $intermediary = static::getImportConfigsRaw($importType);
            foreach ($intermediary as $headerColumn => $validationKey) {
                $intermediary[$headerColumn] = getValidationRule($validationKey, false);
            }
            $importRuleCache[$importType] = $intermediary;
        }

        return $importRuleCache[$importType];
    }

    /**
     * Get the mappings between header columns and real db columns for an import type
     * @param string $importType
     */
    public static function getImportMappings(string $importType) {
        global $importMapCache;

        if (! isset($importMapCache)) {
            $importMapCache = [];
        }

        if (! isset($importMapCache[$importType])) {
            $intermediary = static::getImportConfigsRaw($importType);
            foreach ($intermediary as $headerColumn => $validationKey) {
                $keyParts = explode('.', $validationKey);
                $intermediary[$headerColumn] = end($keyParts);
            }
            $importMapCache[$importType] = $intermediary;
        }

        return $importMapCache[$importType];
    }

    /**
     * Store the export config path
     */
    private static function getExportConfigPath() {
        return __DIR__ . '/../' . 'Constant/' . 'Exports/';
    }

    /**
     * Store the import config path
     */
    private static function getImportConfigPath() {
        return __DIR__ . '/../' . 'Constant/' . 'Imports/';
    }

    /**
     * Get the basic import configurations for an import type,
     * containing header column and a corresponding validation key
     * @param string $importType
     */
    private static function getImportConfigsRaw(string $importType) {
        global $importCache;

        if (! isset($importCache)) {
            $importCache = [];
        }

        if (! isset($importCache[$importType])) {
            $filePath = static::getImportConfigPath() . $importType . '.yml';
            $importCache[$importType] = Yaml::parseFile($filePath);
        }

        return $importCache[$importType];
    }
}
