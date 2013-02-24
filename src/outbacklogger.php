<?php

/**
 * simple data logger for Outback Power device
 */

require_once "OutbackPowerDataCollector.php";
require_once "OutbackPowerDataCollectorException.php";
require_once "DataLogger.php";

$baseUrl = 'http://localhost';
$timeout = 3;
$filename = null;

$options = getopt('h:t:f');

if (isset($options['h'])) {
    $baseUrl = $options['h'];
}
if (isset($options['t'])) {
    $timeout = $options['t'];
}
if (isset($options['f'])) {
    $filename = $options['f'];
}

$statusLogger = new OutbackPowerDataCollector("{$baseUrl}/Dev_status.cgi");
$battLogger = new OutbackPowerDataCollector("${baseUrl}/Dev_batt.cgi");

$csvLogger = new DataLogger();

$lineCount = 0;
while(true) {
    $statusData = $statusLogger->collectData(['Port' => 0]);
    // $battData   = $battLogger->collectData();
    if ($lineCount == 0) {
        echo $csvLogger->jsonToHeadlineCsv($statusData) . PHP_EOL;
    }
    $lineCount++;
    echo $csvLogger->jsonToCsv($statusData) . PHP_EOL;
    // echo $csvLogger->jsonToCsv($battData);
    sleep(10);
}

