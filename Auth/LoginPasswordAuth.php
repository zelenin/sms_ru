<?php

namespace Zelenin\SmsRu\Auth;

class LoginPasswordAuth extends AbstractAuth
{
    /**
     * @var string
     */
    private $login;
    
    /**
     * @var string
     */
    private $password;
    
    /**
     * @var null|string
     */
    private $partnerId;
    
    /**
     * @param string $login
     * @param string $password
     * @param null|string $partnerId
     */
    public function __construct($login, $password, $partnerId = null)
    {
        $this->login = $login;
        $this->password = $password;
        $this->partnerId = $partnerId;
    }
    
    /**
     * @return array
     */
    public function getAuthParams()
    {
        return [
            'login'    => $this->login,
            'password' => $this->password,
        ];
    }
    
    /**
     * @return null|string
     */
    public function getPartnerId()
    {
        return $this->partnerId;
    }
}
