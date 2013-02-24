<?php
require_once "OutbackPowerDataCollector.php";
require_once "OutbackPowerDataCollectorException.php";

/**
 * CactiCommandClass implements the cacti command.
 *
 * after setting up the class, the run() method will collect and parse the data in
 * a format suitable for cacti
 */
class CactiCommand
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var int
     */
    private $timeout;

    /**
     * @var OutbackPowerDataCollector
     */
    private $statusCollector;
    /**
     * @var OutbackPowerDataCollector
     */
    private $battCollector;
    /**
     * @var array
     */
    private $statusData;

    /**
     * @var array
     */
    private $battData;

    /**
     * @param null $baseUrl
     * @param int  $timeout
     */
    public function __construct($baseUrl = null, $timeout = 5)
    {
        $this->baseUrl = $baseUrl;
        $this->timeout = $timeout;

        $this->statusCollector = new OutbackPowerDataCollector("{$baseUrl}/Dev_status.cgi", $timeout);
        $this->battCollector   = new OutbackPowerDataCollector("{$baseUrl}/Dev_batt.cgi", $timeout);
    }

    /**
     * collect data
     */
    public function collect()
    {
        $this->statusData = json_decode($this->statusCollector->collectData(['Port' => 0]), true);
        $this->battData   = json_decode($this->battCollector->collectData(), true);
    }

    /**
     * parse collected battery data
     *
     * @param array $data
     *
     * return @array
     */
    public function parseBattData(array $data)
    {
        $values = [];

        if (!array_key_exists('sys_battery', $data)) {
            return [];
        }

        array_walk($data['sys_battery'], function($val, $key) use (&$values) {
            $values[] = "{$key}:{$val}";
        });

        return $values;
    }

    /**
     * parse the status for the given port
     *
     * @param int   $portNum      port number
     * @param array $allowedValue fields we want to use
     * @param array $data         data for port
     *
     * @return array
     */
    public function parseStatusPort($portNum, array $allowedValue, array $data)
    {
        $values = [];

        array_walk($data, function($val, $key) use (&$values, $portNum, $allowedValue) {
            if (!in_array($key, $allowedValue)) {
                return;
            }
            $values[] = "Port{$portNum}_{$key}:{$val}";
        });

        return $values;
    }

    /**
     * parse collected status data
     *
     * @param array $data
     *
     * @return array
     */
    public function parseStatusData(array $data)
    {
        $values = [];

        if (!array_key_exists('devstatus', $data)) {
            return [];
        }

        $values[] = "Sys_Batt_V:{$data['devstatus']['Sys_Batt_V']}";

        $values = array_merge($values, $this->parseStatusPort(1, ['VAC_in', 'VAC_out', 'Batt_V'], $data['devstatus']['ports'][0]));
        $values = array_merge($values,
            $this->parseStatusPort(
                2,
                ['Out_I', 'In_I', 'Batt_V', 'In_V', 'Out_kWh', 'Out_AH'],
                $data['devstatus']['ports'][1]
            )
        );
        $values = array_merge($values,
            $this->parseStatusPort(
                4,
                ['Shunt_A_I', 'Shunt_A_AH', 'Shunt_A_kWh', 'Shunt_B_I', 'Shunt_B_AH', 'Shunt_B_kWh', 'SOC', 'Min_SOC', 'Days_since_full', 'In_AH_today', 'Out_AH_today', 'In_kWh_today', 'Out_kWh_today', 'Net_CFC_AH', 'Net_CFC_kWh', 'Batt_V'],
                $data['devstatus']['ports'][2]
            )
        );

        return $values;
    }

    /**
     *
     * run the cacti command
     */
    public function run()
    {
        try {
            $this->collect();
        } catch (Exception $e) {
            echo 'ERROR' . PHP_EOL;
            return;
        }
        $values = array_merge($this->parseStatusData($this->statusData), $this->parseBattData($this->battData));
        sort($values);

        echo implode(' ', $values) . PHP_EOL;
    }
}
