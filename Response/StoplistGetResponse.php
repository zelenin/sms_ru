<?php

namespace Zelenin\SmsRu\Response;

use Zelenin\SmsRu\Entity\StoplistPhone;

/**
 * Class StoplistGetResponse
 * @package Zelenin\SmsRu\Response
 */
class StoplistGetResponse extends AbstractResponse
{

    /**
     * @var StoplistPhone[]
     */
    public $phones = [];

    /**
     * @var array
     */
    protected $availableDescriptions = [
        '100' => 'Запрос обработан. На последующих строчках будут идти номера телефонов, указанных в стоплисте в формате номер;примечание.',
    ];
}
