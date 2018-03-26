<?php

namespace Zelenin\SmsRu\Client;

interface ClientInterface
{

    /**
     * Provides an additional configs for client service
     * @param array $config
     */
    public function __construct($config = []);

    /**
     * @param string $method
     * @param array $params
     *
     * @return string
     */
    public function request($method, $params = []);
}
