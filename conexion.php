<?php

function connect() {
	$host = "127.0.0.1";
	$database = "qsdm";
	$user = "root";
	$password = "";
	$arrayPDO = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
    return new PDO('mysql:host='.$host.';dbname='.$database, $user, $password, $arrayPDO);
}
$pdo = connect();

function getNombreLimpio($string) {
	$signos_para_eliminar = array("'",'"',",",";","(",")","/","~","+");
	// eliminar acentos y signos, yconvertir underscore en espacios
    return str_replace('_', ' ', str_replace($signos_para_eliminar, '', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', trim(mb_strtolower($string, 'UTF-8')))));
}

?>