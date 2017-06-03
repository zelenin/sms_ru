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
     * @return null|string
     */
    public function getPartnerId();

    /**
     * @return Api
     */
    public function getContext();

    /**
     * @param Api $context
     */
    public function setContext(Api $context);
}
