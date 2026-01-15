<?php

$serverinimi='localhost';
$kasutajanimi='igor';
$parool='1234';
$andmebaasinimi='valimised';
$yhendus=new mysqli($serverinimi,$kasutajanimi,$parool,$andmebaasinimi);
$yhendus->set_charset('utf8');

//$serverinimi='d141126.mysql.zonevs.eu';
//$kasutajanimi='d141126_igor';
//$parool='1448ff12Jpa';
//$andmebaasinimi='d141126_baasphp';
//$yhendus=new mysqli($serverinimi,$kasutajanimi,$parool,$andmebaasinimi);
//$yhendus->set_charset('utf8');


