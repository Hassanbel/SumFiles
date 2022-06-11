<?php


/* Keeping track of processed files */
$fileTracker = [];
$subFileTracker = [];

/* Passing file name as argument */
if (isset($argc) && count($argv) > 1) {
    $file = $argv[1];
    if (file_exists($file)) {
        echo $file . ' - ' . sumFile($file, $GLOBALS['fileTracker']) . "\n";
        sort($GLOBALS['fileTracker']);

        foreach ($GLOBALS['fileTracker'] as $subFile) {
            if ($subFile) {
                $GLOBALS['subFileTracker'][] = $subFile;
                echo $subFile . ' - ' . sumFile($subFile, $GLOBALS['subFileTracker']) . "\n";
            }
        }

        // Reset files trackers
        $GLOBALS['fileTracker'] = $GLOBALS['subFileTracker'] = [];
    }
} else {
    echo "# Please set files names as argument \n";
}

/* Calculation of file numbers recursive with sub files */
function sumFile(string $fileName, &$fileTracker, int $sumFileResult = null): ?int
{
    $data = readMyFile($fileName);

    foreach ($data as $item) {
        if (is_numeric($item)) {
            $sumFileResult += $item;
        } else if (!in_array($item, $fileTracker, true)) {
            $sumFileResult = sumFile($item, $fileTracker, $sumFileResult);
            $fileTracker[] = $item;
        }
    }

    return $sumFileResult;
}

/* Read file and return content as data array */
function readMyFile(string $fileName): array
{
    $data = [];

    if (!empty($fileName) && $file_handle = fopen($fileName, "r")) {
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            $line = str_replace(["\r", "\n", ""], "", $line);
            if (!empty($line)) {
                $data[] = $line;
            }
        }
        fclose($file_handle);
    }

    return $data;
}