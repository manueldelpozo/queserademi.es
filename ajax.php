<?php

require('conexion.php');

if( isset( $_GET['keyword'] ) ){

	$keyword = $_GET['keyword'];
	$keyword = addslashes( $keyword );
	$keyword = trim( $keyword );
	$keyword = preg_replace( '/--+/', '-', $keyword );

	if( !$keyword == "" ) { 
	//	$sql="SELECT * FROM profesiones_sanitarias WHERE profesion LIKE '%$keyword%' " ;
	$sql="	SELECT  *
	FROM profesiones_sanitarias
	WHERE profesion LIKE '$keyword%'
	UNION distinct SELECT *
	FROM profesiones_sanitarias
	WHERE profesion LIKE '%$keyword%'
	OR estudios_asoc LIKE '%$keyword%'";
	if($keyword== '%'){
		$sql.= 'ORDER BY profesion ASC';	
	}
		$query = $pdo->prepare($sql);
		$query->bindParam(':keyword', $keyword, PDO::PARAM_STR);
		$query->execute();
		$count = $query->rowCount();
		
		if( $count > 0 ){
			$list = $query->fetchAll();
			foreach ( $list as $rs ) {
				$profesion_name = ucfirst( mb_strtolower( str_replace( $_GET['keyword'],$_GET['keyword'], $rs['profesion'] ),'UTF-8' ) );
				// Listado
				echo '<li role="presentation"><a class="search-option" role="menuitem" href="#">'.$profesion_name.'</a></li>';
			}			
		} else {
			echo '<li style="color:black" role="presentation"><a class="search-option" role="menuitem" href="#">Profesion no encontrada</a></li>';
		}
	} else {echo '<li style="color:black" role="presentation"><a class="search-option" role="menuitem" href="#">Profesion vacia</a></li>';}
}

?>