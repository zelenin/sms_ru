<?php

/**
 * Class for sms.ru
 * @package smsru
 * @author  Aleksandr Zelenin <aleksandr@zelenin.me>
 * @link    https://github.com/zelenin/sms_ru
 * @version 1.1.3
 * @license http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 */

namespace Zelenin;

class smsru
{
	const HOST = 'http://sms.ru/';
	const SEND = 'sms/send?';
	const STATUS = 'sms/status?';
	const COST = 'sms/cost?';
	const BALANCE = 'my/balance?';
	const LIMIT = 'my/limit?';
	const SENDERS = 'my/senders?';
	const GET_TOKEN = 'auth/get_token';
	const CHECK = 'auth/check?';
	const ADD = 'stoplist/add?';
	const DEL = 'stoplist/del?';
	const GET = 'stoplist/get?';
	const UCS = 'sms/ucs?';
	private $api_id;
	private $login;
	private $pwd;
	private $token;
	private $sha512;
	protected $response_code = array(

		'send' => array(
			'100' => 'Сообщение принято к отправке. На следующих строчках вы найдете идентификаторы отправленных сообщений в том же порядке, в котором вы указали номера, на которых совершалась отправка.',
			'200' => 'Неправильный api_id',
			'201' => 'Не хватает средств на лицевом счету',
			'202' => 'Неправильно указан получатель',
			'203' => 'Нет текста сообщения',
			'204' => 'Имя отправителя не согласовано с администрацией',
			'205' => 'Сообщение слишком длинное (превышает 8 СМС)',
			'206' => 'Будет превышен или уже превышен дневной лимит на отправку сообщений',
			'207' => 'На этот номер (или один из номеров) нельзя отправлять сообщения, либо указано более 100 номеров в списке получателей',
			'208' => 'Параметр time указан неправильно',
			'209' => 'Вы добавили этот номер (или один из номеров) в стоп-лист',
			'210' => 'Используется GET, где необходимо использовать POST',
			'211' => 'Метод не найден',
			'220' => 'Сервис временно недоступен, попробуйте чуть позже.',
			'300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился)',
			'301' => 'Неправильный пароль, либо пользователь не найден',
			'302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс)'
		),

		'status' => array(
			'-1' => 'Сообщение не найдено.',
			'100' => 'Сообщение находится в нашей очереди',
			'101' => 'Сообщение передается оператору',
			'102' => 'Сообщение отправлено (в пути)',
			'103' => 'Сообщение доставлено',
			'104' => 'Не может быть доставлено: время жизни истекло',
			'105' => 'Не может быть доставлено: удалено оператором',
			'106' => 'Не может быть доставлено: сбой в телефоне',
			'107' => 'Не может быть доставлено: неизвестная причина',
			'108' => 'Не может быть доставлено: отклонено',
			'200' => 'Неправильный api_id',
			'210' => 'Используется GET, где необходимо использовать POST',
			'211' => 'Метод не найден',
			'220' => 'Сервис временно недоступен, попробуйте чуть позже.',
			'300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился)',
			'301' => 'Неправильный пароль, либо пользователь не найден',
			'302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс)'
		),

		'cost' => array(
			'100' => 'Запрос выполнен. На второй строчке будет указана стоимость сообщения. На третьей строчке будет указана его длина.',
			'200' => 'Неправильный api_id',
			'202' => 'Неправильно указан получатель',
			'207' => 'На этот номер нельзя отправлять сообщения',
			'210' => 'Используется GET, где необходимо использовать POST',
			'211' => 'Метод не найден',
			'220' => 'Сервис временно недоступен, попробуйте чуть позже.',
			'300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился)',
			'301' => 'Неправильный пароль, либо пользователь не найден',
			'302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс)'
		),

		'balance' => array(
			'100' => 'Запрос выполнен. На второй строчке вы найдете ваше текущее состояние баланса.',
			'200' => 'Неправильный api_id',
			'210' => 'Используется GET, где необходимо использовать POST',
			'211' => 'Метод не найден',
			'220' => 'Сервис временно недоступен, попробуйте чуть позже.',
			'300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился)',
			'301' => 'Неправильный пароль, либо пользователь не найден',
			'302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс)'
		),

		'limit' => array(
			'100' => 'Запрос выполнен. На второй строчке вы найдете ваше текущее дневное ограничение. На третьей строчке количество сообщений, отправленных вами в текущий день.',
			'200' => 'Неправильный api_id',
			'210' => 'Используется GET, где необходимо использовать POST',
			'211' => 'Метод не найден',
			'220' => 'Сервис временно недоступен, попробуйте чуть позже.',
			'300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился)',
			'301' => 'Неправильный пароль, либо пользователь не найден',
			'302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс)'
		),

		'senders' => array(
			'100' => 'Запрос выполнен. На второй и последующих строчках вы найдете ваших одобренных отправителей, которые можно использовать в параметре &from= метода sms/send.',
			'200' => 'Неправильный api_id',
			'210' => 'Используется GET, где необходимо использовать POST',
			'211' => 'Метод не найден',
			'220' => 'Сервис временно недоступен, попробуйте чуть позже.',
			'300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился)',
			'301' => 'Неправильный пароль, либо пользователь не найден',
			'302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс)'
		),

		'check' => array(
			'100' => 'ОК, номер телефона и пароль совпадают.',
			'300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился)',
			'301' => 'Неправильный пароль, либо пользователь не найден',
			'302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс)'
		),

		'add' => array(
			'100' => 'Номер добавлен в стоплист.',
			'202' => 'Номер телефона в неправильном формате'
		),

		'del' => array(
			'100' => 'Номер удален из стоплиста.',
			'202' => 'Номер телефона в неправильном формате'
		),

		'get' => array(
			'100' => 'Запрос обработан. На последующих строчках будут идти номера телефонов, указанных в стоплисте в формате номер;примечание.'
		)

	);

