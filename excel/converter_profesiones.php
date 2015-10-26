<?php

//conectar
require('../conexion.php');
	
try{
	$sql ="DROP TABLE IF EXISTS profesiones";
	if ($pdo->query($sql))
		echo "<p>Tabla borrada correctamente.</p>\n";
} catch(PDOException $Exception) {
	echo "<p>Error al borrar la tabla.<p>\n";
	exit;
}

try {
	$sql="CREATE TABLE IF NOT EXISTS `profesiones` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`cod` int(11),
	`nombre_ppal` varchar(100) NOT NULL,
	`descripcion` varchar(500),
	`estudios_asoc` varchar(500),
	`p_past` float,
	`p_present` float,
	`p_future` float,
	`s_past` float,
	`s_present` float,
	`s_future` float,
	`c_memoria` float,
	`c_creatividad` float,
	`c_comunicacion` float,
	`c_forma_fisica` float,
	`c_logica` float,
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
$inputFileName = 'tabla_profesiones.xls';

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
	for ($row = 8; $row <= $highestRow; $row++) { 
		$sql_insert.= "INSERT INTO `profesiones` ( `cod` , `nombre_ppal` , `descripcion` , `estudios_asoc` , `p_past` , `p_present` , `p_future` , `s_past` , `s_present` , `s_future` , `c_memoria` , `c_creatividad` , `c_comunicacion` , `c_forma_fisica` , `c_logica` ) VALUES (";
	    //poner los datos de cada fila en el array rowData
	    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
	    //recoger datos del array rowData
		$cod = $rowData[0][0];
		
	    $nombre_ppal= $rowData[0][1];
	    $descripcion = $rowData[0][2];
		$estudios_asoc = $rowData[0][3];
	    
	    $p_past = str_replace(',', '.', $rowData[0][4]); // convertir las comas en puntos
		$p_present = str_replace(',', '.', $rowData[0][5]);
		$p_future = str_replace(',', '.', $rowData[0][6]);
		$s_past = str_replace(',', '.', $rowData[0][7]);
		$s_present = str_replace(',', '.', $rowData[0][8]);
		$s_future = str_replace(',', '.', $rowData[0][9]);
		$c_memoria = str_replace(',', '.', $rowData[0][10]);
		$c_creatividad = str_replace(',', '.', $rowData[0][11]);
		$c_comunicacion = str_replace(',', '.', $rowData[0][12]);
		$c_forma_fisica = str_replace(',', '.', $rowData[0][13]);
		$c_logica = str_replace(',', '.', $rowData[0][14]);
	   
	    //insertar datos en el VALUE
	    $sql_insert .= "'$cod','$nombre_ppal','$descripcion','$estudios_asoc','$p_past','$p_present','$p_future','$s_past','$s_present','$s_future','$c_memoria','$c_creatividad','$c_comunicacion','$c_forma_fisica','$c_logica');";  
	}
	//cerrar sentencia INSERT
	if ($pdo->query($sql_insert)) {
	    print "<p>Registro creado correctamente.</p>\n";
		echo "<table border='2px solid'><tr><td>id</td><td>cod</td><td>nombre_ppal</td><td>descripcion</td><td>estudios_asoc</td><td>p_past</td><td>p_present</td><td>p_future</td><td>s_past</td><td>s_present</td><td>s_future</td><td>c_memoria</td><td>c_creatividad</td><td>c_comunicacion</td><td>c_forma_fisica</td><td>c_logica</td><td>ultima_actualizacion</td></tr>";
		$sql = 'SELECT * FROM profesiones';
	    foreach ($pdo->query($sql) as $row) {
			print "<td>".$row['id'] . "</td>";
			print "<td>".$row['cod'] . "</td>";
			print "<td>".$row['nombre_ppal'] . "</td>";
			print "<td>".$row['descripcion'] . "</td>";
			print "<td>".$row['estudios_asoc'] . "</td>";
			print "<td>".$row['p_past'] . "</td>";
			print "<td>".$row['p_present'] . "</td>";
			print "<td>".$row['p_future'] . "</td>";
			print "<td>".$row['s_past'] . "</td>";
			print "<td>".$row['s_present'] . "</td>";
			print "<td>".$row['s_future'] . "</td>";
			print "<td>".$row['c_memoria'] . "</td>";
			print "<td>".$row['c_creatividad'] . "</td>";
			print "<td>".$row['c_comunicacion'] . "</td>";
			print "<td>".$row['c_forma_fisica'] . "</td>";
			print "<td>".$row['c_logica'] . "</td>";
			print "<td>".$row['ultima_actualizacion'] . "</td></tr>";   
	    }
		echo "</table>";
	} 
} catch(PDOException $Exception) {
	echo "<p>Error al insertar los datos de la tabla.<p>\n";
	exit;
}

$pdo = null;

?>

