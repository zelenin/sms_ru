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
     * @param string $login
     * @param string $password
     */
    public function __construct($login, $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * @return array
     */
    public function getAuthParams()
    {
        return [
            'login' => $this->login,
            'password' => $this->password,
        ];
    }
}
