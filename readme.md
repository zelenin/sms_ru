# sms_ru

PHP-класс для работы с api сервиса [sms.ru](http://sms.ru)

## Установка

### Предупреждение

Версия 4 имеет отличное от предыдущих версий API.

### Установка через Composer

Запустите

```
php composer.phar require zelenin/smsru "~4"
```

или добавьте

```js
"zelenin/smsru": "~4"
```

в секцию ```require``` вашего composer.json

## Использование

Простая авторизация (с помощью api_id):

```php
$client = new \Zelenin\SmsRu\Api(new \Zelenin\SmsRu\Auth\ApiIdAuth($apiId));
```

Усиленная авторизация (с помощью api_id, логина и пароля):

```php
$client = new \Zelenin\SmsRu\Api(new \Zelenin\SmsRu\Auth\LoginPasswordSecureAuth($login, $password, $apiId));
```

Усиленная авторизация (с помощью логина и пароля):

```php
$client = new \Zelenin\SmsRu\Api(new \Zelenin\SmsRu\Auth\LoginPasswordAuth($login, $password));
```

Отправка SMS:

```php
$sms1 = new \Zelenin\SmsRu\Entity\Sms($phone1, $text1);
$sms1->translit = 1;
$sms2 = new \Zelenin\SmsRu\Entity\Sms($phone2, $text2);

$client->smsSend($sms1);
$client->smsSend($sms2);

$client->smsSend(new \Zelenin\SmsRu\Entity\SmsPool([$sms1, $sms2]));
```

Статус SMS:

```php
$sms->smsStatus($smsId);
```

Стоимость SMS:

```php
$sms->smsCost(new \Zelenin\SmsRu\Entity\Sms($phone, $text));
```

Баланс:

```php
$sms->myBalance();
```

Дневной лимит:

```php
$sms->myLimit();
```

Отправители:

```php
$sms->mySenders();
```

Проверка валидности логина и пароля:

```php
$sms->authCheck();
```

Добавить номер в стоплист:

```php
$sms->stoplistAdd($phone, $text);
```

Удалить номер из стоп-листа:

```php
$sms->stoplistDel($phone);
```

Получить номера стоплиста:

```php
$sms->stoplistGet();
```

## Автор

[Александр Зеленин](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)
