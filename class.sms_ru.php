<?php

/**
 * Class for sms.ru
 * @package sms_ru
 * @author Aleksandr Zelenin <aleksandr@zelenin.me>
 * @link https://github.com/zelenin/sms_ru
 * @version 1.0
 * @license http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 */


class sms_ru {

	const HOST  = 'http://sms.ru/';
	const SEND = 'sms/send?';
	const STATUS = 'sms/status?';
	const COST = 'sms/cost?';
	const BALANCE = 'my/balance?';
	const LIMIT = 'my/limit?';
	const SENDERS = 'my/senders?';
	const GET_TOKEN = 'auth/get_token';
	const CHECK = 'auth/check?';

	private $api_id;
	private $login;
	private $pwd;

	private $token;
	private $sha512;
	private $strong_auth = false;

	protected $response_code = array(

		'send' => array(
			'100' => 'Message is accepted to send',
			'200' => 'Incorrect api_id',
			'201' => 'Not enough money',
			'202' => 'Incorrect recipient',
			'203' => 'No text messages',
			'204' => 'The name of the sender is not agreed with the administration',
			'205' => 'The message is too long (more than 5 SMS)',
			'206' => 'Exceeded the daily limit for sending messages',
			'207' => 'On this number can not send messages',
			'208' => 'Time parameter is incorrect',
			'210' => 'Use GET, POST should be used where',
			'211' => 'The method was not found',
			'220' => 'Service is temporarily unavailable, please try later',
			'300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился)',
			'301' => 'Неправильный пароль, либо пользователь не найден',
			'302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс)'
		),

		'status' => array(
			'-1' => 'Message not found',
			'100' => 'Message in the queue',
			'101' => 'Message to the operator',
			'102' => 'Your post (in transit)',
			'103' => 'Message delivered',
			'104' => 'Can not be reached: the lifetime has expired',
			'105' => 'Can not be reached: Removed from operator',
			'106' => 'Can not be reached: failed to phone',
			'107' => 'Can not be reached: unknown cause',
			'108' => 'Can not be reached: rejected',
			'200' => 'Incorrect api_id',
			'210' => 'Use GET, POST should be used where',
			'211' => 'The method was not found',
			'220' => 'Service is temporarily unavailable, please try later',
			'300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился)',
			'301' => 'Неправильный пароль, либо пользователь не найден',
			'302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс)'
		),

		'cost' => array(
			'100' => 'Request is made',
			'200' => 'Incorrect api_id',
			'202' => 'Неправильно указан получатель',
			'207' => 'На этот номер нельзя отправлять сообщения',
			'210' => 'Use GET, POST should be used where',
			'211' => 'The method was not found',
			'220' => 'Service is temporarily unavailable, please try later',
			'300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился)',
			'301' => 'Неправильный пароль, либо пользователь не найден',
			'302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс)'
		),

		'balance' => array(
			'100' => 'Request is made',
			'200' => 'Incorrect api_id',
			'210' => 'Use GET, POST should be used where',
			'211' => 'The method was not found',
			'220' => 'Service is temporarily unavailable, please try later',
			'300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился)',
			'301' => 'Неправильный пароль, либо пользователь не найден',
			'302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс)'
		),

		'limit' => array(
			'100' => 'Request is made',
			'200' => 'Incorrect api_id',
			'210' => 'Use GET, POST should be used where',
			'211' => 'The method was not found',
			'220' => 'Service is temporarily unavailable, please try later',
			'300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился)',
			'301' => 'Неправильный пароль, либо пользователь не найден',
			'302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс)'
		),

		'check' => array(
			'100' => 'Number and password are the same',
			'300' => 'Invalid token (may have expired, or your IP has changed)',
			'301' => 'Wrong password or user not found',
			'302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс)'
		)

	);

	function  __construct( $api_id, $login = null, $pwd = null ) {
		$this->api_id = $api_id;
		$this->login = $login;
		$this->pwd = $pwd;
	}

	public function sms_send( $to, $text, $from = null, $time = null, $test = false, $partner_id = null ) {

		$url = self::HOST . self::SEND;
		$this->id = null;

		$params = $this->get_auth_params();
		$params['to'] = $to;
		$params['text'] = $text;

		if ( $from )
			$params['from'] = $from;

		if ( $time && $time < ( time() + 7 * 60 * 60 * 24 ) )
			$params['time'] = $time;

		if ( $test )
			$params['test'] = 1;

		if ( $partner_id )
			$params['partner_id'] = $partner_id;

		$result = $this->curl( $url, $params );
		$result = explode( "\n", $result );

		$response = array(
			'code' => $result[0],
			'id' => $result[1],
			'balance' => str_replace( 'balance=', '', $result[2] )
		);
		return $response;

	}

	public function sms_mail( $to, $text, $from = null ) {

		$mail = $this->api_id . '@sms.ru';
		$subject = isset( $from ) ? $to . ' from:' . $from : $to;
		$headers = 'Content-Type: text/html; charset=UTF-8';

		$response = mail( $mail, $subject, $text, $headers );
		return $response;

	}

	public function sms_status( $id ) {

		$url = self::HOST . self::STATUS;

		$params = $this->get_auth_params();
		$params['id'] = $id;
		$result = $this->curl( $url, $params );

		return $result;

	}

	// encoding

	public function sms_cost( $to, $text ) {

		$url = self::HOST . self::COST;
		$this->id = null;

		$params = $this->get_auth_params();
		$params['to'] = $to;
		$params['text'] = $text;

		$result = $this->curl( $url, $params );
		$result = explode( "\n", $result );

		$response = array(
			'code' => $result[0],
			'price' => $result[1],
			'number' => $result[2]
		);
		return $response;

	}

	public function my_balance() {

		$url = self::HOST . self::BALANCE;

		$params = $this->get_auth_params();
		$result = $this->curl( $url, $params );
		$result = explode( "\n", $result );

		$response = array(
			'code' => $result[0],
			'balance' => $result[1]
		);
		return $response;

	}

	public function my_limit() {

		$url = self::HOST . self::LIMIT;

		$params = $this->get_auth_params();
		$result = $this->curl( $url, $params );
		$result = explode( "\n", $result );

		$response = array(
			'code' => $result[0],
			'total' => $result[1],
			'current' => $result[2]
		);
		return $response;

	}

	public function my_senders() {

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

	private function auth_get_token() {

		$url = self::HOST . self::GET_TOKEN;
		$this->token = $this->curl( $url );
		return $this->token;

	}

	public function auth_check() {

		$url = self::HOST . self::CHECK;
		$params = $this->get_auth_params();
		$result = $this->curl( $url, $params );

		return $result;

	}

	private function get_sha512() {
		$this->sha512 = hash( 'sha512', $this->pwd . $this->token . $this->api_id );
	}

	private function curl( $url, $params = array() ) {

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

	private function get_auth_params() {

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

	public function translit( $text ) {

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

?>