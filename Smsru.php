<?php

namespace Zelenin;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;

class Smsru
{
    /** @var Client */
    private $client = null;
    private $apiId = null;
    private $login = null;
    private $password = null;
    private $token;
    private $sha512;

    const API_HOST = 'sms.ru';
    const METHOD_SMS_SEND = 'sms/send';
    const METHOD_SMS_STATUS = 'sms/status';
    const METHOD_SMS_COST = 'sms/cost';
    const METHOD_MY_BALANCE = 'my/balance';
    const METHOD_MY_LIMIT = 'my/limit';
    const METHOD_MY_SENDERS = 'my/senders';
    const METHOD_AUTH_GET_TOKEN = 'auth/get_token';
    const METHOD_AUTH_CHECK = 'auth/check';
    const METHOD_STOPLIST_ADD = 'stoplist/add';
    const METHOD_STOPLIST_DEL = 'stoplist/del';
    const METHOD_STOPLIST_GET = 'stoplist/get';

    const MAX_TIME = 604800;

    public function  __construct()
    {
    }

    public function setApiId($apiId)
    {
        $this->apiId = $apiId;
        return $this;
    }

    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function smsSend(
        $to,
        $text,
        $from = null,
        $time = null,
        $translit = false,
        $test = false,
        $partner_id = null
    ) {
        $messages = [[$to, $text]];
        return $this->multiSmsSend($messages, $from, $time, $translit, $test, $partner_id);
    }

    public function multiSmsSend(
        $messages,
        $from = null,
        $time = null,
        $translit = false,
        $test = false,
        $partner_id = null
    ) {
        $params = [];
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

        $result = $this->method(static::METHOD_SMS_SEND, $params);
        $result = explode("\n", $result);

        $response = [];
        $response['code'] = array_shift($result);
        $response['description'] = $this->getAnswer(static::METHOD_SMS_SEND, $response['code']);

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
        $mail = $this->apiId . '@' . static::API_HOST;
        $subject = $from
            ? $to . ' from:' . $from
            : $to;
        $headers = 'Content-Type: text/html; charset=UTF-8';
        return mail($mail, $subject, $text, $headers);
    }

    public function smsStatus($id)
    {
        $params['id'] = $id;
        $result = $this->method(static::METHOD_SMS_STATUS, $params);

        $response = [];
        $response['code'] = $result;
        $response['description'] = $this->getAnswer(static::METHOD_SMS_STATUS, $response['code']);
        return $response;
    }

    public function smsCost($to, $text)
    {
        $params['to'] = $to;
        $params['text'] = $text;

        $result = $this->method(static::METHOD_SMS_COST, $params);
        $result = explode("\n", $result);

        return [
            'code' => $result[0],
            'description' => $this->getAnswer(static::METHOD_SMS_COST, $result[0]),
            'price' => array_key_exists(1, $result) ? $result[1] : null,
            'number' => array_key_exists(2, $result) ? $result[2] : null
        ];
    }

    public function myBalance()
    {
        $result = $this->method(static::METHOD_MY_BALANCE);
        $result = explode("\n", $result);
        return [
            'code' => $result[0],
            'description' => $this->getAnswer(static::METHOD_MY_BALANCE, $result[0]),
            'balance' => array_key_exists(1, $result) ? $result[1] : null
        ];
    }

    public function myLimit()
    {
        $result = $this->method(static::METHOD_MY_LIMIT);
        $result = explode("\n", $result);
        return [
            'code' => $result[0],
            'description' => $this->getAnswer(static::METHOD_MY_LIMIT, $result[0]),
            'total' => array_key_exists(1, $result) ? $result[1] : null,
            'current' => array_key_exists(2, $result) ? $result[2] : null
        ];
    }

    public function mySenders()
    {
        $result = $this->method(static::METHOD_MY_SENDERS);
        $result = explode("\n", rtrim($result));

        $response = [
            'code' => $result[0],
            'description' => $this->getAnswer(static::METHOD_MY_SENDERS, $result[0]),
            'senders' => $result
        ];
        unset($response['senders'][0]);
        $response['senders'] = array_values($response['senders']);
        return $response;
    }

    public function authGetToken()
    {
        return (string)$this->request('http://' . static::API_HOST . '/' . static::METHOD_AUTH_GET_TOKEN);
    }

    public function authCheck()
    {
        $result = $this->method(static::METHOD_AUTH_CHECK);
        $response = [];
        $response['code'] = $result;
        $response['description'] = $this->getAnswer(static::METHOD_AUTH_CHECK, $response['code']);
        return $response;
    }

    public function stoplistAdd($stoplistPhone, $stoplistText)
    {
        $params['stoplist_phone'] = $stoplistPhone;
        $params['stoplist_text'] = $stoplistText;
        $result = $this->method(static::METHOD_STOPLIST_ADD, $params);

        $response = [];
        $response['code'] = $result;
        $response['description'] = $this->getAnswer(static::METHOD_STOPLIST_ADD, $response['code']);
        return $response;
    }

    public function stoplistDel($stoplistPhone)
    {
        $params['stoplist_phone'] = $stoplistPhone;
        $result = $this->method(static::METHOD_STOPLIST_DEL, $params);

        $response = [];
        $response['code'] = $result;
        $response['description'] = $this->getAnswer(static::METHOD_STOPLIST_DEL, $response['code']);
        return $response;
    }

