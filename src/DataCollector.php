<?php

require_once "DataCollectorException.php";

/**
 * the DataCollector class is used to collect data from the device
 *
 */
class DataCollector
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
     * @param string $baseUrl URL to fetch data from
     * @param int    $timeout time out in seconds, default 5
     */
    public function __construct($baseUrl = null, $timeout = 5)
    {
        $this->baseUrl = $baseUrl;
        $this->timeout = $timeout;
    }

    /**
     * @param string $url
     */
    public function setBaseUrl($url)
    {
        $this->baseUrl = $url;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * collect Data from the device
     *
     * @param array $params
     *
     * @throws DataCollectorException
     *
     * @return string
     */
    public function collectData(array $params = null)
    {
        if (is_null($this->baseUrl)) {
            throw new DataCollectorException('base url must not be null!');
        }

        $opts = [
            'http' => [
                'method'  => 'GET',
                'timeout' => $this->timeout
            ]
        ];

        $context  = stream_context_create($opts);
        $url  = $this->baseUrl;

        if (!is_null($params)) {
            $url .= '?' . http_build_query($params);
        }

        $data = file_get_contents($url, false, $context);

        if ($data === false) {
            throw new DataCollectorException("Can't collect data for base url!");
        }

        return $data;
    }
}
