<?php
/**
 * DataLogger module
 */
class DataLogger
{
    /**
     * converts json into headline for csv
     *
     * @param string $json json string
     *
     * @return string
     */
    function jsonToHeadlineCsv($json) {
        $data = json_decode($json, true);
        $csv = "";
        array_walk_recursive($data, function($v, $k) use (&$csv) {
            $csv .= ";'$k'";
        });

        return substr($csv, 2);
    }

    /**
     * converts json into csv
     *
     * @param string $json json string
     *
     * @return string
     */
    function jsonToCsv($json) {
        $data = json_decode($json, true);
        $csv = "";
        array_walk_recursive($data, function($v, $k) use (&$csv) {
            $csv .= ";'$v'";
        });

        return substr($csv, 2);
    }

}
