# sms_ru

PHP-класс для работы с api сервиса [sms.ru](http://sms.ru)

## Использование

Подключение класса:

    require_once( 'class.sms_ru.php' );

Простая авторизация:

    $sms = new zelenin\sms_ru( $api_id );

Усиленная авторизация:

    $sms = new zelenin\sms_ru( $api_id, $login = null, $pwd = null );

Отправка SMS:

    $sms->sms_send( '79112223344', 'Текст SMS' );
	$sms->sms_send( '79112223344', 'Текст SMS', 'Имя отправителя', time(), $test = true, $partner_id );

Отправка SMS через e-mail:

    $sms->sms_mail( '79112223344', 'Текст SMS' );
	$sms->sms_mail( '79112223344', 'Текст SMS', 'Имя отправителя' );

Статус SMS:

    $sms->sms_status( 'SMS id' );

Стоимость SMS:

    $sms->sms_cost( '79112223344', 'Текст SMS' );

Баланс:

    $sms->my_balance();

Дневной лимит:

    $sms->my_limit();

Отправители:

    $sms->my_senders();

Проверка валидности логина и пароля:

    $sms->auth_check();

## Author

[Александр Зеленин](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)