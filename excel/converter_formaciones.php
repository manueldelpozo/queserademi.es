<?php
//eliminar el limite de ejecucion
set_time_limit(0);

//conectar
require('../conexion.php');
	
try {
	$sql ="DROP TABLE IF EXISTS profesiones_formaciones;DROP TABLE IF EXISTS formaciones;";
	if ($pdo->query($sql))
		echo "<p>Tabla borrada correctamente.</p>\n";
} catch(PDOException $Exception) {
	echo "<p>Error al borrar la tabla.<p>\n";
	exit;
}

try {
	$sql="CREATE TABLE IF NOT EXISTS `formaciones` (
	`cod` int(11) NOT NULL,
	`f_nombre_ppal` varchar(500) NOT NULL,
	`f_nombre_alt` varchar(100),
	`f_descripcion` varchar(2000),
	`duracion_academica` float,
	`duracion_real` float NULL,
	`acceso` float NULL,
	`nivel` float NULL,
	`ultima_actualizacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`cod`) 
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=92101051 ;" ;
	if ($pdo->query($sql))
		print "<p>Tabla formaciones creada correctamente.</p>\n";
} catch(PDOException $Exception) {
	echo "<p>Error al crear la tabla.<p>\n";
	exit;
} 

try {
	$sql="CREATE TABLE IF NOT EXISTS `profesiones_formaciones` (
  	`id` int(11) NOT NULL AUTO_INCREMENT,
  	`id_profesion` int(11) NOT NULL,
  	`id_formacion` int(11) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;
	ALTER TABLE  `profesiones_formaciones` ADD CONSTRAINT  `fk_formaciones_profesiones` FOREIGN KEY (  `id_profesion` ) REFERENCES `qth809`.`profesiones_test` (`id`
	) ON DELETE CASCADE ON UPDATE CASCADE ;
	ALTER TABLE  `profesiones_formaciones` ADD CONSTRAINT  `fk_profesiones_formaciones` FOREIGN KEY (  `id_formacion` ) REFERENCES `qth809`.`formaciones` (`cod`
	) ON DELETE CASCADE ON UPDATE CASCADE ;" ;
	if ($pdo->query($sql))
		print "<p>Tabla profesiones_formaciones creada correctamente.</p>\n";
} catch(PDOException $Exception) {
	echo "<p>Error al crear la tabla.<p>\n";
	exit;
} 

//usar PHPExcel_IOFactory
//include 'phpexcel-master/Classes/PHPExcel/IOFactory.php';
include '../vendor/autoload.php';
//coger excel
$inputFileName = 'tabla_formaciones.xls';

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
try{
	$sql_insert="";
	// desde la fila 5 del archivo excel
	for ($row = 5; $row <= $highestRow; $row++) { 
		$sql_insert .= "INSERT INTO `formaciones` ( `cod` , `f_nombre_ppal` , `f_nombre_alt` , `f_descripcion` , `duracion_academica` , `duracion_real` , `acceso` , `nivel` ) VALUES (";
	    //poner los datos de cada fila en el array rowData
	    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
	    //recoger datos del array rowData
		$cod = $rowData[0][0];
		
	    $f_nombre_ppal = str_replace("'", "\'", $rowData[0][1]);
	    $f_nombre_ppal = ucfirst(trim(str_replace("_", " ", $f_nombre_ppal)));
	    $f_nombre_alt = str_replace("'", "\'", $rowData[0][2]);
	    $f_descripcion = str_replace("'", "\'", $rowData[0][3]);
	    $duracion_academica = str_replace(',', '.', $rowData[0][4]); // convertir las comas en puntos
		$duracion_real = str_replace(',', '.', $rowData[0][5]);
		$acceso = $rowData[0][6];
		$nivel = str_replace(',', '.', $rowData[0][7]);
	   
	    //insertar datos en el VALUE
	    $sql_insert .= "'$cod','$f_nombre_ppal','$f_nombre_alt','$f_descripcion','$duracion_academica','$duracion_real','$acceso','$nivel');";  
	}
	//cerrar sentencia INSERT e insertar en mysql
	if ($pdo->query($sql_insert)) {
	    print "<p>Registro creado correctamente.</p>\n";
		echo "<table border='2px solid'><tr><td>cod</td><td>f_nombre_ppal</td><td>f_nombre_alt</td><td>f_descripcion</td><td>duracion_academica</td><td>duracion_real</td><td>acceso</td><td>nivel</td><td>ultima_actualizacion</td></tr>";
		$sql = 'SELECT * FROM formaciones';
	    foreach ($pdo->query($sql) as $row) {
			print "<td>".$row['cod'] . "</td>";
			print "<td>".$row['f_nombre_ppal'] . "</td>";
			print "<td>".$row['f_nombre_alt'] . "</td>";
			print "<td>".$row['f_descripcion'] . "</td>";
			print "<td>".$row['duracion_academica'] . "</td>";
			print "<td>".$row['duracion_real'] . "</td>";
			print "<td>".$row['acceso'] . "</td>";
			print "<td>".$row['nivel'] . "</td></tr>";   
	    }
		echo "</table>";
	} 
} catch(PDOException $Exception) {
	echo "<p>Error al insertar los datos de la tabla.<p>\n";
	exit;
}

$pdo = null;

?>


