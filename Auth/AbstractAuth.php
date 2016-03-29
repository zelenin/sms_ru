<?php

namespace Zelenin\SmsRu\Auth;

use Zelenin\SmsRu\Api;

/**
 * Class AbstractAuth
 * @package Zelenin\SmsRu\Auth
 */
abstract class AbstractAuth implements AuthInterface
{

    /**
     * @var Api
     */
    private $context;

    /**
     * @return array
     */
    abstract public function getAuthParams();

    /**
     * @return Api
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param Api $context
     */
    public function setContext(Api $context)
    {
        $this->context = $context;
    }
}
