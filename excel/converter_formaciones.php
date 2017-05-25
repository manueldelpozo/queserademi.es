<?php
//eliminar el limite de ejecucion
set_time_limit(0);

//conectar
require('../conexion.php');

//usar PHPExcel_IOFactory
include '../vendor/autoload.php';
//coger excel
$inputFileName = 'tabla_formaciones.xls';

$campos = array( 
	'formaciones'					=> array('cod', 'f_nombre_ppal', 'f_descripcion', 'duracion_academica', 'duracion_real'),
	'f_nombres_alt'					=> array('f_nombre_alt'),
	'formaciones_formacion_ant' 	=> array('id_formacion_ant'),
	'formaciones_centros_educativos'=> array('id_centros_educativos')
);

function insertar($tabla, $campos) {
    $insercion = "INSERT INTO ".$tabla." (";

	if ($tabla != 'formaciones') {
		$insercion .= "id_formacion, ";
	}

    foreach ( $campos[$tabla] as $campo) {
		$insercion .= $campo.", ";
    }

    return rtrim($insercion,", ") . ") VALUES (";
}
	
//eliminar tablas de formaciones de la bbdd 
/*try {
	$sql ="DROP TABLE IF EXISTS formaciones_formacion_ant;DROP TABLE IF EXISTS formaciones_centros_educativos;DROP TABLE IF EXISTS profesiones_formaciones;DROP TABLE IF EXISTS formaciones;";
	if ($pdo->query($sql))
		echo "<p>Tablas borradas correctamente.</p>\n";
} catch(PDOException $Exception) {
	echo "<p>Error al borrar la tabla.<p>\n";
	exit;
}*/

