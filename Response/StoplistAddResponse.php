<?php

namespace Zelenin\SmsRu\Response;

class StoplistAddResponse extends AbstractResponse
{

    /**
     * @var array
     */
    protected $availableDescriptions = [
        '100' => 'Номер добавлен в стоплист.',
        '202' => 'Номер телефона в неправильном формате.',
    ];
}
