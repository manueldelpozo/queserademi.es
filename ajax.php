<?php

require('conexion.php');

if( isset( $_GET['query'] ) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {

	$query = $_GET['query'];
	$tipo = $_GET['tipo'];
	$sql = "SELECT nombre_ppal FROM $tipo ";
	$lista = array();

	if( $query != '%25' )
		$sql .= "WHERE nombre_ppal LIKE '%$query%' LIMIT 0,15";	
	else
		$sql .= "ORDER BY nombre_ppal ASC";

	$request = $pdo->prepare($sql);
	$request->execute();
	$count = $request->rowCount();
	
	if( $count > 0 ) {
		$rows = $request->fetchAll();
		foreach ( $rows as $row ) {
			$nombre_ppal = ucfirst( mb_strtolower( $row['nombre_ppal'], 'UTF-8' ) );
			$lista[] = $nombre_ppal;
		}
	}
	echo json_encode($lista);
}
die();
/*
if( isset( $_GET['keyword'] ) && isset( $_GET['estudios_asoc'] ) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {

	$keyword = $_GET['keyword'];
	$consultar_estudios = $_GET['estudios_asoc'];
	$keyword = addslashes( $keyword );
	$keyword = trim( $keyword );
	$keyword = preg_replace( '/--+/', '-', $keyword );
	$output = "";
	$lista = array();

	$item1 = 'profesion';
	$item2 = 'estudios_asoc';

	if( $consultar_estudios == 1 ) {
		$item1 = 'estudios_asoc';
		$item2 = 'profesion';
	}

	if( !$keyword == "" ) { 

		//$sql="SELECT * FROM profesiones WHERE ".$item1." LIKE '$keyword%'
		//UNION distinct SELECT * FROM profesiones WHERE ".$item1." LIKE '%$keyword%' OR ".$item2." LIKE '%$keyword%'";
		$sql = " SELECT * FROM profesiones WHERE ".$item1." LIKE '$keyword%'
		UNION distinct SELECT * FROM profesiones WHERE ".$item1." LIKE '%$keyword%' ";
		//$sql = " SELECT * FROM profesiones WHERE ".$item1." LIKE '%$keyword%'";

		if( $keyword == '%' ) {
			$sql.= 'ORDER BY '.$item1.' ASC';	
		}

		$query = $pdo->prepare($sql);
		$query->bindParam(':keyword', $keyword, PDO::PARAM_STR);
		$query->execute();
		$count = $query->rowCount();
		
		if( $count > 0 ) {
			$list = $query->fetchAll();
			foreach ( $list as $rs ) {
				$item_name = ucfirst( mb_strtolower( $rs[$item1], 'UTF-8' ) );
				// Hacer otro test case de la primera letra
				//if( ctype_lower ( mb_substr( $item_name, 0, 1, 'utf-8') ) ) {
					//$item_name = mb_strtoupper( mb_substr( $item_name, 0, 1, 'utf-8') ) . mb_substr( $item_name, 1, strlen($item_name)-1, 'utf-8') ;
				//}

				$cod = $rs['cod'];
				// Imprimir solo si tiene contenido
				if( !empty($item_name) ) {
					$clase = "hijo";
					
					if( $cod < 10000 ) {
						$clase = "padre";

						if( $cod < 1000 ) {
							$clase = "abuelo";
							
							if( $cod < 100 ) {
								$clase = "bisabuelo";
								
								if( $cod < 10 ) {
									$clase = "tatarabuelo";
									//$tat = $cod;
									//$lista[$tat] = $item_name;
									//continue;
								}
								//$tat = intval( $cod / 10 );
								//$bis = $cod - $tat * 10;
								//$lista[$tat][$bis] = $item_name;
								//continue; 		
							}
							//$tat = intval( $cod / 100 );
							//$bis = intval( $cod / 10 ) - $tat * 10;
							//$abu = $cod - $bis * 10 - $tat * 100 ;
							//$lista[$tat][$bis][$abu] = $item_name;
							//continue;
						}
						//$tat = intval( $cod / 1000 );
						//$bis = intval( $cod / 100 ) - $tat * 10;
						//$abu = intval( $cod / 10 ) - $bis * 10 - $tat * 100;
						//$pad = $cod - $abu * 10 - $bis * 100 - $tat * 1000;
						//$lista[$tat][$bis][$abu][$pad] = $item_name;
						//continue;
					}
					//$tat = intval( $cod / 10000000 );
					//$bis = intval( $cod / 1000000 ) - $tat * 10;
					//$abu = intval( $cod / 100000 ) - $bis * 10 - $tat * 100;
					//$pad = intval( $cod / 10000 ) - $abu * 10 - $bis * 100 - $tat * 1000;
					//////$hij = $cod - $pad * 10000 - $abu * 100000 - $bis * 1000000 - $tat * 10000000;
					//$lista[$tat][$bis][$abu][$pad][] = $item_name;
					//continue;
					$output .= '<li role="presentation" class="'.$clase.'"><a class="search-option" role="menuitem" href="#">'.$item_name.'</a></li>';
				}
			}			
		} else {
			// Si el item no existe en la BBDD
			$output .= '<li style="color:black" role="presentation"><a class="search-option" role="menuitem" href="#">Elemento no encontrado</a></li>';
		}
	} else {
		// Si no hay texto en el input
		$output .= '<li style="color:black" role="presentation"><a class="search-option" role="menuitem" href="#">Campo sin rellenar</a></li>';
	}
	echo $output;
	//echo json_encode($lista);
}
*/
?>