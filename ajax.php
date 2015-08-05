<?php

require('conexion.php');

if( isset( $_GET['keyword'] ) && isset( $_GET['estudios_asoc'] ) ) {

	$keyword = $_GET['keyword'];
	$consultar_estudios = $_GET['estudios_asoc'];
	$keyword = addslashes( $keyword );
	$keyword = trim( $keyword );
	$keyword = preg_replace( '/--+/', '-', $keyword );

	$item1 = 'profesion';
	$item2 = 'estudios_asoc';

	if( $consultar_estudios == 1 ) {
		$item1 = 'estudios_asoc';
		$item2 = 'profesion';
	}

	if( !$keyword == "" ) { 

		$sql="SELECT *
		FROM profesiones
		WHERE ".$item1." LIKE '$keyword%'
		UNION distinct SELECT *
		FROM profesiones
		WHERE ".$item1." LIKE '%$keyword%'
		OR ".$item2." LIKE '%$keyword%'";

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
				// Imprimir solo si tiene contenido
				if( !empty($item_name) )
					echo '<li role="presentation"><a class="search-option" role="menuitem" href="#">'.$item_name.'</a></li>';
			}			
		} else {
			// Si el item no existe en la BBDD
			echo '<li style="color:black" role="presentation"><a class="search-option" role="menuitem" href="#">Elemento no encontrado</a></li>';
		}
	} else {
		// Si no hay texto en el input
		echo '<li style="color:black" role="presentation"><a class="search-option" role="menuitem" href="#">Campo sin rellenar</a></li>';
	}
}

?>