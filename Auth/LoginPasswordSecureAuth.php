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
    private $cache;

    /**
     * @var string
     */
    private $cacheKey = 'zelenin.smsru.auth.token';

    /**
     * @param string $login
     * @param string $password
     * @param null|string $apiId
     * @param CacheInterface|null $cache
     */
    public function __construct($login, $password, $apiId = null, CacheInterface $cache = null)
    {
        $this->login = $login;
        $this->password = $password;
        $this->apiId = $apiId;
        $this->cache = $cache;
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
    public function authGetToken()
    {
        $cache = $this->cache;

        if (empty($cache)) {
            $result = $this->requestAuthToken();
        } elseif ($cache->exists($this->cacheKey)) {
            $result = $cache->get($this->cacheKey);
        } else {
            $result = $this->requestAuthToken();

            $cache->set($this->cacheKey, $result, 60 * 9);
        }

        return $result;
    }

    /**
     * @return string
     */
    private function requestAuthToken()
    {
        return $this->getContext()
            ->getClient()
            ->request('auth/get_token');
    }
}
