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
	/*'capacidades'   			=> array('c_analisis', 'c_comunicacion', 'c_equipo', 'c_forma_fisica', 'c_objetivos', 'c_persuasion', 'i_ingles', 'i_frances', 'i_aleman', 'i_otro', 'i_otro_nombre'),*/
	'competencias'   			=> array('c_iniciativa', 'c_resolucion', 'c_creatividad', 'c_planificacion', 'c_aprendizaje', 'c_comunicacion', 'c_negociacion', 'c_cliente', 'c_critica', 'c_analisis', 'c_calidad', 'c_espacialidad', 'c_coordinacion', 'c_descubrimiento', 'c_empatia', 'c_equipo', 'c_social', 'c_adaptabilidad', 'c_liderazgo', 'c_integridad', 'c_transmision', 'c_tecnologia', 'c_sensibilidad'),
	'profesiones_formaciones'	=> array('id_formacion'),
	'temporalidad'				=> array('temporalidad')
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
	    $descripcion 	= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][14]);
	   
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
		//$insert_capacidades				= insertar('capacidades', $campos);
		$insert_competencias			= insertar('competencias', $campos);
		$insert_profesiones_formaciones = insertar('profesiones_formaciones', $campos);
		$insert_temporalidad			= insertar('temporalidad', $campos);

		//poner los datos de cada fila en el array rowData
	    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
	    
	    //recoger datos del array rowData
	    $cod 						= $rowData[0][0];
	    $nombre_alt_1				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][2]);
	    $nombre_alt_2				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][3]);
	    $nombre_alt_3				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][4]);
	    $nombre_alt_4				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][5]);
	    $nombre_alt_5				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][6]);
	    $nombre_alt_6				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][7]);
	    $nombre_alt_7				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][8]);
	    $nombre_alt_8				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][9]);
	    $nombre_alt_9				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][10]);
	    $nombre_alt_10				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][11]);
	    $nombre_alt_11				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][12]);
	    $nombre_alt_12				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][13]);
	    /*descripcion               = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][14]);*/
	    $s_princ_min 				= str_replace(',', '.', $rowData[0][15]); 
	    $s_princ_med 				= str_replace(',', '.', $rowData[0][16]); 
	    $s_princ_max 				= str_replace(',', '.', $rowData[0][17]); 
	    $s_junior_min 				= str_replace(',', '.', $rowData[0][18]); 
	    $s_junior_med 				= str_replace(',', '.', $rowData[0][19]); 
	    $s_junior_max 				= str_replace(',', '.', $rowData[0][20]); 
	    $s_intermedio_min 			= str_replace(',', '.', $rowData[0][21]); 
	    $s_intermedio_med 			= str_replace(',', '.', $rowData[0][22]); 
	    $s_intermedio_max 			= str_replace(',', '.', $rowData[0][23]); 
	    $s_senior_min 				= str_replace(',', '.', $rowData[0][24]); 
	    $s_senior_med 				= str_replace(',', '.', $rowData[0][25]); 
	    $s_senior_max 				= str_replace(',', '.', $rowData[0][26]);
		/*$c_analisis 				= str_replace(',', '.', $rowData[0][27]);
		$c_comunicacion 			= str_replace(',', '.', $rowData[0][28]);
		$c_equipo 					= str_replace(',', '.', $rowData[0][29]);
		$c_forma_fisica 			= str_replace(',', '.', $rowData[0][30]);
		$c_objetivos 				= str_replace(',', '.', $rowData[0][31]);
		$c_persuasion 				= str_replace(',', '.', $rowData[0][32]);
		$i_ingles 					= str_replace(',', '.', $rowData[0][33]);
		$i_frances 					= str_replace(',', '.', $rowData[0][34]);
		$i_aleman 					= str_replace(',', '.', $rowData[0][35]);
		$i_otro 					= str_replace(',', '.', $rowData[0][36]);
		$i_otro_nombre 				= $rowData[0][37];*/
		$c_iniciativa				= $rowData[0][27];
		$c_resolucion	 			= $rowData[0][28];
		$c_creatividad				= $rowData[0][29];
		$c_planificacion 			= $rowData[0][30];
		$c_aprendizaje 				= $rowData[0][31];
		$c_comunicacion				= $rowData[0][32];
		$c_negociacion				= $rowData[0][33];
		$c_cliente					= $rowData[0][34];
		$c_critica					= $rowData[0][35];
		$c_analisis					= $rowData[0][36];
		$c_calidad					= $rowData[0][37];
		$c_espacialidad				= $rowData[0][38];
		$c_coordinacion				= $rowData[0][39];
		$c_descubrimiento			= $rowData[0][40];
		$c_empatia					= $rowData[0][41];
		$c_equipo					= $rowData[0][42];
		$c_social					= $rowData[0][43];
		$c_adaptabilidad			= $rowData[0][44];
		$c_liderazgo				= $rowData[0][45];
		$c_integridad				= $rowData[0][46];
		$c_transmision				= $rowData[0][47];
		$c_tecnologia				= $rowData[0][48];
		$c_sensibilidad				= $rowData[0][49];
		$i_ingles 					= str_replace(',', '.', $rowData[0][50]);
		$i_frances 					= str_replace(',', '.', $rowData[0][51]);
		$i_aleman 					= str_replace(',', '.', $rowData[0][52]);
		$i_otro 					= str_replace(',', '.', $rowData[0][53]);
		$i_otro_nombre 				= $rowData[0][54];
		$temporalidad				= str_replace(',', '.', $rowData[0][55]);
		$id_formacion_1 			= $rowData[0][56];
		$id_formacion_2 			= $rowData[0][57];
		$id_formacion_3 			= $rowData[0][58];
		$parados_enero_2014 		= str_replace(',', '.', $rowData[0][58]);
		$contratados_enero_2014 	= str_replace(',', '.', $rowData[0][59]);
		$parados_abril_2014 		= str_replace(',', '.', $rowData[0][60]);
		$contratados_abril_2014 	= str_replace(',', '.', $rowData[0][61]);
		$parados_julio_2014 		= str_replace(',', '.', $rowData[0][62]);
		$contratados_julio_2014 	= str_replace(',', '.', $rowData[0][63]);
		$parados_octubre_2014 		= str_replace(',', '.', $rowData[0][64]);
		$contratados_octubre_2014 	= str_replace(',', '.', $rowData[0][65]);
		$parados_enero_2015 		= str_replace(',', '.', $rowData[0][66]);
		$contratados_enero_2015 	= str_replace(',', '.', $rowData[0][67]);
		$parados_abril_2015 		= str_replace(',', '.', $rowData[0][68]);
		$contratados_abril_2015 	= str_replace(',', '.', $rowData[0][69]);
		$parados_julio_2015 		= str_replace(',', '.', $rowData[0][70]);
		$contratados_julio_2015 	= str_replace(',', '.', $rowData[0][71]);
		$parados_octubre_2015	 	= str_replace(',', '.', $rowData[0][72]);
		$contratados_octubre_2015 	= str_replace(',', '.', $rowData[0][73]);
		$parados_enero_2016 		= str_replace(',', '.', $rowData[0][74]);
		$contratados_enero_2016 	= str_replace(',', '.', $rowData[0][75]);
		$parados_abril_2016 		= str_replace(',', '.', $rowData[0][76]);
		$contratados_abril_2016 	= str_replace(',', '.', $rowData[0][77]);
		$parados_julio_2016 		= str_replace(',', '.', $rowData[0][78]);
		$contratados_julio_2016 	= str_replace(',', '.', $rowData[0][79]);
		$parados_octubre_2016	 	= str_replace(',', '.', $rowData[0][80]);
		$contratados_octubre_2016 	= str_replace(',', '.', $rowData[0][81]);
		$parados_enero_2017 		= str_replace(',', '.', $rowData[0][82]);
		$contratados_enero_2017 	= str_replace(',', '.', $rowData[0][83]);
		$parados_abril_2017 		= str_replace(',', '.', $rowData[0][84]);
		$contratados_abril_2017 	= str_replace(',', '.', $rowData[0][85]);

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
			$insert_nombres_alt_4 				= $insert_nombres_alt.$id_profesion.",'$nombre_alt_4')";
			$insert_nombres_alt_5 				= $insert_nombres_alt.$id_profesion.",'$nombre_alt_5')";
			$insert_nombres_alt_6 				= $insert_nombres_alt.$id_profesion.",'$nombre_alt_6')";
			$insert_nombres_alt_7 				= $insert_nombres_alt.$id_profesion.",'$nombre_alt_7')";
			$insert_nombres_alt_8 				= $insert_nombres_alt.$id_profesion.",'$nombre_alt_8')";
			$insert_nombres_alt_9 				= $insert_nombres_alt.$id_profesion.",'$nombre_alt_9')";
			$insert_nombres_alt_10 				= $insert_nombres_alt.$id_profesion.",'$nombre_alt_10')";
			$insert_nombres_alt_11				= $insert_nombres_alt.$id_profesion.",'$nombre_alt_11')";
			$insert_nombres_alt_12				= $insert_nombres_alt.$id_profesion.",'$nombre_alt_12')";
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
			/*$insert_capacidades_total 			= $insert_capacidades.$id_profesion.",'$c_analisis','$c_comunicacion','$c_equipo','$c_forma_fisica','$c_objetivos','$c_persuasion','$i_ingles','$i_frances','$i_aleman','$i_otro','$i_otro_nombre')";*/
			$insert_competencias_total 			= $insert_competencias.$id_profesion.",'$c_iniciativa', '$c_resolucion', '$c_creatividad', '$c_planificacion', '$c_aprendizaje', '$c_comunicacion', '$c_negociacion', '$c_cliente', '$c_critica', '$c_analisis', '$c_calidad', '$c_espacialidad', '$c_coordinacion', '$c_descubrimiento', '$c_empatia', '$c_equipo', '$c_social', '$c_adaptabilidad', '$c_liderazgo', '$c_integridad', '$c_transmision', '$c_tecnologia', '$c_sensibilidad')";
			$insert_profesiones_formaciones_1 	= $id_formacion_1 > 0 ? $insert_profesiones_formaciones . $id_profesion . ",$id_formacion_1)" : null; 
			$insert_profesiones_formaciones_2 	= $id_formacion_2 > 0 ? $insert_profesiones_formaciones . $id_profesion . ",$id_formacion_2)" : null;
			$insert_profesiones_formaciones_3 	= $id_formacion_3 > 0 ? $insert_profesiones_formaciones . $id_profesion . ",$id_formacion_3)" : null;
			$insert_temporalidad				= $insert_temporalidad.$id_profesion.",'$temporalidad')";

			$inserts = array(
				$insert_nombres_alt_1, 
				$insert_nombres_alt_2, 
				$insert_nombres_alt_4, 
				$insert_nombres_alt_5, 
				$insert_nombres_alt_6, 
				$insert_nombres_alt_7, 
				$insert_nombres_alt_8, 
				$insert_nombres_alt_9, 
				$insert_nombres_alt_10, 
				$insert_nombres_alt_11, 
				$insert_nombres_alt_12,  
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
				//$insert_capacidades_total, 
				$insert_competencias_total, 
				$insert_profesiones_formaciones_1, 
				$insert_profesiones_formaciones_2, 
				$insert_profesiones_formaciones_3,
				$insert_temporalidad
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


