<?php

function connect() {
	$host = "qth809.queserademi.es";
	$database = "qth809";
	$user = "qth809";
	$password = "Qsdm2015";
	$arrayPDO = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
    return new PDO('mysql:host='.$host.';dbname='.$database, $user, $password, $arrayPDO);
}
$pdo = connect();

?>