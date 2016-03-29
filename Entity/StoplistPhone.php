<?php

namespace Zelenin\SmsRu\Entity;

class StoplistPhone
{

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $text;

    /**
     * @param string $phone
     * @param string $text
     */
    public function __construct($phone, $text)
    {
        $this->phone = $phone;
        $this->text = $text;
    }
}
