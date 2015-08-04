<?php

// Activamos automaticamente cada dia a las 23pm
// Cronjob con piensasolutions


// Consultamos valores de la tabla colaboraciones
require('conexion.php');
// solo el dia de fecha
$sql_col="SELECT * FROM colaboraciones WHERE DATE(fecha) = DATE(NOW()) " ;
$query_col = $pdo->prepare( $sql_col );
$query_col->execute();
$count_col = $query_col->rowCount();

if( $count_col > 0 ) {
	// Si hay valores nuevos los recorremos en filas
	$colaboraciones = $query_col->fetchAll();
	foreach ( $colaboraciones as $colaboracion ) {
		// Comprobar aceptacion. Si aceptado es true procedemos a la actualizacion
		if( $colaboracion['aceptado'] ) {
			// consultamos si exite ya la profesion
			$sql_prof="SELECT * FROM profesiones WHERE profesion LIKE ".$colaboracion['profesion'] ;
			$query_prof = $pdo->prepare( $sql_prof );
			$query_prof->execute();
			$count_prof = $query_prof->rowCount();
			// Si existe ya la profesion 
			if( $count_prof == 1 ) {
				// consultamos los valores guardados
				$profesion = $query_prof->fetch();		
				// generar una media aritmetica con los nuevos valores NUMERICOS
				//array_sum($array) / count($array);
				
				$p_present = ( $profesion['p_present'] + $colaboracion['p_present'] ) / 2;
				// correccion de paros
				if( is_null( $profesion['p_past'] ) || is_null( $colaboracion['p_past'] )  )
					$p_past = $p_present;
				else
					$p_past = ( $profesion['p_past'] + $colaboracion['p_past'] ) / 2;
				if( is_null( $profesion['p_future'] ) || is_null( $colaboracion['p_future'] )  )
					$p_future = $p_present;
				else
					$p_future = ( $profesion['p_future'] + $colaboracion['p_future'] ) / 2;

				$s_present = ( $profesion['s_present'] + $colaboracion['s_present'] ) / 2;
				// correccion de salarios
				if( is_null( $profesion['s_past'] ) || is_null( $colaboracion['s_past'] )  )
					$s_past = $s_present;
				else
					$s_past = ( $profesion['s_past'] + $colaboracion['s_past'] ) / 2;
				if( is_null( $profesion['s_future'] ) || is_null( $colaboracion['s_future'] )  )
					$s_future = $s_present;
				else
					$s_future = ( $profesion['s_future'] + $colaboracion['s_future'] ) / 2;
				
				$c_memoria = ( $profesion['c_memoria'] + $colaboracion['c_memoria'] ) / 2;
				$c_creatividad = ( $profesion['c_creatividad'] + $colaboracion['c_creatividad'] ) / 2;
				$c_comunicacion = ( $profesion['c_comunicacion'] + $colaboracion['c_comunicacion'] ) / 2;
				$c_forma_fisica = ( $profesion['c_forma_fisica'] + $colaboracion['c_forma_fisica'] ) / 2;
				$c_logica = ( $profesion['c_logica'] + $colaboracion['c_logica'] ) / 2;
				// agregar estudios asociados y descripcion si no existen
				if( empty( $profesion['estudios_asoc'] ) )
					$estudios_asoc = $colaboracion['estudios_asoc'];
				else {
					$estudios_asoc = $profesion['estudios_asoc'];
					//agregar mas estudios??
				}
				if( empty( $profesion['descripcion'] ) )
					$descripcion = $colaboracion['descripcion'];
				else {
					$descripcion = $profesion['descripcion'];
					//optimizar descripcion??
					//opcion de descripcion alternativa??
				}
				// guardar valores antiguos y nuevos en otro registro?
				// Reescribir los nuevos datos en la tabla profesiones
				$sql_update = "UPDATE profesiones SET descripcion = ".$descripcion" , estudios_asoc = ".$estudios_asoc." , p_past = ".$p_past." , p_present = ".$p_present." , p_future = ".$p_future." , s_past = ".$s_past." , s_present = ".$s_present." , s_future = ".$s_future." , c_memoria = ".$c_memoria." , c_creatividad = ".$c_creatividad." , c_comunicacion = ".$c_comunicacion." , c_forma_fisica = ".$c_forma_fisica." , c_logica = ".$c_logica." WHERE profesion LIKE".$profesion;
			} else {
				// si no existe, Insertamos nueva profesion
				$sql_update = "INSERT INTO profesiones ( profesion, descripcion, estudios_asoc, p_past, p_present, p_future, s_past, s_present, s_future, c_memoria, c_creatividad, c_comunicacion, c_forma_fisica, c_logica, ultima_actualizacion ) VALUES ( ".$colaboracion['profesion'].",".$colaboracion['descripcion'].",".$colaboracion['estudios_asoc'].",".$colaboracion['p_past'].",".$colaboracion['p_present'].",".$colaboracion['p_future'].",".$colaboracion['s_past'].",".$colaboracion['s_present'].",".$colaboracion['s_future'].",".$colaboracion['c_memoria'].",".$colaboracion['c_creatividad'].",".$colaboracion['c_comunicacion'].",".$colaboracion['c_forma_fisica'].",".$colaboracion['c_logica'].");";
			}
			// ejecutar actualizacion sql
			$updating = $pdo->prepare( $sql_update );
    		$updating->execute();
		}
		
		

	}	

} else {
	// Si no hay valores nuevos salimos de la aplicacion
	exit();
}
?>