	public function  __construct( $api_id = null, $login = null, $pwd = null )
	{
		$this->api_id = $api_id;
		$this->login = $login;
		$this->pwd = $pwd;
	}

	public function sms_send( $to, $text, $from = null, $time = null, $test = false, $partner_id = null )
	{
		$url = self::HOST . self::SEND;
		$this->id = null;

		$params = $this->get_auth_params();
		$params['to'] = $to;
		$params['text'] = $text;

		if ( $from ) {
			$params['from'] = $from;
		}

		if ( $time && $time < ( time() + 7 * 60 * 60 * 24 ) ) {
			$params['time'] = $time;
		}

		if ( $test ) {
			$params['test'] = 1;
		}

		if ( $partner_id ) {
			$params['partner_id'] = $partner_id;
		}

		$result = $this->curl( $url, $params );
		$result = explode( "\n", $result );

		$response = array();

		$response['code'] = $result[0];
		unset( $result[0] );

		foreach ( $result as $id ) {
			if ( !preg_match( '/=/', $id ) ) {
				$response['ids'][] = $id;
			} else {
				$result = explode( '=', $id );
				$response[$result[0]] = $result[1];
			}
		}

		return $response;
	}

	public function sms_mail( $to, $text, $from = null )
	{
		$mail = $this->api_id . '@' . self::HOST;
		$subject = isset( $from ) ? $to . ' from:' . $from : $to;
		$headers = 'Content-Type: text/html; charset=UTF-8';
		return mail( $mail, $subject, $text, $headers );
	}

	public function sms_status( $id )
	{
		$url = self::HOST . self::STATUS;
		$params = $this->get_auth_params();
		$params['id'] = $id;
		return $this->curl( $url, $params );
	}

	public function sms_cost( $to, $text )
	{
		$url = self::HOST . self::COST;
		$this->id = null;

		$params = $this->get_auth_params();
		$params['to'] = $to;
		$params['text'] = $text;

		$result = $this->curl( $url, $params );
		$result = explode( "\n", $result );

		return array(
			'code' => $result[0],
			'price' => $result[1],
			'number' => $result[2]
		);
	}

