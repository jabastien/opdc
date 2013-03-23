<?php

require_once "DataCollector.php";
require_once "IndexRange.php";

$baseUrl  = 'http://localhost/';
$timeout  = 1;
$interval = 2;
$range    = [];
$options = getopt('h:t:i:f:');

if (isset($options['h'])) {
    $baseUrl = $options['h'];
}
if (isset($options['t'])) {
    $timeout = $options['t'];
}
if (isset($options['i'])) {
    $interval = $options['i'];
}
if (isset($options['f'])) {
    $rangeStr = $options['f'];
    $range    = IndexRange::computeIndexRange($rangeStr);
}

$csvLogger = new DataCollector($baseUrl, $timeout);

while (true) {
    $csv    = $csvLogger->collectData();
    $fields = str_getcsv($csv);

    if (count($range) > 0) {
        $values = IndexRange::arraySliceIndexedValues($fields, $range);
        echo implode(',', $values) . PHP_EOL;
    } else {
        var_dump($fields);
    }
    sleep($interval);
}
