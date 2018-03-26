<?php

namespace Zelenin\SmsRu\Client;

use Zelenin\SmsRu\Exception\Exception;

class Client implements ClientInterface
{
    /**
     * Additional configuration for Guzzle Client.
     * For example @link http://docs.guzzlephp.org/en/stable/request-options.html#verify
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    private $baseUrl = 'https://sms.ru/{method}';

    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;
    }

    /**
     * @param string $method
     * @param array $params
     *
     * @return string
     *
     * @throws Exception
     */
    public function request($method, $params = [])
    {
        $client = new \GuzzleHttp\Client();

        // Merge with specific config
        $params = array_merge($this->config, ['query' => $params]);

        $response = $client->post($this->getUrl($method), $params);

        if ($response->getStatusCode() === 200) {
            return (string)$response->getBody();
        } else {
            throw new Exception(sprintf('Sms.ru problem. Status code is %s', $response->getStatusCode()), $response->getStatusCode());
        }
    }

    /**
     * @param string $method
     *
     * @return string
     */
    private function getUrl($method)
    {
        return strtr($this->baseUrl, ['{method}' => $method]);
    }
}
