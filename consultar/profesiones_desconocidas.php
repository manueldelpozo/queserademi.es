<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Language" content="es">
    <meta charset="UTF-8">
    <title>Consultar profesiones desconocidas</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale = 1.0">
    <link rel="icon" type="image/x-icon" href="../images/logo.png" />
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <style type="text/css" media="screen">
    	body, h4 {
    		padding: 20px;
    	}

    	button {
			float: right;
    	}
    </style>
</head>

<body>
	<h1>PROFESIONES DESCONOCIDAS queserademi</h1>
	
	<?php
	//conectar
	require('../conexion.php');

	$campos = [
		'profesion_desconocida',
		'fecha'
	];

	if (isset($_POST['clean'])) {
		$delete = 'DELETE FROM profesiones_desconocidas WHERE CHAR_LENGTH(profesion_desconocida) < 5';
		$filas_erroneas = $pdo->prepare($delete);
		$filas_erroneas->execute();
	}

	$sql = 'SELECT '. join(',', $campos) .' FROM profesiones_desconocidas';

	$filas = $pdo->prepare($sql);
	$filas->execute();
	$total = $filas->rowCount();

	echo '<h4 class="bg-info">Tenemos un total de <strong>'. $total. ' profesiones desconocidas</strong>. </h4>';
	echo '<form action="profesiones_desconocidas.php?clean=true" method="post"><h4 class="bg-danger">*Algunas de las profesiones insertadas parecen falsas<button type="submit" class="btn btn-danger">Borrar</button></h4></form>';

	echo '<table class="table table-striped">';

	echo '<thead>';
		echo '<tr>';
		foreach ($campos as $campo) {
			echo '<th>'. $campo . '</th>';
		}
		echo '</tr>';
	echo '</thead>';

	echo '<tbody>';
	foreach ($filas as $fila) {
		echo '<tr';
		echo strlen($fila['profesion_desconocida']) < 5 ? ' class="danger">' : '>';
		foreach ($campos as $campo) {
			echo '<td>'. $fila[$campo] . '</td>';
		}
		echo '</tr>'; 
	}
	echo "</tbody>";

	echo '</table>';
	?>

</body>

</html>