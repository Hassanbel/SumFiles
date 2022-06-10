<?php

/*
 * Keeping track of processed files
 * To avoid infinite loop when a file contains same file name as previous ones
 */
$fileTracker = [];

/*
 * Passing file names as argument
 */
if (isset($argc) && count($argv) > 1) {
    // reading first argument for file name to calculate its sum
    foreach ($argv as $key => $file) {

        if (($key !== 0) && file_exists($file)) {
            echo $file . ' - ' . sumFile($file) . "\n";

            // Reset fileTracker for each file Sum calculation
            $GLOBALS['fileTracker'] = [];
        }
    }
} else {
    echo "# Please set files names as argument \n";
}

/*
 * Calculation of file numbers recursive with sub files, plus verify if file exist in fileTracker var
 */
function sumFile(string $fileName, int $sumResult = null): ?int
{
    $data = readMyFile($fileName);

    foreach ($data as $item) {
        $item = str_replace(["\r", "\n"], '', $item);
        if (is_numeric($item)) {
            $sumResult += $item;
        } else if (count($GLOBALS['fileTracker']) === 0 && !in_array($item, $GLOBALS['fileTracker'], true)) {
            $sumResult = sumFile($item, $sumResult);
            $GLOBALS['fileTracker'][] = $item;
        }
    }

    return $sumResult;
}

/*
 * Read file and return content as data array
 */
function readMyFile(string $fileName): array
{
    $data = [];

    if (!empty($fileName) && $file_handle = fopen($fileName, "r")) {
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            $data[] = $line;
        }
        fclose($file_handle);
    }

    return $data;
}