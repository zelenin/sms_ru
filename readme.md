# sms_ru

PHP-класс для работы с api сервиса [sms.ru](http://sms.ru)

## Использование

Подключение класса:

    require_once 'src/Zelenin/smsru.php';

Простая авторизация (с помощью api_id):

    $sms = new \Zelenin\smsru( $api_id );

Усиленная авторизация (с помощью api_id, логина и пароля):

    $sms = new \Zelenin\smsru( $api_id, $login, $password );

Усиленная авторизация (с помощью логина и пароля):

	$sms = new \Zelenin\smsru( null, $login, $password );

Отправка SMS:

    $sms->sms_send( '79112223344', 'Текст SMS' );
	$sms->sms_send( '79112223344,79115556677,79118889900', 'Текст SMS' );
	$sms->sms_send( '79112223344', 'Текст SMS', 'Имя отправителя', time(), $translit = false, $test = true, $partner_id );

	$messages = array(
		array( '79112223344', 'Текст СМС' ),
		array( '79115556677', 'Текст СМС' )
	);
	$sms->multi_sms_send( $messages, 'Имя отправителя', time(), $translit = false, $test = true, $partner_id );

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

## Автор

[Александр Зеленин](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)