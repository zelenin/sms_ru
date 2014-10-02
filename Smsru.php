<?php

namespace Zelenin;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;

class Smsru
{
    /** @var Client */
    private $client = null;
    private $apiId;
    private $login;
    private $password;
    private $authParams = [];
    private $token;
    private $sha512;

    const VERSION = '2.0.0';
    const HOST = 'sms.ru';
    const SEND = 'sms/send';
    const STATUS = 'sms/status';
    const COST = 'sms/cost';
    const BALANCE = 'my/balance';
    const LIMIT = 'my/limit';
    const SENDERS = 'my/senders';
    const GET_TOKEN = 'auth/get_token';
    const CHECK = 'auth/check';
    const ADD = 'stoplist/add';
    const DEL = 'stoplist/del';
    const GET = 'stoplist/get';
    const UCS = 'sms/ucs';

    const MAX_TIME = 604800; //7 * 60 * 60 * 24

    private $response_code = [
        'send' => [
            '100' => 'Сообщение принято к отправке. На следующих строчках вы найдете идентификаторы отправленных сообщений в том же порядке, в котором вы указали номера, на которых совершалась отправка.',
            '200' => 'Неправильный api_id.',
            '201' => 'Не хватает средств на лицевом счету.',
            '202' => 'Неправильно указан получатель.',
            '203' => 'Нет текста сообщения.',
            '204' => 'Имя отправителя не согласовано с администрацией.',
            '205' => 'Сообщение слишком длинное (превышает 8 СМС).',
            '206' => 'Будет превышен или уже превышен дневной лимит на отправку сообщений.',
            '207' => 'На этот номер (или один из номеров) нельзя отправлять сообщения, либо указано более 100 номеров в списке получателей.',
            '208' => 'Параметр time указан неправильно.',
            '209' => 'Вы добавили этот номер (или один из номеров) в стоп-лист.',
            '210' => 'Используется GET, где необходимо использовать POST.',
            '211' => 'Метод не найден.',
            '220' => 'Сервис временно недоступен, попробуйте чуть позже.',
            '300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился).',
            '301' => 'Неправильный пароль, либо пользователь не найден.',
            '302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс).'
        ],
        'status' => [
            '-1' => 'Сообщение не найдено.',
            '100' => 'Сообщение находится в нашей очереди.',
            '101' => 'Сообщение передается оператору.',
            '102' => 'Сообщение отправлено (в пути).',
            '103' => 'Сообщение доставлено.',
            '104' => 'Не может быть доставлено: время жизни истекло.',
            '105' => 'Не может быть доставлено: удалено оператором.',
            '106' => 'Не может быть доставлено: сбой в телефоне.',
            '107' => 'Не может быть доставлено: неизвестная причина.',
            '108' => 'Не может быть доставлено: отклонено.',
            '200' => 'Неправильный api_id.',
            '210' => 'Используется GET, где необходимо использовать POST.',
            '211' => 'Метод не найден.',
            '220' => 'Сервис временно недоступен, попробуйте чуть позже.',
            '300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился).',
            '301' => 'Неправильный пароль, либо пользователь не найден.',
            '302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс).'
        ],
        'cost' => [
            '100' => 'Запрос выполнен. На второй строчке будет указана стоимость сообщения. На третьей строчке будет указана его длина.',
            '200' => 'Неправильный api_id.',
            '202' => 'Неправильно указан получатель.',
            '207' => 'На этот номер нельзя отправлять сообщения.',
            '210' => 'Используется GET, где необходимо использовать POST.',
            '211' => 'Метод не найден.',
            '220' => 'Сервис временно недоступен, попробуйте чуть позже.',
            '300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился).',
            '301' => 'Неправильный пароль, либо пользователь не найден.',
            '302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс).'
        ],
        'balance' => [
            '100' => 'Запрос выполнен. На второй строчке вы найдете ваше текущее состояние баланса.',
            '200' => 'Неправильный api_id.',
            '210' => 'Используется GET, где необходимо использовать POST.',
            '211' => 'Метод не найден.',
            '220' => 'Сервис временно недоступен, попробуйте чуть позже.',
            '300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился).',
            '301' => 'Неправильный пароль, либо пользователь не найден.',
            '302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс).'
        ],
        'limit' => [
            '100' => 'Запрос выполнен. На второй строчке вы найдете ваше текущее дневное ограничение. На третьей строчке количество сообщений, отправленных вами в текущий день.',
            '200' => 'Неправильный api_id.',
            '210' => 'Используется GET, где необходимо использовать POST.',
            '211' => 'Метод не найден.',
            '220' => 'Сервис временно недоступен, попробуйте чуть позже.',
            '300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился).',
            '301' => 'Неправильный пароль, либо пользователь не найден.',
            '302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс).'
        ],
        'senders' => [
            '100' => 'Запрос выполнен. На второй и последующих строчках вы найдете ваших одобренных отправителей, которые можно использовать в параметре &from= метода sms/send.',
            '200' => 'Неправильный api_id.',
            '210' => 'Используется GET, где необходимо использовать POST.',
            '211' => 'Метод не найден.',
            '220' => 'Сервис временно недоступен, попробуйте чуть позже.',
            '300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился).',
            '301' => 'Неправильный пароль, либо пользователь не найден.',
            '302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс).'
        ],
        'check' => [
            '100' => 'ОК, номер телефона и пароль совпадают.',
            '300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился).',
            '301' => 'Неправильный пароль, либо пользователь не найден.',
            '302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс).'
        ],
        'add' => [
            '100' => 'Номер добавлен в стоплист.',
            '202' => 'Номер телефона в неправильном формате.'
        ],
        'del' => [
            '100' => 'Номер удален из стоплиста.',
            '202' => 'Номер телефона в неправильном формате.'
        ],
        'get' => [
            '100' => 'Запрос обработан. На последующих строчках будут идти номера телефонов, указанных в стоплисте в формате номер;примечание.'
        ]
    ];

