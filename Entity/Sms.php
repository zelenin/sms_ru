<?php

namespace Zelenin\SmsRu\Entity;

class Sms extends AbstractSms
{

    /**
     * @var string
     */
    public $to;

    /**
     * @var null|string
     */
    public $text;

    /**
     * @param $to
     * @param null|string $text
     */
    public function __construct($to, $text = null)
    {
        $this->to = $to;
        $this->text = $text;
    }
}
