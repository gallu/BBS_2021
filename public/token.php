<?php   // token.php

$token = bin2hex( random_bytes(24) );
var_dump($token);
//
$token = base64_encode( random_bytes(24) );
var_dump($token);

//
$token = bin2hex( openssl_random_pseudo_bytes(24) );
var_dump($token);

var_dump( uniqid() );
var_dump( uniqid() );
var_dump( uniqid() );

var_dump( uniqid(mt_rand().'_') );
var_dump( uniqid(mt_rand().'_') );
var_dump( uniqid(mt_rand().'_') );

var_dump( md5(uniqid(mt_rand().'_')) );
var_dump( md5(uniqid(mt_rand().'_')) );
var_dump( md5(uniqid(mt_rand().'_')) );

