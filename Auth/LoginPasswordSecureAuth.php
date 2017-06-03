<?php

namespace Zelenin\SmsRu\Auth;

use Zelenin\SmsRu\Auth\TokenCache\CacheInterface;
use Zelenin\SmsRu\Auth\TokenCache\DummyCache;

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
     * @var null|string
     */
    private $partnerId;

    /**
     * @var CacheInterface
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
     * @param null|string $partnerId
     */
    public function __construct($login, $password, $apiId = null, CacheInterface $cache = null, $partnerId=null)
    {
        $this->login = $login;
        $this->password = $password;
        $this->apiId = $apiId;
        $this->cache = $cache === null ? new DummyCache() : $cache;
        $this->partnerId = $partnerId;
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
     * @return null|string
     */
    public function getPartnerId()
    {
        return $this->partnerId;
    }

    /**
     * @return string
     */
    private function authGetToken()
    {
        $token = null;
        if ($this->cache->exists($this->cacheKey)) {
            $token = $this->cache->get($this->cacheKey);
        }

        if (!$token) {
            $token = $this->requestAuthToken();
            $this->cache->set($this->cacheKey, $token, 60 * 9);
        }

        return $token;
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
