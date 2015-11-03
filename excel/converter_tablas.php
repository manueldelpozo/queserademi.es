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
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
} catch(Exception $e) {
    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}

//coger las dimensiones de la hoja de calculo
$sheet = $objPHPExcel->getSheet(0); 
$highestRow = $sheet->getHighestRow(); 
$highestColumn = $sheet->getHighestColumn();

//preparar sentencia INSERT
//bucle de cada fila
try {
	
	$campos = { 
		'profesiones_test': ['cod', 'nombre_alt', 'nombre_alt', 'descripcion']
		'salarios': ['s_junior_min', 's_junior_max', 's_intermedio_min', 's_intermedio_max', 's_senior_min', 's_senior_max'],
		'empleabilidad': ['parados', 'contratados', 'mes', 'anyo'],
		'capacidades': ['c_memoria', 'c_comunicacion', 'c_analisis', 'c_forma_fisica', 'c_equipo'],
		'profesion_formacion': ['nombre_ppal',]
	};

	function insertar( $tabla ) {
	    $insercion = "INSERT INTO '$tabla' (";
    	if ( $table != 'profesiones_test')
    		$insercion .= "id_profeion, ";
	    foreach ( $campos[$tabla] as $campo) {
	      $insercion .= $campo.", ";
	    }
	    $insercion .= ") VALUES (";
	    return $insercion;
	    //////
	    if ($tabla == 'info')
	      $where = "WHERE ";
	    else if ($tabla == 'formaciones')
	      $where = "p INNER JOIN profesiones_formaciones pf ON p.id = pf.id_profesion INNER JOIN formaciones f ON f.id = pf.id_formaciones ";
	    else
	      $where = "p, '$tabla' '$tabla_ref' WHERE p.id = '$tabla_ref'.id_profesion AND ";
	    $consulta .= " FROM profesiones_test ".$where."p.nombre_ppal LIKE '$profesion'";
	    $rs = $pdo->prepare($consulta);
	    $rs->execute();
	    return $rs->fetch();
	}
  	
	//$sql_insert="";
	// desde la fila 2 del archivo excel
	for ($row = 2; $row <= $highestRow; $row++) { 
		$insert_profesiones = insertar('profesiones');
		$insert_salarios = insertar('salarios');
		$insert_empleabilidad = insertar('empleabilidad');
		$insert_capacidades = insertar('capacidades');
		$insert_profesion_formacion = insertar('profesion_formacion');
		$inserts = [$insert_salarios,$insert_empleabilidad,$insert_capacidades,$insert_profesion_formacion];
		//$sql_insert.= "INSERT INTO `formaciones` ( `cod` , `nombre_ppal` , `nombre_alt` , `descripcion` , `duracion_academica` , `duracion_real` , `acceso` , `nivel` ) VALUES (";
	    //poner los datos de cada fila en el array rowData
	    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
	    //recoger datos del array rowData
		$cod = $rowData[0][0];
	    $nombre_ppal = $rowData[0][1];
	    $nombre_alt = $rowData[0][2];
	    $descripcion = $rowData[0][3];
	    $duracion_academica = str_replace(',', '.', $rowData[0][4]); // convertir las comas en puntos
		$duracion_real = str_replace(',', '.', $rowData[0][5]);
		$acceso = $rowData[0][6];
		$nivel = str_replace(',', '.', $rowData[0][7]);
	   
	    //insertar profesiones
	    $insert_profesiones_values = "'$cod','$nombre_ppal','$nombre_alt','$descripcion','$duracion_academica')";
		if ($pdo->query($insert_profesiones+$insert_profesiones_values))
		    echo "<p>Registro creado correctamente.</p>\n";
		
		
	    //$sql_insert .= "'$cod','$nombre_ppal','$nombre_alt','$descripcion','$duracion_academica','$duracion_real','$acceso','$nivel');";  
	
		
	}
	//cerrar sentencia INSERT
	foreach ( $i = 0; $i < $inserts; $i++) {
		if ($pdo->query($inserts[i])) {
		    echo "<p>Registro creado correctamente.</p>\n";
		    /*
		    echo "<table border='1px solid'><th>";
		    foreach ($campos[i] as $campo) {
		    	echo "<td>".$campo."</td>";
		    }
		    echo "<th>";
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
	}
	// momento de insertar profesion_formacion
	//consulta a las tablas profesion y formacion y obtener ids
	$sql_profesiones = "SELECT id, nombre_ppal FROM profesiones_test";
	$sql_formaciones = "SELECT id FROM formaciones";
	foreach ($pdo->query($sql) as $row) {
		$id_profesion = $row['id'];
		$insert_salarios_values = "'$id_profesion','$s_junior_min',....)";
		$insert_empleabilidad_values = "'$id_profesion','$parados',....)";
		$insert_capacidades_values = "'$id_profesion','$c_memoria',....)";
		$insert_profesion_formacion_values = "'$id_profesion','$id_formacion')"; // necesito las ids // primero consultar // o usar cods
		$inserts_values = [$insert_salarios_values,$insert_empleabilidad_values,$insert_capacidades_values,$insert_profesion_formacion_values];
		foreach ( $i = 0; $i < count($inserts); $i++) {
			$insert = $inserts[$i]+$inserts_values[$i];
			if ($pdo->query($insert))
			    echo "<p>Registro creado correctamente.</p>\n";	
		}
	}
	//insertar el resto
	
	
} catch(PDOException $Exception) {
	echo "<p>Error al insertar los datos de la tabla.<p>\n";
	exit;
}

$pdo = null;

?>


