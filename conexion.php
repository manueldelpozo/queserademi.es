<?php

function connect() {
	$isTest = false;

	$host = $isTest ? "127.0.0.1" : "qxc430.queserademi.com";
	$database = $isTest ? "qsdm" : "qxc430";
	$user = $isTest ? "root" : "qxc430";
	$password = $isTest ? "" : "Qsdm2017";
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