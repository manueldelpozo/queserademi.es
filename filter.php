<?php

require('conexion.php');

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	$salario_from 	= $_GET['salario_from'];
	$salario_to 	= $_GET['salario_to'];

	$sql = "";
	$lista = array();
	$cod_exception = "LENGTH(p.cod) = 4 AND p.cod >= 2000";

	if (isset($salario_from) && isset($salario_to)) {
		$sql = "
		SELECT nombre_ppal 
		FROM profesiones p INNER JOIN salarios s ON p.id = s.id_profesion 
		WHERE s_princ_med > '$salario_from' AND s_princ_med < '$salario_to' AND " . $cod_exception . "
		ORDER BY s_princ_med DESC";
	}

	$request = $pdo->prepare($sql);
	$request->execute();
	$count = $request->rowCount();
	
	if ($count > 0) {
		$rows = $request->fetchAll();
		foreach ( $rows as $row ) {
			$lista[] = ucfirst( mb_strtolower( $row['nombre_ppal'], 'UTF-8' ) );
			/*if (!empty($row['nombre_alt']) && !is_null($row['nombre_alt'])) {
				$nombre_alt = ucfirst( mb_strtolower( $row['nombre_alt'], 'UTF-8' ) );
				if (strlen($row['nombre_alt']) < 5 && mb_strtoupper($row['nombre_alt'], 'UTF-8') == $row['nombre_alt'])
					$nombre_alt = $row['nombre_alt']; // solo si son siglas
				$lista[] = $nombre_alt;
			}*/
		}
	}

	echo json_encode($lista);
}

die();

?>