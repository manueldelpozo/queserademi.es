<?php

if( !empty( $_POST['verificacion'] ) ){
    // Es un SPAMbot
    exit();
} else {
	$error;

	// VALIDAR EMAIL??
	$email = $_POST['email'];
	$domain = substr( $email, strpos($email,'@') );
	// invalid emailaddress
    if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) )
    	$error =+ 0.1;
	//Additionally you can check whether the domain defines an MX record:
	if ( !checkdnsrr( $domain, 'MX' ) )
		$error =+ 0.1;
	
	//MEDIR CONCORDANCIA DE CONTENIDOS
	$profesion = $_POST['profesion'];
	$estudios = $_POST['estuios_asoc'];
	$descripcion = $_POST['descripcion'];
	if( !empty($descripcion) ) {
		if( !empty($estudios) ) {
			if( !preg_match("/($profesion|$estudios)/i", $descripcion) )
				$error =+ 0.1; 
		} else {
			if( !preg_match("/$profesion/i", $descripcion) )
				$error =+ 0.05;
		}
	}

	//CORRECCION DE SALARIOS
	$s_past = $_POST['s_past'];
	$s_present = $_POST['s_present'];
	$s_future = $_POST['s_future'];

	if( empty($s_past) )
		$s_past == $s_present;
	if( empty($s_future) )
		$s_future == $s_present;

	//CORRECCION DE PAROS
	$p_past = $_POST['p_past'];
	$p_present = $_POST['p_present'];
	$p_future = $_POST['p_future'];

	if( empty($p_past) )
		$p_past == $p_present;
	if( empty($p_future) )
		$p_future == $p_present;

	//CONCORDANCIA DE DATOS FINALES
	function diferencia( $valor_antiguo, $valor_nuevo ) {
		return 2*abs($valor_antiguo - $valor_nuevo) / ($valor_antiguo + $valor_nuevo);	
	}
	//obtencion de datos guardados
	require('conexion.php');
  
    $consulta = "SELECT * FROM profesiones_sanitarias WHERE profesion LIKE '$profesion'";
    $result = $pdo->prepare($consulta);
    $result->execute();
    $registro = $result->fetch();

	if( diferencia( $registro['s_past'], $s_past ) > 0.5 )
		$error =+ 0.05;	
	if( diferencia( $registro['s_present'], $s_present ) > 0.5 )
		$error =+ 0.05;
	if( diferencia( $registro['s_future'], $s_future ) > 0.5 )
		$error =+ 0.05;

	if( diferencia( $registro['p_past'], $p_past ) > 0.5 )
		$error =+ 0.05;	
	if( diferencia( $registro['p_present'], $p_present ) > 0.5 )
		$error =+ 0.05;
	if( diferencia( $registro['p_future'], $p_future ) > 0.5 )
		$error =+ 0.05;

	if( diferencia( $registro['c_memoria'], $c_memoria ) > 0.5 )
		$error =+ 0.05;	
	if( diferencia( $registro['c_logica'], $c_logica ) > 0.5 )
		$error =+ 0.05;
	if( diferencia( $registro['c_comunicacion'], $c_comunicacion ) > 0.5 )
		$error =+ 0.05;
	if( diferencia( $registro['c_forma_fisica'], $c_formafisica ) > 0.5 )
		$error =+ 0.05;
	if( diferencia( $registro['c_creatividad'], $c_creatividad ) > 0.5 )
		$error =+ 0.05;

	//SENTENCIA DE ERROR
	if( $error > 0.5 )
		$aceptado = false;
	else
		$aceptado = true;
		
	//GUARDAR COLABORACIONES
	$colaborador = $_POST['colaborador'];
	$fecha = date_default_timezone_get();
	//$fecha = date('m/d/Y h:i:s a', time());

	$sql_insert = "INSERT INTO `colaboraciones` ( `colaborador` , `email` , `profesion` , `descripcion` , `estudios_asoc` , `p_past` , `p_present` , `p_future` , `s_past` , `s_present` , `s_future` , `c_memoria` , `c_creatividad` , `c_comunicacion` , `c_forma_fisica` , `c_logica` , `fecha` , `aceptado` ) VALUES ( '$colaborador','$email','$profesion','$descripcion','$estudios_asoc','$p_past','$p_present','$p_future','$s_past','$s_present','$s_future','$c_memoria','$c_creatividad','$c_comunicacion','$c_forma_fisica','$c_logica','$fecha','$aceptado');";

	//AGRADECIMIENTOS
	if ( $pdo->query($sql_insert) ) {
		echo "<h1>Colaboracion recibida correctamente. Muchas gracias por su tiempo.</h1>\n";
		echo "<h2>Muchas gracias por su tiempo.</h2>";
		//enviar mail
	} else { 
		echo "<h1>Tu colaboracion no se ha recibido correctamente.<h1>\n";
		echo "<h2>Por favor, vuelve a <a href='colabora.php'>intentarlo</a></h2>";
	}
}
?>