/*try {
	$sql = "CREATE TABLE IF NOT EXISTS `formaciones` (
	`id` int(11) NOT NULL,
	`f_nombre_ppal` varchar(500) NOT NULL,
	`f_descripcion` varchar(2000),
	`duracion_academica` float,
	`duracion_real` float NULL,
	`ultima_actualizacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`) 
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=92101051 ;" ;
	
	if ($pdo->query($sql)) {
		print "<p>Tabla formaciones ha sido creada correctamente.</p>\n";
	}
} catch(PDOException $Exception) {
	echo "<p>Error al crear la tabla.<p>\n" . $Exception;
	exit;
} 

try {
	$sql = "CREATE TABLE IF NOT EXISTS `formaciones_formacion_ant` (
  	`id` int(11) NOT NULL AUTO_INCREMENT,
  	`id_formacion` int(11) NOT NULL,
  	`id_formacion_ant` int(11) NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;
	ALTER TABLE  `formaciones_formacion_ant` ADD CONSTRAINT  `fk_formaciones_formacion_ant` FOREIGN KEY (  `id_formacion` ) REFERENCES `qxc430`.`formaciones` (`id`
	) ON DELETE CASCADE ON UPDATE CASCADE ;" ;
	
	if ($pdo->query($sql)){
		print "<p>Tabla formaciones_formacion_ant ha sido creada correctamente.</p>\n";
	}
} catch(PDOException $Exception) {
	echo "<p>Error al crear la tabla.<p>\n" . $Exception;
	exit;
} 

try {
	$sql = "CREATE TABLE IF NOT EXISTS `profesiones_formaciones` (
  	`id` int(11) NOT NULL AUTO_INCREMENT,
  	`id_profesion` int(11) NOT NULL,
  	`id_formacion` int(11) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;
	ALTER TABLE  `profesiones_formaciones` ADD CONSTRAINT  `fk_formaciones_profesiones` FOREIGN KEY (  `id_profesion` ) REFERENCES `qxc430`.`profesiones` (`id`
	) ON DELETE CASCADE ON UPDATE CASCADE ;
	
	if ($pdo->query($sql)){
		print "<p>Tabla profesiones_formaciones ha sido creada correctamente.</p>\n";
	}
} catch(PDOException $Exception) {
	echo "<p>Error al crear la tabla.<p>\n" . $Exception;
	exit;
} 

try {
	$sql = "CREATE TABLE IF NOT EXISTS `formaciones_centros_educativos` (
  	`id` int(11) NOT NULL AUTO_INCREMENT,
  	`id_profesion` int(11) NOT NULL,
  	`id_formacion` int(11) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;
	ALTER TABLE  `formaciones_centros_educativos` ADD CONSTRAINT  `fk_centros_educativos_formaciones` FOREIGN KEY (  `id_formacion` ) REFERENCES `qxc430`.`formaciones` (`id`
	) ON DELETE CASCADE ON UPDATE CASCADE ;
	
	if ($pdo->query($sql)){
		print "<p>Tabla formaciones_centros_educativos ha sido creada correctamente.</p>\n";
	}
} catch(PDOException $Exception) {
	echo "<p>Error al crear la tabla.<p>\n" . $Exception;
	exit;
}*/ 

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
	// PRIMERO FORMACIONES

	/*try {
	    $delete_sql = 'DELETE FROM `formaciones`;';
	    $delete = $pdo->prepare( $delete_sql );
		$delete->execute();
	} catch(PDOException $e) {
	    die('Error deleting table '.$e);
	}

	// desde la fila 5 del archivo excel
	for ($row = 5; $row <= $highestRow; $row++) { 
		$insert_formaciones = insertar('formaciones', $campos);

	    //poner los datos de cada fila en el array rowData
	    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

	    //recoger datos del array rowData
		$cod = $rowData[0][0];
	    $f_nombre_ppal = str_replace("'", "\'", $rowData[0][2]);
	    $f_nombre_ppal = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', ucfirst(trim(str_replace("_", " ", $f_nombre_ppal))));
	    //$f_nombre_alt = str_replace("'", "\'", $rowData[0][2]);
	    $f_descripcion = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', str_replace("'", "\'", $rowData[0][6]));
	    $duracion_academica = str_replace(',', '.', $rowData[0][7]); // convertir las comas en puntos
		$duracion_real = str_replace(',', '.', $rowData[0][8]);

		if (is_null($duracion_real) || $duracion_real === '' || $duracion_real === 0) {
			$duracion_real = $duracion_academica;
		}
	   
	    //insertar datos en el VALUE
	    $insert_formaciones .= "'$cod', '$f_nombre_ppal', '$f_descripcion', '$duracion_academica', '$duracion_real');";  
	    echo $insert_formaciones . "<br>";

		if ($pdo->query($insert_formaciones)){
			echo "<p>Formacion insertada correctamente.</p>\n";
		} else {
			echo "<p>Error en la insercion</p>";
		}
	}*/

	// SEGUNDO RESTO

	try {
	    $delete_sql = 'DELETE FROM `f_nombres_alt`;DELETE FROM `formaciones_formacion_ant`;';
	    $delete = $pdo->prepare( $delete_sql );
		$delete->execute();
		$delete->closeCursor();
	} catch(PDOException $e) {
	    die('Error deleting table ' . $e);
	}

	// desde la fila 5 del archivo excel
	for ($row = 5; $row <= $highestRow; $row++) { 
		$insert_f_nombres_alt					= insertar('f_nombres_alt', $campos);
		$insert_formaciones_formacion_ant		= insertar('formaciones_formacion_ant', $campos);
		//$insert_formaciones_centros_educativos  = insertar('formaciones_centros_educativos', $campos);

		//poner los datos de cada fila en el array rowData
	    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

	    //recoger datos del array rowData
	    $cod 						= $rowData[0][0];
	    $f_nombre_alt_1				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][3]);
	    $f_nombre_alt_2				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][4]);
	    $f_nombre_alt_3				= preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rowData[0][5]);
		$id_formacion_ant_1 		= $rowData[0][9];
		$id_formacion_ant_2 		= $rowData[0][10];
		$id_formacion_ant_3 		= $rowData[0][11];
		//$id_centro_educativo_1 		= $rowData[0][12];
		//$id_centro_educativo_2 		= $rowData[0][13];
		//$id_centro_educativo_3 		= $rowData[0][14];

		//consulta a la tabla formacion para obtener ids creados
		$sql_formaciones = "SELECT id FROM formaciones WHERE cod LIKE '$cod';";
		$rs_formaciones = $pdo->prepare($sql_formaciones);
		$rs_formaciones->execute();
		$filas_formaciones = $rs_formaciones->fetchAll(PDO::FETCH_ASSOC);
		$rs_formaciones->closeCursor();

		foreach ($filas_formaciones as $fila) {
			$id_formacion							= $fila['id'];
			$insert_f_nombres_alt_1 				= $insert_f_nombres_alt . $id_formacion . ", '$f_nombre_alt_1')";
			$insert_f_nombres_alt_2 				= $insert_f_nombres_alt . $id_formacion . ", '$f_nombre_alt_2')";
			$insert_f_nombres_alt_3 				= $insert_f_nombres_alt . $id_formacion . ", '$f_nombre_alt_3')";
			$insert_formaciones_formacion_ant_1 	= $id_formacion_ant_1 > 0 ? $insert_formaciones_formacion_ant . $id_formacion . ", $id_formacion_ant_1)" : null; 
			$insert_formaciones_formacion_ant_2 	= $id_formacion_ant_2 > 0 ? $insert_formaciones_formacion_ant . $id_formacion . ", $id_formacion_ant_2)" : null;
			$insert_formaciones_formacion_ant_3 	= $id_formacion_ant_3 > 0 ? $insert_formaciones_formacion_ant . $id_formacion . ", $id_formacion_ant_3)" : null;
			//$insert_formaciones_centro_educativo_1 	= $id_centro_educativo_1 > 0 ? $insert_formaciones_centro_educativo . $id_formacion . ", $id_centro_educativo_1)" : null; 
			//$insert_formaciones_centro_educativo_2 	= $id_centro_educativo_2 > 0 ? $insert_formaciones_centro_educativo . $id_formacion . ", $id_centro_educativo_2)" : null;
			//$insert_formaciones_centro_educativo_3 	= $id_centro_educativo_3 > 0 ? $insert_formaciones_centro_educativo . $id_formacion . ", $id_centro_educativo_3)" : null;

			$inserts = array(
				$insert_f_nombres_alt_1, 
				$insert_f_nombres_alt_2, 
				$insert_f_nombres_alt_3, 
				$insert_formaciones_formacion_ant_1, 
				$insert_formaciones_formacion_ant_2, 
				$insert_formaciones_formacion_ant_3,
				//$insert_formaciones_centro_educativo_1,
				//$insert_formaciones_centro_educativo_2,
				//$insert_formaciones_centro_educativo_3
			);

			foreach ($inserts as $insert) {
				echo $insert . "<br>";
				if (!is_null($insert)){
					echo $pdo->query($insert) ? "<p>Registro insertado correctamente.</p>" : "<p>Error en la insercion</p>";
				}
			}
		}
	}	
} catch(PDOException $Exception) {
	echo "<p>Error al insertar datos en la tabla.<p>" . $Exception;
	exit;
}

$pdo = null;

?>