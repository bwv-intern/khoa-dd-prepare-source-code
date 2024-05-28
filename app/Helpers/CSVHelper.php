<?php

namespace App\Helpers;

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
            throw new Exception('Wrong header');
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
                foreach ($formats as $field => $format)
                {
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

    public static function betterRowFormatter(array $row): string {
        foreach ($row as $key => $item) {
            $item = str_replace('"', '""', $item);
            $row[$key] = '"' . $item . '"';
        }

        return implode(',', $row);
    }

    public static function getPath() {
        return __DIR__ . '/../' . 'Constant/' . 'Exports/';
    }

    public static function getExportConfigs(string $exportType) {
        global $cache;

        if (! isset($cache)) {
            $cache = [];
        }

        if (!isset($cache[$exportType])) {
            $filePath = static::getPath() . $exportType . '.yml';
            $cache[$exportType] = Yaml::parseFile($filePath);
        }
        return $cache[$exportType];
    }
}
