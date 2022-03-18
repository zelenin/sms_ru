<?php

namespace Zelenin\SmsRu\Response;

class CallCodeResponse extends AbstractResponse
{
    /** @var int|null */
    public $checkCode;

    /** @var float|null */
    public $balance;

    /** @var float|null */
    public $cost;

    /** @var string|null */
    public $callId;

    protected $availableDescriptions = [
        '100' => 'Запрос выполнен. На второй строчке вы найдете ваше текущее дневное ограничение. На третьей строчке количество сообщений, отправленных вами в текущий день.',
        '200' => 'Неправильный api_id.',
        '210' => 'Используется GET, где необходимо использовать POST.',
        '211' => 'Метод не найден.',
        '220' => 'Сервис временно недоступен, попробуйте чуть позже.',
        '300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился).',
        '301' => 'Неправильный пароль, либо пользователь не найден.',
        '302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс).',
    ];

    public static function makeByJson(array $json)
    {
        if ($json['status'] == 'ERROR') {
            $errorCode = '400';
            $response = new CallCodeResponse($errorCode);
            $response->availableDescriptions[$errorCode] = $json['status_text'];
            return $response;
        }

        $response = new CallCodeResponse('100');

        $response->checkCode = $json['code'];
        $response->balance = $json['balance'];
        $response->cost = $json['cost'];
        $response->callId = $json['call_id'];

        return $response;
    }
}