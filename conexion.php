<?php

function connect() {
	$enLocal = true;

	$host = $enLocal ? "127.0.0.1" : "qxc430.queserademi.com";
	$database = $enLocal ? "qsdm" : "qxc430";
	$user = $enLocal ? "root" : "qxc430";
	$password = $enLocal ? "" : "Qsdm2017";
	$arrayPDO = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
    return new PDO('mysql:host='.$host.';dbname='.$database, $user, $password, $arrayPDO);
}
$pdo = connect();

function getNombreLimpio($string) {
	$signos_para_eliminar = array("'",'"',",",";","(",")","~","+");
	// eliminar acentos y signos, 
	$string = str_replace($signos_para_eliminar, '', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', trim(mb_strtolower($string, 'UTF-8'))));
	// y convertir underscore y slash en espacios
    return str_replace(array('_', '/'), ' ', $string);
}

// CONFIG GENERADOR_ESTATICAS
$_COUNT_FROM = 0;
$_TOTAL_PROFESSION = 8254;


// CONFIG GENERADOR_SITEMAP
$_LIMIT = $_TOTAL_PROFESSION / 2;
?>