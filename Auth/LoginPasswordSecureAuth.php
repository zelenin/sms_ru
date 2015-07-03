<?php

namespace Zelenin\SmsRu\Auth;

class LoginPasswordSecureAuth extends AbstractAuth
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
    private $apiId;

    /**
     * @param string $login
     * @param string $password
     * @param null|string $apiId
     */
    public function __construct($login, $password, $apiId = null)
    {
        $this->login = $login;
        $this->password = $password;
        $this->apiId = $apiId;
    }

    /**
     * @return array
     */
    public function getAuthParams()
    {
        $token = $this->authGetToken();
        return [
            'login' => $this->login,
            'token' => $token,
            'sha512' => $this->apiId
                ? hash('sha512', $this->password . $token . $this->apiId)
                : hash('sha512', $this->password . $token)
        ];
    }

    /**
     * @return string
     */
    private function authGetToken()
    {
        return $this->getContext()->getClient()->request('auth/get_token');
    }

    public function getApiId()
    {
        return $this->apiId;
    }
}
