<?php

namespace Zelenin\SmsRu\Entity;

abstract class AbstractSms
{

    /**
     * @var string
     */
    public $from;

    /**
     * @var string
     */
    public $time;

    /**
     * @var string
     */
    public $translit;

    /**
     * @var string
     */
    public $test;

    /**
     * @var string
     */
    public $partner_id;
}
