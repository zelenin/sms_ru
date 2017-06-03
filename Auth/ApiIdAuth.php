<?php

namespace Zelenin\SmsRu\Auth;

class ApiIdAuth extends AbstractAuth
{
    
    /**
     * @var string
     */
    private $apiId;
    
    /**
     * @var null|string
     */
    private $partnerId;
    
    /**
     * @param string $apiId
     * @param null|string $partnerId
     */
    public function __construct($apiId, $partnerId = null)
    {
        $this->apiId = $apiId;
        $this->partnerId = $partnerId;
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
    
    /**
     * @return null|string
     */
    public function getPartnerId()
    {
        return $this->partnerId;
    }
}
