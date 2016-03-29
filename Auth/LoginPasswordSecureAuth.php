<?php

namespace Zelenin\SmsRu\Auth;

use Zelenin\SmsRu\Cache\CacheInterface;

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
     * @var CacheInterface|null
     */
    public $Cache;

    /**
     * @var string
     */
    public $cacheKey = 'zelenin.smsru.auth.token';

    /**
     * @param string $login
     * @param string $password
     * @param null|string $apiId
     * @param CacheInterface|null $Cache
     */
    public function __construct($login, $password, $apiId = null, CacheInterface $Cache = null)
    {
        $this->login = $login;
        $this->password = $password;
        $this->apiId = $apiId;
        $this->Cache = $Cache;
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
        $Cache = $this->Cache;

        if (empty($Cache)) {
            $result = $this->requestAuthToken();
        } elseif ($Cache->exists($this->cacheKey)) {
            $result = $Cache->get($this->cacheKey);
        } else {
            $result = $this->requestAuthToken();

            $Cache->set($this->cacheKey, $result, 60 * 9);
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function requestAuthToken()
    {
        return $this->getContext()
            ->getClient()
            ->request('auth/get_token');
    }
}
