<?php

namespace Zelenin\SmsRu\Auth\TokenCache;

class ArrayCache implements CacheInterface
{

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @inheritdoc
     */
    public function exists($key)
    {
        return isset($this->cache[$key]) && ($this->cache[$key][1] === 0 || $this->cache[$key][1] > microtime(true));
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return isset($this->cache[$key]) && ($this->cache[$key][1] === 0 || $this->cache[$key][1] > microtime(true))
            ? $this->cache[$key][0]
            : false;
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $ttl = null)
    {
        $ttl = (int)$ttl;
        $ttl = empty($ttl)
            ? 31536000 // 1 year
            : $ttl;
        $this->cache[$key] = [$value, microtime(true) + $ttl];

        return true;
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        unset($this->cache[$key]);

        return true;
    }
}
