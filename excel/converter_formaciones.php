<?php

//conectar
require('../conexion.php');
	
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
	// desde la fila 2 del archivo excel
	for ($row = 2; $row <= $highestRow; $row++) { 
		$sql_insert.= "INSERT INTO `formaciones` ( `cod` , `nombre_ppal` , `nombre_alt` , `descripcion` , `duracion_academica` , `duracion_real` , `acceso` , `nivel` ) VALUES (";
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
	   
	    //insertar datos en el VALUE
	    $sql_insert .= "'$cod','$nombre_ppal','$nombre_alt','$descripcion','$duracion_academica','$duracion_real','$acceso','$nivel');";  
	}
	//cerrar sentencia INSERT
	if ($pdo->query($sql_insert)) {
	    print "<p>Registro creado correctamente.</p>\n";
		echo "<table border='2px solid'><tr><td>id</td><td>cod</td><td>nombre_ppal</td><td>nombre_alt</td><td>descripcion</td><td>duracion_academica</td><td>duracion_real</td><td>acceso</td><td>nivel</td><td>ultima_actualizacion</td></tr>";
		$sql = 'SELECT * FROM formaciones';
	    foreach ($pdo->query($sql) as $row) {
			print "<td>".$row['id'] . "</td>";
			print "<td>".$row['cod'] . "</td>";
			print "<td>".$row['nombre_ppal'] . "</td>";
			print "<td>".$row['nombre_alt'] . "</td>";
			print "<td>".$row['descripcion'] . "</td>";
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