    public function stoplistGet()
    {
        $result = $this->method(static::METHOD_STOPLIST_GET);

        $result = explode("\n", rtrim($result));
        $response = [
            'code' => $result[0],
            'description' => $this->getAnswer(static::METHOD_STOPLIST_GET, $result[0]),
            'stoplist' => $result
        ];
        $count = count($response['stoplist']);
        $stoplist = [];
        for ($i = 1; $i < $count; $i++) {
            $result = explode(';', $response['stoplist'][$i]);
            $stoplist[$i - 1]['number'] = $result[0];
            $stoplist[$i - 1]['note'] = $result[1];
        }
        $response['stoplist'] = $stoplist;
        return $response;
    }

    private function getAuthParams()
    {
        if ($this->login && $this->password) {
            $this->token = $this->authGetToken();
            $this->sha512 = $this->getSha512();

            $params['login'] = $this->login;
            $params['token'] = $this->token;
            $params['sha512'] = $this->sha512;
        } elseif ($this->apiId) {
            $params['api_id'] = $this->apiId;
        } else {
            throw new Exception('You should set login/password or api_id');
        }
        return $params;
    }

    private function getSha512()
    {
        return $this->apiId
            ? hash('sha512', $this->password . $this->token . $this->apiId)
            : hash('sha512', $this->password . $this->token);
    }

    private function getAnswer($key, $code)
    {
        $responseCode = [
            static::METHOD_SMS_SEND => [
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
                '212' => 'Текст сообщения необходимо передать в кодировке UTF-8 (вы передали в другой кодировке).',
                '220' => 'Сервис временно недоступен, попробуйте чуть позже.',
                '230' => 'Сообщение не принято к отправке, так как на один номер в день нельзя отправлять более 60 сообщений.',
                '300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился).',
                '301' => 'Неправильный пароль, либо пользователь не найден.',
                '302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс).'
            ],
            static::METHOD_SMS_STATUS => [
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
            static::METHOD_SMS_COST => [
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
            static::METHOD_MY_BALANCE => [
                '100' => 'Запрос выполнен. На второй строчке вы найдете ваше текущее состояние баланса.',
                '200' => 'Неправильный api_id.',
                '210' => 'Используется GET, где необходимо использовать POST.',
                '211' => 'Метод не найден.',
                '220' => 'Сервис временно недоступен, попробуйте чуть позже.',
                '300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился).',
                '301' => 'Неправильный пароль, либо пользователь не найден.',
                '302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс).'
            ],
            static::METHOD_MY_LIMIT => [
                '100' => 'Запрос выполнен. На второй строчке вы найдете ваше текущее дневное ограничение. На третьей строчке количество сообщений, отправленных вами в текущий день.',
                '200' => 'Неправильный api_id.',
                '210' => 'Используется GET, где необходимо использовать POST.',
                '211' => 'Метод не найден.',
                '220' => 'Сервис временно недоступен, попробуйте чуть позже.',
                '300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился).',
                '301' => 'Неправильный пароль, либо пользователь не найден.',
                '302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс).'
            ],
            static::METHOD_MY_SENDERS => [
                '100' => 'Запрос выполнен. На второй и последующих строчках вы найдете ваших одобренных отправителей, которые можно использовать в параметре &from= метода sms/send.',
                '200' => 'Неправильный api_id.',
                '210' => 'Используется GET, где необходимо использовать POST.',
                '211' => 'Метод не найден.',
                '220' => 'Сервис временно недоступен, попробуйте чуть позже.',
                '300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился).',
                '301' => 'Неправильный пароль, либо пользователь не найден.',
                '302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс).'
            ],
            static::METHOD_AUTH_CHECK => [
                '100' => 'ОК, номер телефона и пароль совпадают.',
                '300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился).',
                '301' => 'Неправильный пароль, либо пользователь не найден.',
                '302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс).'
            ],
            static::METHOD_STOPLIST_ADD => [
                '100' => 'Номер добавлен в стоплист.',
                '202' => 'Номер телефона в неправильном формате.'
            ],
            static::METHOD_STOPLIST_DEL => [
                '100' => 'Номер удален из стоплиста.',
                '202' => 'Номер телефона в неправильном формате.'
            ],
            static::METHOD_STOPLIST_GET => [
                '100' => 'Запрос обработан. На последующих строчках будут идти номера телефонов, указанных в стоплисте в формате номер;примечание.'
            ]
        ];
        return isset($responseCode[$key][$code])
            ? $responseCode[$key][$code]
            : null;
    }

    public function method($name, $params = [])
    {
        return (string)$this->request('http://' . static::API_HOST . '/' . $name, array_merge($params, $this->getAuthParams()));
    }

    private function request($url, $params = [])
    {
        $client = $this->getClient();
        /** @var Response $response */
        $response = $this->client->post($url, ['body' => $params]);
        if ($response->getStatusCode() == 200) {
            return $response->getBody();
        } else {
            throw new Exception('Sms.ru problem. Status code is ' . $response->getStatusCode(), $response->getStatusCode());
        }
    }
    private function getClient() {
      if (!$this->client) {
        $this->setClient(new Client());
      }
      return $this->client;
    }
    public function setClient($client) {
      $this->client = $client;
    }

    public function handleCallbackData($data) {
        $preparedData = [];
        foreach($data as $blockData) {
            $lines = explode("\n", $blockData);
            if (array_key_exists(0, $lines)) {
                switch(trim($lines[0])) {
                    case 'sms_status':
                        if (array_key_exists(1, $lines) && array_key_exists(2, $lines)) {
                            $preparedData[self::METHOD_SMS_STATUS][$lines[1]] =
                                $this->getAnswer(self::METHOD_SMS_STATUS, $lines[2]);
                        }
                        break;
                }
            }
        }
        return $preparedData;
    }
}
