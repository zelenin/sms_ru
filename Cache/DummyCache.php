<?php

namespace Zelenin\SmsRu\Cache;

class DummyCache implements CacheInterface
{

    public function exists($key)
    {
        return false;
    }

    public function get($key, $default = null)
    {
        return $default;
    }

    public function set($key, $value, $ttl = null)
    {
        return false;
    }

    public function remove($key)
    {
        return true;
    }
}
