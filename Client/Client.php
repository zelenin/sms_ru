<?php

namespace Zelenin\SmsRu\Client;

use Zelenin\SmsRu\Exception\Exception;

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
        $client = new \GuzzleHttp\Client();

        $response = $client->post($this->getUrl($method), ['query' => $params]);

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
