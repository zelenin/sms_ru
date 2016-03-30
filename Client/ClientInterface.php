<?php

namespace Zelenin\SmsRu\Client;

interface ClientInterface
{

    /**
     * @param string $method
     * @param array $params
     *
     * @return string
     */
    public function request($method, $params = []);
}
