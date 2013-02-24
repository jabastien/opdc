<?php
/**
 * Simple Script Command to provide the data of an Outback Power device to cacti
 */

require_once "CactiCommand.php";

$url     = 'http://localhost';
$timeout = 3;

$options = getopt("h:t:");
if (isset($options['h'])) {
    $url = $options['h'];
}
if (isset($options['t'])) {
    $timeout = $options['t'];
}

$cactiCommand = new CactiCommand($url, $timeout);
$cactiCommand->run();
