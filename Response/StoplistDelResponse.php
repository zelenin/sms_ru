<?php

namespace Zelenin\SmsRu\Response;

/**
 * Class StoplistDelResponse
 * @package Zelenin\SmsRu\Response
 */
class StoplistDelResponse extends AbstractResponse
{

    /**
     * @var array
     */
    protected $availableDescriptions = [
        '100' => 'Номер удален из стоплиста.',
        '202' => 'Номер телефона в неправильном формате.',
    ];
}
