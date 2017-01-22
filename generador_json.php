<?php
require('conexion.php');

$tipos = array('profesiones_test', 'formaciones');

foreach ($tipos as &$tipo) {
	$lista = array();
	$n_ppal = ($tipo == 'formaciones') ? 'f_nombre_ppal' : 'nombre_ppal';
	$n_alt = ($tipo == 'formaciones') ? 'f_nombre_alt' : 'nombre_alt';
	$join = ($tipo == 'profesiones_test') ? 'p INNER JOIN nombres_alt n ON p.id = n.id_profesion ' : '';
	$sql = "SELECT $n_ppal, $n_alt FROM $tipo " . $join . "ORDER BY $n_ppal ASC";

	$request = $pdo->prepare($sql);
	$request->execute();
	$count = $request->rowCount();
	
	if ($count > 0) {
		$rows = $request->fetchAll();
		foreach ($rows as $row) {
			$nombre_ppal = trim(ucfirst(mb_strtolower($row[$n_ppal], 'UTF-8')));
			if (!in_array($nombre_ppal, $lista))
				$lista[] = $nombre_ppal;
			if (!empty($row[$n_alt]) && !is_null($row[$n_alt])) {
				$nombre_alt = trim(ucfirst(mb_strtolower($row[$n_alt], 'UTF-8')));
				if (strlen($row[$n_alt]) < 5 && mb_strtoupper($row[$n_alt], 'UTF-8') == $row[$n_alt])
					$nombre_alt = trim($row[$n_alt]); // solo si son siglas
				if (!in_array($nombre_ppal, $lista))
					$lista[] = $nombre_alt;
			}
		}
	} 

	$file = 'data/' . $tipo . '.json';
	file_put_contents($file, json_encode($lista, JSON_UNESCAPED_UNICODE));
}

echo 'DONE';
?>