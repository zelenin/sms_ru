<?php

namespace Zelenin\SmsRu\Auth\TokenCache;

class DummyCache implements CacheInterface
{

    /**
     * @inheritdoc
     */
    public function exists($key)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $ttl = null)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        return true;
    }
}