    public function  __construct($apiId = null, $login = null, $password = null)
    {
        $this->apiId = $apiId;
        $this->login = $login;
        $this->password = $password;
        $this->authParams = $this->getAuthParams();
    }

    public function smsSend($to, $text, $from = null, $time = null, $translit = false, $test = false, $partner_id = null) {
        $messages = [[$to, $text]];
        return $this->multiSmsSend($messages, $from, $time, $translit, $test, $partner_id);
    }

    public function multiSmsSend($messages, $from = null, $time = null, $translit = false, $test = false, $partner_id = null) {
        foreach ($messages as $message) {
            $params['multi'][$message[0]] = $message[1];
        }

        if ($from) {
            $params['from'] = $from;
        }

        if ($time && $time < (time() + static::MAX_TIME)) {
            $params['time'] = $time;
        }

        if ($translit) {
            $params['translit'] = 1;
        }

        if ($test) {
            $params['test'] = 1;
        }

        if ($partner_id) {
            $params['partner_id'] = $partner_id;
        }

        $result = $this->method(static::SEND, $params);
        $result = explode("\n", $result);

        $response = [];
        $response['code'] = array_shift($result);
        $response['description'] = $this->getAnswer('send', $response['code']);

        if ($response['code'] == 100) {
            foreach ($result as $id) {
                if (!preg_match('/=/', $id)) {
                    $response['ids'][] = $id;
                } else {
                    $result = explode('=', $id);
                    $response[$result[0]] = $result[1];
                }
            }
        }
        return $response;
    }

    public function smsMail($to, $text, $from = null)
    {
        $mail = $this->apiId . '@' . static::HOST;
        $subject = $from ? $to . ' from:' . $from : $to;
        $headers = 'Content-Type: text/html; charset=UTF-8';
        return mail($mail, $subject, $text, $headers);
    }

    public function smsStatus($id)
    {
        $params['id'] = $id;
        $result = $this->method(static::STATUS, $params);

        $response = [];
        $response['code'] = $result;
        $response['description'] = $this->getAnswer('status', $response['code']);
        return $response;
    }

