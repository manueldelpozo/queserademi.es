<?php
//eliminar el limite de ejecucion
set_time_limit(0);

//conectar
require('../conexion.php');

//usar PHPExcel_IOFactory
include '../vendor/autoload.php';
//coger excel
$inputFileName = 'tabla_profesiones.xls';

$campos = array( 
	'profesiones'				=> array('cod', 'nombre_ppal', 'descripcion'),
	'nombres_alt'				=> array('nombre_alt'),
	'salarios'      			=> array('s_princ_min', 's_princ_med', 's_princ_max', 's_junior_min', 's_junior_med', 's_junior_max', 's_intermedio_min', 's_intermedio_med', 's_intermedio_max', 's_senior_min', 's_senior_med', 's_senior_max'),
	'empleabilidad' 			=> array('parados', 'contratados', 'mes', 'anyo'),
	'capacidades'   			=> array('c_analisis', 'c_comunicacion', 'c_equipo', 'c_forma_fisica', 'c_objetivos', 'c_persuasion', 'i_ingles', 'i_frances', 'i_aleman', 'i_otro', 'i_otro_nombre'),
	'profesiones_formaciones'	=> array('id_formacion')
);

function insertar($tabla, $campos) {
    $insercion = "INSERT INTO ".$tabla." (";

	if ($tabla != 'profesiones') {
		$insercion .= "id_profesion, ";
	}

    foreach ( $campos[$tabla] as $campo) {
		$insercion .= $campo.",";
    }

    return substr($insercion, 0, -1) . ") VALUES (";
}

//eliminar tabla de profesiones de la bbdd 
try {
    $delete_sql = 'DELETE FROM profesiones;';
    $delete = $pdo->prepare( $delete_sql );
	$delete->execute();
} catch(PDOException $e) {
    die('Error deleting table');
}

//leer Excel 
try {
    $inputFileType 	= PHPExcel_IOFactory::identify($inputFileName);
    $objReader 		= PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel 	= $objReader->load($inputFileName);
} catch(Exception $e) {
    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}

//coger las dimensiones de la hoja de calculo
$sheet 			= $objPHPExcel->getSheet(0); 
$highestRow 	= $sheet->getHighestRow(); 
$highestColumn 	= $sheet->getHighestColumn();
//$highestRow = 2932;
//$highestColumn = 'AT';
	
