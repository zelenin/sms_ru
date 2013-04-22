<?php

require_once 'lib/Zelenin/smsru.php';

$sms = new \Zelenin\smsru( 'api_id', 'login', 'password' );
// $sms = new \Zelenin\smsru( 'api_id' );
// $sms = new \Zelenin\smsru( null, 'login', 'password' );

// $sms->sms_send( '79112223344', 'Текст SMS' );

// $sms->sms_send( '79112223344,79115556677,79118889900', 'Текст SMS' );

// $sms->sms_send( '79112223344', 'Текст SMS', 'Имя отправителя', time(), $translit = false, $test = true, $partner_id );

$messages = array(
	array( '79112223344', 'Текст СМС' ),
	array( '79115556677', 'Текст СМС' )
);
$sms->multi_sms_send( $messages, 'Имя отправителя', time(), $translit = false, $test = true, $partner_id );

// $sms->sms_mail( '79112223344', 'Текст SMS' );

// $sms->sms_mail( '79112223344', 'Текст SMS', 'Имя отправителя' );

// $sms->sms_status( 'SMS id' );

// $sms->sms_cost( '79112223344', 'Текст SMS' );

// $sms->my_balance();

// $sms->my_limit();

// $sms->my_senders();

// $sms->auth_check();

// $sms->stoplist_add( '79112223344', 'ban' );

// $sms->stoplist_get();

// $sms->stoplist_del( '79112223344' );