    public function smsCost($to, $text)
    {
        $params['to'] = $to;
        $params['text'] = $text;

        $result = $this->method(static::COST, $params);
        $result = explode("\n", $result);

        return [
            'code' => $result[0],
            'description' => $this->getAnswer('cost', $result[0]),
            'price' => $result[1],
            'number' => $result[2]
        ];
    }

    public function myBalance()
    {
        $result = $this->method(static::BALANCE);
        $result = explode("\n", $result);
        return [
            'code' => $result[0],
            'description' => $this->getAnswer('balance', $result[0]),
            'balance' => $result[1]
        ];
    }

    public function my_limit()
    {
        $result = $this->method(static::LIMIT);
        $result = explode("\n", $result);
        return [
            'code' => $result[0],
            'description' => $this->getAnswer('limit', $result[0]),
            'total' => $result[1],
            'current' => $result[2]
        ];
    }

    public function my_senders()
    {
        $result = $this->method(static::SENDERS);
        $result = explode("\n", rtrim($result));

        $response = [
            'code' => $result[0],
            'description' => $this->getAnswer('senders', $result[0]),
            'senders' => $result
        ];
        unset($response['senders'][0]);
        $response['senders'] = array_values($response['senders']);
        return $response;
    }

    public function authCheck()
    {
        /* todo */
        $result = $this->method(static::CHECK, $this->authParams);
        $response = [];
        $response['code'] = $result;
        $response['description'] = $this->getAnswer('check', $response['code']);
        return $response;
    }

    public function stoplist_add($stoplist_phone, $stoplist_text)
    {
        $params['stoplist_phone'] = $stoplist_phone;
        $params['stoplist_text'] = $stoplist_text;
        $result = $this->method(static::ADD, $params);

        $response = [];
        $response['code'] = $result;
        $response['description'] = $this->getAnswer('add', $response['code']);
        return $response;
    }

    public function stoplist_del($stoplist_phone)
    {
        $params['stoplist_phone'] = $stoplist_phone;
        $result = $this->method(static::DEL, $params);

        $response = [];
        $response['code'] = $result;
        $response['description'] = $this->getAnswer('del', $response['code']);
        return $response;
    }

    public function stoplistGet()
    {
        $result = $this->method(static::GET);

        $result = explode("\n", rtrim($result));
        $response = [
            'code' => $result[0],
            'description' => $this->getAnswer('get', $result[0]),
            'stoplist' => $result
        ];
        $count = count($response['stoplist']);
        $stoplist=[];
        for ($i = 1; $i < $count; $i++) {
            $result = explode(';', $response['stoplist'][$i]);
            $stoplist[$i - 1]['number'] = $result[0];
            $stoplist[$i - 1]['note'] = $result[1];
        }
        $response['stoplist'] = $stoplist;
        return $response;
    }

    public function smsUcs()
    {
        return  $this->method(static::UCS);
    }

    private function getAuthParams()
    {
        if (!empty($this->login) && !empty($this->password)) {
            $this->token = $this->authGetToken();
            $this->sha512 = $this->getSha512();

            $params['login'] = $this->login;
            $params['token'] = $this->token;
            $params['sha512'] = $this->sha512;
        } else {
            $params['api_id'] = $this->apiId;
        }
        return $params;
    }

    private function authGetToken()
    {
        return $this->method(static::GET_TOKEN);
    }

    private function getSha512()
    {
        return !$this->apiId || empty($this->apiId)
            ? hash('sha512', $this->password . $this->token)
            : hash('sha512', $this->password . $this->token . $this->apiId);
    }

    private function getAnswer($key, $code)
    {
        return isset($this->response_code[$key][$code])
            ? $this->response_code[$key][$code]
            : null;
    }

    public function method($name,$params = [])
    {
        return $this->request('http://'.static::HOST . '/' .$name,array_merge($params,$this->authParams));
    }

    private function request($url, $params = [])
    {
        if (!$this->client) {
            $this->client = new Client();
        }
        /** @var Response $response */
        $response = $this->client->post($url, ['body' => $params]);
        if ($response->getStatusCode()==200) {
            return $response->getBody();
        } else {
            throw new \Exception('Sms.ru problem. Status code is ' . $response->getStatusCode(),$response->getStatusCode());
        }
    }
}
