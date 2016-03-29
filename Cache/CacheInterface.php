<?php

namespace Zelenin\SmsRu\Cache;

interface CacheInterface
{

    /**
     * @param string $key
     * @return boolean
     */
    public function exists($key);

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * @param string $key
     * @param mixed $value
     * @param integer|null $ttl
     * @return mixed
     */
    public function set($key, $value, $ttl = null);

    /**
     * @param string $key
     * @return boolean
     */
    public function remove($key);
}
