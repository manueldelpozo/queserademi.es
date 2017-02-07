<?php

function connect() {
	$host = "qxc430.queserademi.com";
	$database = "qxc430";
	$user = "qxc430";
	$password = "Qsdm2017";
	$arrayPDO = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
    return new PDO('mysql:host='.$host.';dbname='.$database, $user, $password, $arrayPDO);
}
$pdo = connect();

?>