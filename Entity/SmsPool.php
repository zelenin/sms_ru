<?php

namespace Zelenin\SmsRu\Entity;

/**
 * Class SmsPool
 * @package Zelenin\SmsRu\Entity
 */
class SmsPool extends AbstractSms
{

    /**
     * @var Sms[]
     */
    public $messages;

    /**
     * @param Sms[] $messages
     */
    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }
}
