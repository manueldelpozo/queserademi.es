<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Language" content="es">
    <meta charset="UTF-8">
    <title>Consultar sugerencias</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale = 1.0">
    <link rel="icon" type="image/x-icon" href="../images/logo.png" />
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <script type="text/javascript" src="../js/jquery-2.1.3.js" ></script>
    <style type="text/css" media="screen">
    	body, h4 {
    		padding: 20px;
    		line-height: 30px;
    	}

    	button {
			float: right;
    	}
    </style>
</head>

<body>
	<h1>SUGERENCIAS queserademi</h1>
	<form>
	
	<?php
	//conectar
	require('../conexion.php');

	$campos = [
		'id',
		'sugeridor',
		'email',
		'sugerencia',
		'fecha',
		'solucionada'
	];

	if (isset($_GET['clean'])) {
		$delete = 'DELETE FROM sugerencias WHERE CHAR_LENGTH(sugerencia) < 5';
		$filas_erroneas = $pdo->prepare($delete);
		$filas_erroneas->execute();
	}

	if (isset($_GET['sugerencias_solucionadas'])) {
		$sugerencias_solucionadas = $_GET['sugerencias_solucionadas'];
		$ids = join($sugerencias_solucionadas, ',');
		$update = 'UPDATE `sugerencias` SET `solucionada`=1 WHERE `id` IN (' . $ids . ');';
		echo $update;
		$filas_solucionadas = $pdo->prepare($update);
		$filas_solucionadas->execute();
	}

	$sql = 'SELECT '. join(',', $campos) .' FROM sugerencias';

	$filas = $pdo->prepare($sql);
	$filas->execute();
	$total = $filas->rowCount();
	$totalSolucionadas = 0;
	$hayFalsas = false;

	echo '<h4 class="bg-info">Tenemos un total de <strong>'. $total. ' sugerencias</strong>. </h4>';

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
				echo '<tr id="' . $fila['id'] . '"';
				if (strlen($fila['sugerencia']) < 5) {
					$hayFalsas = true;
					echo ' class="danger"';
				} 
				echo '>';
				foreach ($campos as $campo) {
					echo '<td>';
					if ($campo === 'solucionada') {
						$checked = '';
						if ($fila[$campo]) {
							$checked = 'checked';
							$totalSolucionadas++;
						}
						echo '<input type="checkbox" name="sugerencias_solucionadas[]" value="' . $fila['id'] . '" ' . $checked . '>';
					} else {
						echo $fila[$campo];
					}
					echo '</td>';
				}
				echo '</tr>'; 
			}
			echo '</tbody>';

		echo '</table>';
		echo '<h4 class="bg-success">Tenemos un total de <strong>'. $totalSolucionadas . ' sugerencias solucionada</strong><button class="btn btn-success" id="guardar">Guardar</button></h4>';

		if ($hayFalsas) {
			echo '<h4 class="bg-danger">*Algunas de las sugerencias insertadas parecen falsas<button class="btn btn-danger" id="borrar">Borrar</button></h4>';
		}
	?>

	</form>
	<script type="text/javascript">

		$('button').click(function(event) {
			switch ($(event.target).attr('id')) {
				case 'guardar':
					guardar();
					break;
				case 'borrar':
					alert('borrar');
					break;
				default:
					break;
			}
			event.preventDefault();
		}); 

		function guardar() {
			$form = $('form');
			$from.submit(function() {
				$.ajax({
					type: 'get',
					url: 'sugerencias.php',
					data: $form.serialize()
				});
			})
		}

	</script>

</body>

</html>