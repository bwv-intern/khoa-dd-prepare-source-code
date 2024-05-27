<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\File;
use Log;

class CSVHelper
{
    /**
     * Read file csv by $filePath, compare if wrong header, and process csv file with $callback
     *
     * @param string $filePath
     * @param array $header
     * @param callback $callback
     *
     * @return void
     * @throws \RuntimeException
     */
    public static function readCSV($filePath, $header, $callBack)
    {
        if (!File::exists($filePath)) {
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
     *
     * @return bool
     */
    public function exportCSV($filePath, $rows)
    {
        try {
            $file = fopen($filePath, 'w');

            foreach ($rows as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
            return true;
        } catch (\Throwable $th) {
            Log::error($th);
            return false;
        }
    }
}
