<?php

namespace Zelenin\SmsRu\Cache;

class ArrayCache implements CacheInterface
{

    protected $_cache = [];

    /**
     * @param string $key
     * @return boolean
     */
    public function exists($key)
    {
        return isset($this->_cache[$key]) && ($this->_cache[$key][1] === 0 || $this->_cache[$key][1] > microtime(true));
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return isset($this->_cache[$key]) && ($this->_cache[$key][1] === 0 || $this->_cache[$key][1] > microtime(true))
            ? $this->_cache[$key][0]
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
        $this->_cache[$key] = [$value, empty($ttl) ? 0 : microtime(true) + $ttl];

        return true;
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function remove($key)
    {
        unset($this->_cache[$key]);

        return true;
    }
}
