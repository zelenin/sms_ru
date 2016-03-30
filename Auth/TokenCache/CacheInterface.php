<?php

namespace Zelenin\SmsRu\Auth\TokenCache;

interface CacheInterface
{

    /**
     * @param string $key
     *
     * @return boolean
     */
    public function exists($key);

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * @param string $key
     * @param mixed $value
     * @param integer|null $ttl
     *
     * @return mixed
     */
    public function set($key, $value, $ttl = null);

    /**
     * @param string $key
     *
     * @return boolean
     */
    public function remove($key);
}
