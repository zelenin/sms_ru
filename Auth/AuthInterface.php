<?php

namespace Zelenin\SmsRu\Auth;

use Zelenin\SmsRu\Api;

/**
 * Interface AuthInterface
 * @package Zelenin\SmsRu\Auth
 */
interface AuthInterface
{
    /**
     * @return array
     */
    public function getAuthParams();

    /**
     * @return Api
     */
    public function getContext();

    /**
     * @param Api $context
     */
    public function setContext(Api $context);
}
