<?php

namespace Zelenin\SmsRu\Auth;

class ApiIdAuth extends AbstractAuth
{

    /**
     * @var string
     */
    private $apiId;

    /**
     * @param string $apiId
     */
    public function __construct($apiId)
    {
        $this->apiId = $apiId;
    }

    /**
     * @return array
     */
    public function getAuthParams()
    {
        return [
            'api_id' => $this->apiId,
        ];
    }

    /**
     * @return string
     */
    public function getApiId()
    {
        return $this->apiId;
    }
}