try {
	// PRIMERO PROFESIONES
	// desde la fila 2 del archivo excel
	for ($row = 2; $row <= $highestRow; $row++) { 
		$insert_profesiones = insertar('profesiones', $campos);

	    //poner los datos de cada fila en el array rowData
	    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
	    
	    //recoger datos del array rowData
		$cod 			= $rowData[0][0];
	    $nombre_ppal 	= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][1]);
	    $descripcion 	= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][5]);
	   
	    //insertar profesiones
	    $insert_profesiones .= "'$cod','$nombre_ppal','$descripcion')";
		echo $insert_profesiones . "<br>";
		if ($pdo->query($insert_profesiones)){
			echo "<p>Porfesion insertada correctamente.</p>\n";
		}
		else{
			echo "<p>Error en la insercion</p>";
		}	
	}

	// SEGUNDO RESTO
	// desde la fila 2 del archivo excel
	for ($row = 2; $row <= $highestRow; $row++) { 
		$insert_salarios 				= insertar('salarios', $campos);
		$insert_nombres_alt				= insertar('nombres_alt', $campos);
		$insert_empleabilidad 			= insertar('empleabilidad', $campos);
		$insert_capacidades				= insertar('capacidades', $campos);
		$insert_profesiones_formaciones = insertar('profesiones_formaciones', $campos);

		//poner los datos de cada fila en el array rowData
	    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
	    
	    //recoger datos del array rowData
	    $cod 						= $rowData[0][0];
	    $nombre_alt_1				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][2]);
	    $nombre_alt_2				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][3]);
	    $nombre_alt_3				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][4]);
	    $s_princ_min 				= str_replace(',', '.', $rowData[0][6]); 
	    $s_princ_med 				= str_replace(',', '.', $rowData[0][7]); 
	    $s_princ_max 				= str_replace(',', '.', $rowData[0][8]); 
	    $s_junior_min 				= str_replace(',', '.', $rowData[0][9]); 
	    $s_junior_med 				= str_replace(',', '.', $rowData[0][10]); 
	    $s_junior_max 				= str_replace(',', '.', $rowData[0][11]); 
	    $s_intermedio_min 			= str_replace(',', '.', $rowData[0][12]); 
	    $s_intermedio_med 			= str_replace(',', '.', $rowData[0][13]); 
	    $s_intermedio_max 			= str_replace(',', '.', $rowData[0][14]); 
	    $s_senior_min 				= str_replace(',', '.', $rowData[0][15]); 
	    $s_senior_med 				= str_replace(',', '.', $rowData[0][16]); 
	    $s_senior_max 				= str_replace(',', '.', $rowData[0][17]);
		$c_analisis 				= str_replace(',', '.', $rowData[0][18]);
		$c_comunicacion 			= str_replace(',', '.', $rowData[0][19]);
		$c_equipo 					= str_replace(',', '.', $rowData[0][20]);
		$c_forma_fisica 			= str_replace(',', '.', $rowData[0][21]);
		$c_objetivos 				= str_replace(',', '.', $rowData[0][22]);
		$c_persuasion 				= str_replace(',', '.', $rowData[0][23]);
		$i_ingles 					= str_replace(',', '.', $rowData[0][24]);
		$i_frances 					= str_replace(',', '.', $rowData[0][25]);
		$i_aleman 					= str_replace(',', '.', $rowData[0][26]);
		$i_otro 					= str_replace(',', '.', $rowData[0][27]);
		$i_otro_nombre 				= $rowData[0][28];
		$id_formacion_1 			= $rowData[0][29];
		$id_formacion_2 			= $rowData[0][30];
		$id_formacion_3 			= $rowData[0][31];
		$parados_enero_2014 		= str_replace(',', '.', $rowData[0][32]);
		$contratados_enero_2014 	= str_replace(',', '.', $rowData[0][33]);
		$parados_abril_2014 		= str_replace(',', '.', $rowData[0][34]);
		$contratados_abril_2014 	= str_replace(',', '.', $rowData[0][35]);
		$parados_julio_2014 		= str_replace(',', '.', $rowData[0][36]);
		$contratados_julio_2014 	= str_replace(',', '.', $rowData[0][37]);
		$parados_octubre_2014 		= str_replace(',', '.', $rowData[0][38]);
		$contratados_octubre_2014 	= str_replace(',', '.', $rowData[0][39]);
		$parados_enero_2015 		= str_replace(',', '.', $rowData[0][40]);
		$contratados_enero_2015 	= str_replace(',', '.', $rowData[0][41]);
		$parados_abril_2015 		= str_replace(',', '.', $rowData[0][42]);
		$contratados_abril_2015 	= str_replace(',', '.', $rowData[0][43]);
		$parados_julio_2015 		= str_replace(',', '.', $rowData[0][44]);
		$contratados_julio_2015 	= str_replace(',', '.', $rowData[0][45]);
		$parados_octubre_2015	 	= str_replace(',', '.', $rowData[0][46]);
		$contratados_octubre_2015 	= str_replace(',', '.', $rowData[0][47]);
		$parados_enero_2016 		= str_replace(',', '.', $rowData[0][48]);
		$contratados_enero_2016 	= str_replace(',', '.', $rowData[0][49]);
		$parados_abril_2016 		= str_replace(',', '.', $rowData[0][50]);
		$contratados_abril_2016 	= str_replace(',', '.', $rowData[0][51]);
		$parados_julio_2016 		= str_replace(',', '.', $rowData[0][52]);
		$contratados_julio_2016 	= str_replace(',', '.', $rowData[0][53]);
		$parados_octubre_2016	 	= str_replace(',', '.', $rowData[0][54]);
		$contratados_octubre_2016 	= str_replace(',', '.', $rowData[0][55]);
		$parados_enero_2017 		= str_replace(',', '.', $rowData[0][56]);
		$contratados_enero_2017 	= str_replace(',', '.', $rowData[0][57]);
		$parados_abril_2017 		= str_replace(',', '.', $rowData[0][58]);
		$contratados_abril_2017 	= str_replace(',', '.', $rowData[0][59]);

		//consulta a las tablas profesion y formacion y obtener ids creados
		$sql_profesiones = "SELECT id, nombre_ppal FROM profesiones WHERE cod LIKE '$cod'";
		$rs_profesiones = $pdo->prepare($sql_profesiones);
		$rs_profesiones->execute();
		$filas_profesiones = $rs_profesiones->fetchAll();

		foreach ($filas_profesiones as $fila) {
			$id_profesion 						= $fila['id'];
			$insert_nombres_alt_1 				= $insert_nombres_alt.$id_profesion.",'$nombre_alt_1')";
			$insert_nombres_alt_2 				= $insert_nombres_alt.$id_profesion.",'$nombre_alt_2')";
			$insert_nombres_alt_3 				= $insert_nombres_alt.$id_profesion.",'$nombre_alt_3')";
			$insert_salarios_total 				= $insert_salarios.$id_profesion.",'$s_princ_min','$s_princ_med','$s_princ_max','$s_junior_min','$s_junior_med','$s_junior_max','$s_intermedio_min','$s_intermedio_med','$s_intermedio_max','$s_senior_min','$s_senior_med','$s_senior_max')";
			$insert_empleabilidad_enero_2014 	= $insert_empleabilidad.$id_profesion.",'$parados_enero_2014','$contratados_enero_2014','enero',2014)";
			$insert_empleabilidad_abril_2014 	= $insert_empleabilidad.$id_profesion.",'$parados_abril_2014','$contratados_abril_2014','abril',2014)";
			$insert_empleabilidad_julio_2014 	= $insert_empleabilidad.$id_profesion.",'$parados_julio_2014','$contratados_julio_2014','julio',2014)";
			$insert_empleabilidad_octubre_2014 	= $insert_empleabilidad.$id_profesion.",'$parados_octubre_2014','$contratados_octubre_2014','octubre',2014)";
			$insert_empleabilidad_enero_2015 	= $insert_empleabilidad.$id_profesion.",'$parados_enero_2015','$contratados_enero_2015','enero',2015)";
			$insert_empleabilidad_abril_2015 	= $insert_empleabilidad.$id_profesion.",'$parados_abril_2015','$contratados_abril_2015','abril',2015)";
			$insert_empleabilidad_julio_2015 	= $insert_empleabilidad.$id_profesion.",'$parados_julio_2015','$contratados_julio_2015','julio',2015)";
			$insert_empleabilidad_octubre_2015 	= $insert_empleabilidad.$id_profesion.",'$parados_octubre_2015','$contratados_octubre_2015','octubre',2015)";
			$insert_empleabilidad_enero_2016 	= $insert_empleabilidad.$id_profesion.",'$parados_enero_2016','$contratados_enero_2016','enero', 2016)";
			$insert_empleabilidad_abril_2016 	= $insert_empleabilidad.$id_profesion.",'$parados_abril_2016','$contratados_abril_2016','abril', 2016)";
			$insert_empleabilidad_julio_2016 	= $insert_empleabilidad.$id_profesion.",'$parados_julio_2016','$contratados_julio_2016','julio', 2016)";
			$insert_empleabilidad_octubre_2016 	= $insert_empleabilidad.$id_profesion.",'$parados_octubre_2016','$contratados_octubre_2016','octubre', 2016)";
			$insert_empleabilidad_enero_2017 	= $insert_empleabilidad.$id_profesion.",'$parados_enero_2017','$contratados_enero_2017','enero', 2017)";
			$insert_empleabilidad_abril_2017 	= $insert_empleabilidad.$id_profesion.",'$parados_abril_2017','$contratados_abril_2017','abril', 2017)";
			$insert_capacidades_total 			= $insert_capacidades.$id_profesion.",'$c_analisis','$c_comunicacion','$c_equipo','$c_forma_fisica','$c_objetivos','$c_persuasion','$i_ingles','$i_frances','$i_aleman','$i_otro','$i_otro_nombre')";
			$insert_profesiones_formaciones_1 	= $id_formacion_1 > 0 ? $insert_profesiones_formaciones . $id_profesion . ",$id_formacion_1)" : null; 
			$insert_profesiones_formaciones_2 	= $id_formacion_2 > 0 ? $insert_profesiones_formaciones . $id_profesion . ",$id_formacion_2)" : null;
			$insert_profesiones_formaciones_3 	= $id_formacion_3 > 0 ? $insert_profesiones_formaciones . $id_profesion . ",$id_formacion_3)" : null;

			$inserts = array(
				$insert_nombres_alt_1, 
				$insert_nombres_alt_2, 
				$insert_nombres_alt_3, 
				$insert_salarios_total, 
				$insert_empleabilidad_enero_2014, 
				$insert_empleabilidad_abril_2014, 
				$insert_empleabilidad_julio_2014, 
				$insert_empleabilidad_octubre_2014, 
				$insert_empleabilidad_enero_2015, 
				$insert_empleabilidad_abril_2015, 
				$insert_empleabilidad_julio_2015,
				$insert_empleabilidad_octubre_2015, 
				$insert_empleabilidad_enero_2016, 
				$insert_empleabilidad_abril_2016, 
				$insert_empleabilidad_julio_2016,
				$insert_empleabilidad_octubre_2016, 
				$insert_empleabilidad_enero_2017, 
				$insert_empleabilidad_abril_2017,
				$insert_capacidades_total, 
				$insert_profesiones_formaciones_1, 
				$insert_profesiones_formaciones_2, 
				$insert_profesiones_formaciones_3
			);
			foreach ($inserts as $insert) {
				echo $insert . "<br>";
				if (!is_null($insert)){
					echo $pdo->query($insert) ? "<p>Registro insertado correctamente.</p>\n" : "<p>Error en la insercion</p>";
				}
			}
		}		
	}
	
} catch(PDOException $Exception) {
	echo "<p>Error al insertar los datos de la tabla.<p>\n";
	exit;
}

$pdo = null;

?>


