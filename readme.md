# sms_ru

PHP-класс для работы с api сервиса [sms.ru](http://sms.ru)

## Установка

### Установка через Composer

Запустите

```
php composer.phar require zelenin/smsru "dev-master"
```

или добавьте

```js
"zelenin/smsru": "~3"
```

в секцию ```require``` вашего composer.json

## Использование

Простая авторизация (с помощью api_id):

```php
$sms = new \Zelenin\Smsru();
$sms->setApiId($apiId);
```

Усиленная авторизация (с помощью api_id, логина и пароля):

```php
$sms = new \Zelenin\Smsru();
$sms->setApiId($apiId);
$sms->setLogin($login);
$sms->setPassword($password);
```

Усиленная авторизация (с помощью логина и пароля):

```php
$sms = new \Zelenin\Smsru();
$sms->setLogin($login);
$sms->setPassword($password);
```

Отправка SMS:

```php
$sms->smsSend('79112223344', 'Текст SMS');
$sms->smsSend('79112223344,79115556677,79118889900', 'Текст SMS');
$sms->smsSend('79112223344', 'Текст SMS', 'Имя отправителя', time(), $translit = false, $test = true, $partner_id);

$messages = [
    ['79112223344', 'Текст СМС'],
    ['79115556677', 'Текст СМС']
];
$sms->multiSmsSend($messages, 'Имя отправителя', time(), $translit = false, $test = true, $partner_id);
```

Отправка SMS через e-mail:

```php
$sms->smsMail('79112223344', 'Текст SMS');
$sms->smsMail('79112223344', 'Текст SMS', 'Имя отправителя');
```

Статус SMS:

```php
$sms->smsStatus('SMS id');
```

Стоимость SMS:

```php
$sms->smsCost('79112223344', 'Текст SMS');
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
$sms->stoplistAdd('79112223344', 'Примечание');
```

Удалить номер из стоп-листа:

```php
$sms->stoplistDel('79112223344');
```

Получить номера стоплиста:

```php
$sms->stoplistGet();
```

## Автор

[Александр Зеленин](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)
