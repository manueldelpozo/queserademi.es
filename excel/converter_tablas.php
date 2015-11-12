<?php

//conectar
require('../conexion.php');
/*	
try{
	$sql ="DROP TABLE IF EXISTS formaciones";
	if ($pdo->query($sql))
		echo "<p>Tabla borrada correctamente.</p>\n";
} catch(PDOException $Exception) {
	echo "<p>Error al borrar la tabla.<p>\n";
	exit;
}

try {
	$sql="CREATE TABLE IF NOT EXISTS `formaciones` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`cod` int(11),
	`nombre_ppal` varchar(100) NOT NULL,
	`nombre_alt` varchar(100),
	`descripcion` varchar(500),
	`duracion_academica` float,
	`duracion_real` float,
	`acceso` float,
	`nivel` float,
	`ultima_actualizacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=92101051 ;" ;
	if ($pdo->query($sql))
		print "<p>Tabla creada correctamente.</p>\n";
} catch(PDOException $Exception) {
	echo "<p>Error al crear la tabla.<p>\n";
	exit;
} 
*/
//usar PHPExcel_IOFactory
include '../vendor/autoload.php';
//coger excel
$inputFileName = 'tabla_general.xls';

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

//preparar sentencia INSERT
//bucle de cada fila
try {

	$campos = array( 
		'profesiones_test'		=> array('cod', 'nombre_ppal', 'descripcion'),
		'nombres_alt'			=> array('nombre_alt_1', 'nombre_alt_2', 'nombre_alt_3'),
		'salarios'      		=> array('s_junior_min', 's_junior_max', 's_intermedio_min', 's_intermedio_max', 's_senior_min', 's_senior_max'),
		'empleabilidad' 		=> array('parados', 'contratados', 'mes', 'anyo'),
		'capacidades'   		=> array('c_memoria', 'c_comunicacion', 'c_analisis', 'c_forma_fisica', 'c_equipo'),
		'profesion_formacion'	=> array('nombre_ppal')
	);

	function insertar( $tabla, $campos ) {
	    $insercion = "INSERT INTO '$tabla' (";
    	if ( $table != 'profesiones_test')
    		$insercion .= "id_profeion, ";
	    foreach ( $campos[$tabla] as $campo) {
	      $insercion .= " ".$campo.",";
	    }
	    $insercion = substr($insercion, 0, -1) . ") VALUES (";
	    return $insercion;
	}

	// desde la fila 2 del archivo excel
	for ($row = 2; $row <= $highestRow; $row++) { 
		$insert_profesiones = insertar('profesiones', $campos);
	    
	    //poner los datos de cada fila en el array rowData
	    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
	    //recoger datos del array rowData
		$cod 			= $rowData[0][0];
	    $nombre_ppal 	= $rowData[0][1];
	    $descripcion 	= $rowData[0][5];
	   
	    //insertar profesiones
	    $insert_profesiones .= "'$cod','$nombre_ppal','$descripcion')";
		if ($pdo->query($insert_profesiones))
		    echo "<p>Porfesiones insertadas correctamente.</p>\n";	
	}
	// desde la fila 2 del archivo excel
	for ($row = 2; $row <= $highestRow; $row++) { 
		$insert_salarios 				= insertar('salarios', $campos);
		$insert_nombres_alt				= insertar('nombres_alt', $campos);
		$insert_empleabilidad 			= insertar('empleabilidad', $campos);
		$insert_capacidades				= insertar('capacidades', $campos);
		$insert_profesion_formacion 	= insertar('profesion_formacion', $campos);

		//poner los datos de cada fila en el array rowData
	    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
	    //recoger datos del array rowData
	    $cod 						= $rowData[0][0];
	    $nombre_alt_1				= $rowData[0][2];
	    $nombre_alt_2				= $rowData[0][3];
	    $nombre_alt_3				= $rowData[0][4];
	    $s_junior_min 				= str_replace(',', '.', $rowData[0][6]); 
	    $s_junior_max 				= str_replace(',', '.', $rowData[0][7]); 
	    $s_intermedio_min 			= str_replace(',', '.', $rowData[0][8]); 
	    $s_intermedio_max 			= str_replace(',', '.', $rowData[0][9]); 
	    $s_senior_min 				= str_replace(',', '.', $rowData[0][10]); 
	    $s_senior_max 				= str_replace(',', '.', $rowData[0][11]);
		$c_analisis 				= str_replace(',', '.', $rowData[0][12]);
		$c_comunicacion 			= str_replace(',', '.', $rowData[0][13]);
		$c_equipo 					= str_replace(',', '.', $rowData[0][14]);
		$c_forma_fisica 			= str_replace(',', '.', $rowData[0][15]);
		$c_organizacion 			= str_replace(',', '.', $rowData[0][16]);
		$i_ingles 					= str_replace(',', '.', $rowData[0][17]);
		$i_frances 					= str_replace(',', '.', $rowData[0][18]);
		$i_aleman 					= str_replace(',', '.', $rowData[0][19]);
		$i_otro 					= str_replace(',', '.', $rowData[0][20]);
		$i_otro_nombre 				= $rowData[0][21];
		$id_formacion_1 			= $rowData[0][22];
		$id_formacion_2 			= $rowData[0][23];
		$id_formacion_3 			= $rowData[0][24];
		$parados_enero_2014 		= str_replace(',', '.', $rowData[0][25]);
		$contratados_enero_2014 	= str_replace(',', '.', $rowData[0][26]);
		$parados_abril_2014 		= str_replace(',', '.', $rowData[0][27]);
		$contratados_abril_2014 	= str_replace(',', '.', $rowData[0][28]);
		$parados_julio_2014 		= str_replace(',', '.', $rowData[0][29]);
		$contratados_julio_2014 	= str_replace(',', '.', $rowData[0][30]);
		$parados_octubre_2014 		= str_replace(',', '.', $rowData[0][31]);
		$contratados_octubre_2014 	= str_replace(',', '.', $rowData[0][32]);
		$parados_enero_2015 		= str_replace(',', '.', $rowData[0][33]);
		$contratados_enero_2015 	= str_replace(',', '.', $rowData[0][34]);
		$parados_abril_2015 		= str_replace(',', '.', $rowData[0][35]);
		$contratados_abril_2015 	= str_replace(',', '.', $rowData[0][36]);
		$parados_julio_2015 		= str_replace(',', '.', $rowData[0][37]);
		$contratados_julio_2015 	= str_replace(',', '.', $rowData[0][38]);
		
		//inseratar el resto
		//consulta a las tablas profesion y formacion y obtener ids creados
		$sql_profesiones = "SELECT id, nombre_ppal FROM profesiones_test WHERE cod LIKE '$cod'";
		$sql_formaciones = "SELECT id FROM formaciones";
		foreach ($pdo->query($sql) as $row) {
			$id_profesion 					= $row['id'];
			$insert_nombres_alt 			.= "'$nombre_alt_1','$nombre_alt_2','$nombre_alt_3')";
			$insert_salarios 				.= "'$id_profesion','$s_junior_min','$s_junior_max','$s_intermedio_min','$s_intermedio_max','$s_senior_min','$s_senior_max')";
			$insert_empleabilidad 			.= "'$id_profesion','$parados_enero_2014','$contratados_enero_2014','$parados_abril_2014','$contratados_abril_2014','$parados_julio_2014','$contratados_julio_2014','$parados_octubre_2014','$contratados_octubre_2014','$parados_enero_2015','$contratados_enero_2015','$parados_abril_2015','$contratados_abril_2015','$parados_julio_2015','$contratados_julio_2015')";
			$insert_capacidades 			.= "'$id_profesion','$c_analisis','$c_comunicacion','$c_equipo','$c_forma_fisica','$c_organizacion','$i_ingles','$i_frances','$i_aleman','$i_otro','$i_otro_nombre')";
			$insert_profesion_formacion 	.= "'$id_profesion','$id_formacion_1','$id_formacion_2','$id_formacion_2')"; // necesito las ids // primero consultar // o usar cods
			$inserts = array($insert_salarios,$insert_empleabilidad,$insert_capacidades,$insert_profesion_formacion);
			foreach ( $inserts as $insert) {
				if ($pdo->query($insert))
				    echo "<p>Registro insertado correctamente.</p>\n";	
			}
		}

	    /*
	    echo "<table border='1px solid'><th>";
	    foreach ($campos[i] as $campo) {
	    	echo "<td>".$campo."</td>";
	    }
	    echo "<th>";9
		$sql = 'SELECT * FROM formaciones';
	    foreach ($pdo->query($sql) as $row) {
			echo "<td>".$row['id'] . "</td>";
			echo "<td>".$row['cod'] . "</td>";
			echo "<td>".$row['nombre_ppal'] . "</td>";
			echo "<td>".$row['nombre_alt'] . "</td>";
			echo "<td>".$row['descripcion'] . "</td>";
			echo "<td>".$row['duracion_academica'] . "</td>";
			echo "<td>".$row['duracion_real'] . "</td>";
			print "<td>".$row['acceso'] . "</td>";
			print "<td>".$row['nivel'] . "</td></tr>";   
	    }
		echo "</table>";
		*/
		
	}
	
	
	
} catch(PDOException $Exception) {
	echo "<p>Error al insertar los datos de la tabla.<p>\n";
	exit;
}

$pdo = null;

?>


