<?php

require_once 'vendor/autoload.php';

$sms = new \Zelenin\smsru( 'api_id', 'login', 'password' );
// $sms = new \Zelenin\smsru( 'api_id' );
// $sms = new \Zelenin\smsru( null, 'login', 'password' );

// $result = $sms->sms_send( '79112223344', 'Текст SMS' );

// $result = $sms->sms_send( '79112223344,79115556677,79118889900', 'Текст SMS' );

// $result = $sms->sms_send( '79112223344', 'Текст SMS', 'Имя отправителя', time(), $translit = false, $test = true, $partner_id );

//$messages = array(
//	array( '79112223344', 'Текст СМС' ),
//	array( '79115556677', 'Текст СМС' )
//);
// $sms->multi_sms_send( $messages, 'Имя отправителя', time(), $translit = false, $test = true, $partner_id );

// $result = $sms->sms_mail( '79112223344', 'Текст SMS' );

// $result = $sms->sms_mail( '79112223344', 'Текст SMS', 'Имя отправителя' );

// $result = $sms->sms_status( 'SMS id' );

// $result = $sms->sms_cost( '79112223344', 'Текст SMS' );

// $result = $sms->my_balance();

// $result = $sms->my_limit();

// $result = $sms->my_senders();

// $result = $sms->auth_check();

// $result = $sms->stoplist_add( '79112223344', 'ban' );

// $result = $sms->stoplist_get();

// $result = $sms->stoplist_del( '79112223344' );

echo '<pre>';

print_r( $result );