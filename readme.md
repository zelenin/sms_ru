# sms_ru

PHP-класс для работы с api сервиса [sms.ru](http://sms.ru)

## Использование

Подключение класса:

    require_once( 'smsru.php' );

Простая авторизация:

    $sms = new \zelenin\smsru( $api_id );

Усиленная авторизация:

    $sms = new \zelenin\smsru( $api_id, $login, $pwd );

Усиленная авторизация с логином и паролем:

	$sms = new \zelenin\smsru( null, $login, $pwd );

Отправка SMS:

    $sms->sms_send( '79112223344', 'Текст SMS' );
	$sms->sms_send( '79112223344,79115556677,79118889900', 'Текст SMS' );
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

Добавить номер в стоплист:

	$sms->stoplist_add( '79112223344', 'Примечание' );

Получить номера стоплиста:

	$sms->stoplist_get();

Удалить номер из стоп-листа:

	$sms->stoplist_del( '79112223344' );

## Author

[Александр Зеленин](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)