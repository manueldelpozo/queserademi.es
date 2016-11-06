<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Language" content="es">
    <meta charset="UTF-8">
    <title>Consultar colaboraciones</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale = 1.0">
    <link rel="icon" type="image/x-icon" href="../images/logo.png" />
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <script type="text/javascript" src="../js/jquery-2.1.3.js" ></script>
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
	<h1>COLABORACIONES queserademi</h1>
	
	<?php
	//conectar
	require('../conexion.php');

	$campos = [
		'colaborador'
		,'email'
		,'profesion'
		,'descripcion'
		,'trabajas'
		,'comunidad_autonoma'
		,'estudios_asoc'
		,'tiempo_estudios'
		,'acceso'
		,'sector'
		,'contrato'
		,'jornada_laboral_min'
		,'jornada_laboral_max'
		,'movilidad'
		,'horas_semana'
		,'horas_real'
		,'puesto'
		,'edad_jubilacion'
		,'tiempo_trabajo'
		,'s_principiante_min'
		,'s_principiante_max'
		,'s_junior_min'
		,'s_junior_max'
		,'s_intermedio_min'
		,'s_intermedio_max'
		,'s_senior_min'
		,'s_senior_max'
		,'c_equipo'
		,'c_objetivos'
		,'c_comunicacion'
		,'c_forma_fisica'
		,'c_analisis'
		,'c_persuasion'
		,'i_ingles'
		,'i_frances'
		,'i_aleman'
		,'i_otro'
		,'i_otro_val'
		,'satisfaccion'
		,'codigo_gen'
		,'fecha'
		,'aceptado'
		,'email_enviado'
	];

	if (isset($_POST['clean'])) {
		$delete = 'DELETE FROM colaboraciones WHERE profesion = ""';
		$filas_erroneas = $pdo->prepare($delete);
		$filas_erroneas->execute();
	}

	$sql = 'SELECT '. join(',', $campos) .' FROM colaboraciones';

	$filas = $pdo->prepare($sql);
	$filas->execute();
	$total = $filas->rowCount();

	echo '<h4 class="bg-info">Tenemos un total de <strong>'. $total. ' colaboraciones</strong>. </h4>';
	echo '<form action="colaboraciones.php?clean=true" method="post"><h4 class="bg-danger">*Algunas colaboraciones no incluyen profesion<button type="submit" class="btn btn-danger">Borrar</button></h4></form>';

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
		echo empty($fila['profesion']) ? ' class="danger">' : '>';
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