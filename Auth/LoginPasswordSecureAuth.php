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
     * @var string
     */
    public $tokenClass = 'Zelenin\SmsRu\Auth\Token';

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
            'sha512' => !empty($this->apiId)
                ? hash('sha512', $this->password . $token . $this->apiId)
                : hash('sha512', $this->password . $token),
        ];
    }

    /**
     * @return null|string
     */
    public function getApiId()
    {
        return $this->apiId;
    }

    /**
     * @return string
     */
    protected function authGetToken()
    {
        return call_user_func(
            [$this->tokenClass, 'get'],
            $this->getContext()
        );
    }
}