	public function my_balance()
	{
		$url = self::HOST . self::BALANCE;
		$params = $this->get_auth_params();
		$result = $this->curl( $url, $params );
		$result = explode( "\n", $result );
		return array(
			'code' => $result[0],
			'balance' => $result[1]
		);
	}

	public function my_limit()
	{
		$url = self::HOST . self::LIMIT;
		$params = $this->get_auth_params();
		$result = $this->curl( $url, $params );
		$result = explode( "\n", $result );
		return array(
			'code' => $result[0],
			'total' => $result[1],
			'current' => $result[2]
		);
	}

	public function my_senders()
	{
		$url = self::HOST . self::SENDERS;
		$params = $this->get_auth_params();
		$result = $this->curl( $url, $params );
		$result = explode( "\n", rtrim( $result ) );

		$response = array(
			'code' => $result[0],
			'senders' => $result
		);
		unset( $response['senders'][0] );
		$response['senders'] = array_values( $response['senders'] );
		return $response;
	}

	private function auth_get_token()
	{
		$url = self::HOST . self::GET_TOKEN;
		$this->token = $this->curl( $url );
		return $this->token;
	}

	public function auth_check()
	{
		$url = self::HOST . self::CHECK;
		$params = $this->get_auth_params();
		return $this->curl( $url, $params );
	}

	public function stoplist_add( $stoplist_phone, $stoplist_text )
	{
		$url = self::HOST . self::ADD;
		$params = $this->get_auth_params();
		$params['stoplist_phone'] = $stoplist_phone;
		$params['stoplist_text'] = $stoplist_text;
		return $this->curl( $url, $params );
	}

	public function stoplist_del( $stoplist_phone )
	{
		$url = self::HOST . self::DEL;
		$params = $this->get_auth_params();
		$params['stoplist_phone'] = $stoplist_phone;
		return $this->curl( $url, $params );
	}

	public function stoplist_get()
	{
		$url = self::HOST . self::GET;
		$params = $this->get_auth_params();
		$result = $this->curl( $url, $params );

		$result = explode( "\n", rtrim( $result ) );
		$response = array(
			'code' => $result[0],
			'stoplist' => $result
		);
		for ( $i = 1; $i < count( $response['stoplist'] ); $i++ ) {
			$result = explode( ';', $response['stoplist'][$i] );
			$stoplist[$i - 1]['number'] = $result[0];
			$stoplist[$i - 1]['note'] = $result[1];
		}
		$response['stoplist'] = $stoplist;
		return $response;
	}

	public function sms_ucs()
	{
		$url = self::HOST . self::UCS;
		$params = $this->get_auth_params();
		return $this->curl( $url, $params );
	}

	private function get_sha512()
	{
		if ( !$this->api_id || empty( $this->api_id ) ) {
			$this->sha512 = hash( 'sha512', $this->pwd . $this->token );
		} else {
			$this->sha512 = hash( 'sha512', $this->pwd . $this->token . $this->api_id );
		}
	}

	private function curl( $url, $params = array() )
	{
		$ch = curl_init( $url );
		$options = array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_POSTFIELDS => $params
		);
		curl_setopt_array( $ch, $options );
		$result = curl_exec( $ch );
		curl_close( $ch );

		return $result;
	}

	private function get_auth_params()
	{
		if ( !empty( $this->login ) && !empty( $this->pwd ) ) {

			$this->auth_get_token();
			$this->get_sha512();

			$params['login'] = $this->login;
			$params['token'] = $this->token;
			$params['sha512'] = $this->sha512;
		} else {
			$params['api_id'] = $this->api_id;
		}

		return $params;
	}

	public function translit( $text )
	{
		$iso9 = array(
			'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'J',
			'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
			'Х' => 'H', 'Ц' => 'Ts', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Shh', 'Ъ' => '``', 'Ы' => 'Y`', 'Ь' => '`', 'Э' => 'E`', 'Ю' => 'YU', 'Я' => 'YA',

			'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'j',
			'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f',
			'х' => 'h', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shh', 'ъ' => '``', 'ы' => 'y`', 'ь' => '`', 'э' => 'e`', 'ю' => 'yu', 'я' => 'ya'
		);
		return strtr( $text, $iso9 );
	}
}