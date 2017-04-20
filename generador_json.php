<?php
require('conexion.php');

$tipos = array('profesiones', 'formaciones');

function getExtraTokens($nombre_userfriendly, $nombre_formal) {
	$tokens = array($nombre_userfriendly, $nombre_formal);
	$extraTokens = array_reverse(explode(' ', $nombre_userfriendly));
	$diff = ['a', 'de', 'del', 'y', ',', '.', 'para', 'la', 'las', 'el', 'los', 'en', 'o'];
	$extraTokens = array_diff($extraTokens, $diff);
	return array_merge($extraTokens, $tokens);
}

foreach ($tipos as &$tipo) {
	$lista = array();
	$n_ppal = ($tipo == 'formaciones') ? 'f_nombre_ppal' : 'nombre_ppal';
	$n_alt = ($tipo == 'formaciones') ? 'f_nombre_alt' : 'nombre_alt';
	$join = ($tipo == 'profesiones') ? 'p INNER JOIN nombres_alt n ON p.id = n.id_profesion ' : '';
	$sql = "SELECT $n_ppal, $n_alt FROM $tipo " . $join . "ORDER BY $n_ppal ASC";

	$request = $pdo->prepare($sql);
	$request->execute();
	$count = $request->rowCount();
	
	if ($count > 0) {
		$rows = $request->fetchAll();
		foreach ($rows as $row) {
			$nombre_ppal = array();
			$nombre_ppal_formal = ucfirst(trim(mb_strtolower($row[$n_ppal], 'UTF-8'))); //primera mayusc
			$nombre_ppal_userfriendly = getNombreLimpio($row[$n_ppal]);
			$nombre_ppal['value'] = $nombre_ppal_formal;
			$nombre_ppal['tokens'] = getExtraTokens($nombre_ppal_userfriendly, $nombre_ppal_formal);

			if (!array_key_exists($nombre_ppal_userfriendly, $lista)) {
				$lista[$nombre_ppal_userfriendly] = $nombre_ppal;
				echo '<p>El nombre principal <strong>' . $nombre_ppal_formal . '</strong> se añadio a la lista de <strong>' . $tipo . '</strong></p>';
			}

			if (!empty($row[$n_alt]) && !is_null($row[$n_alt])) {
				$nombre_alt = array();
				$son_siglas = strlen($row[$n_alt]) < 5 && mb_strtoupper($row[$n_alt], 'UTF-8') === $row[$n_alt];
				$nombre_alt_formal = ucfirst(trim($son_siglas ? $row[$n_alt] : mb_strtolower($row[$n_alt], 'UTF-8'))); //primera mayusc
				$nombre_alt_userfriendly = getNombreLimpio($row[$n_alt]);
				$nombre_alt['value'] = $nombre_alt_formal;
				$nombre_alt['tokens'] = getExtraTokens($nombre_alt_userfriendly, $nombre_alt_formal);
				
				if (!array_key_exists($nombre_alt_userfriendly, $lista)) {
					$lista[$nombre_alt_userfriendly] = $nombre_alt;
					echo '<p>El nombre alternativo <strong>' . $nombre_alt_formal . '</strong> se añadio a la lista de <strong>' . $tipo . '</strong></p>';
				}
			}
		}
	} 

	$file = 'data/' . $tipo . '.json';
	file_put_contents($file, json_encode($lista, JSON_UNESCAPED_UNICODE));
}

echo 'DONE';
?>