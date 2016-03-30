<?php

namespace Zelenin\SmsRu\Cache;

class ApcCache implements CacheInterface
{

    /**
     * @param string $key
     * @return boolean
     */
    public function exists($key)
    {
        return apc_exists($key);
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $result = apc_fetch($key, $success);

        return $success
            ? $result
            : $default;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param integer|null $ttl
     * @return mixed
     */
    public function set($key, $value, $ttl = null)
    {
        return apc_store($key, $value, $ttl);
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function remove($key)
    {
        return apc_delete($key);
    }
}
