<?php

namespace Zelenin\SmsRu\Client;

use Zelenin\SmsRu\Exception\Exception;

/**
 * Class Client
 * @package Zelenin\SmsRu\Client
 */
class Client implements ClientInterface
{

    /**
     * @var string
     */
    private $baseUrl = 'http://sms.ru/{method}';

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
        $Client = new \GuzzleHttp\Client();

        $Response = $Client->post($this->getUrl($method), ['query' => $params]);

        if ($Response->getStatusCode() === 200) {
            return (string)$Response->getBody();
        } else {
            throw new Exception(sprintf('Sms.ru problem. Status code is %s', $Response->getStatusCode()), $Response->getStatusCode());
        }
    }

    /**
     * @param string $method
     *
     * @return string
     */
    protected function getUrl($method)
    {
        return strtr($this->baseUrl, ['{method}' => $method]);
    }
}
