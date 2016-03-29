<?php

namespace Zelenin\SmsRu\Auth;

use Zelenin\SmsRu\Api;

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
