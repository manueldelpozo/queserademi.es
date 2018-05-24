<?php

require('conexion.php');


if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	$sql = "";
	$lista = array();
	$cod_exception = "LENGTH(p.cod) = 4 AND p.cod >= 2000";

	if (isset($_GET['salario_from']) && isset($_GET['salario_to']) && isset($_GET['empleabilidad_from']) && isset($_GET['empleabilidad_to'])) {
		$salario_from 		= $_GET['salario_from'];
		$salario_to 		= $_GET['salario_to'];
		$empleabilidad_from = $_GET['empleabilidad_from'];
		$empleabilidad_to 	= $_GET['empleabilidad_to'];
		$sql = "
		SELECT p.nombre_ppal
		FROM profesiones p INNER JOIN salarios s ON p.id = s.id_profesion INNER JOIN empleabilidad e ON p.id = e.id_profesion
		WHERE s.s_princ_med > '$salario_from' AND s.s_princ_med < '$salario_to' 
		AND e.paro > '$empleabilidad_from' AND e.paro < '$empleabilidad_to' 
		AND e.mes LIKE 'abril' AND e.anyo LIKE 2017 
		AND " . $cod_exception . "
		ORDER BY e.empleabilidad DESC";
	}

	$request = $pdo->prepare($sql);
	$request->execute();
	$count = $request->rowCount();
	
	if ($count > 0) {
		$rows = $request->fetchAll();
		foreach ( $rows as $row ) {
			$lista[] = ucfirst( mb_strtolower( $row['nombre_ppal'], 'UTF-8' ) );
		}
	}

	echo json_encode($lista);
}

die();

?>