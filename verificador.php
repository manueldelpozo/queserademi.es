<?php
if ($_POST['verificacion'] != ""){
    // Es un SPAMbot
    exit();
}else{
    // Es un usuario real, proceder a enviar el formulario.

	$error;

	// VALIDAR EMAIL??
	$email = $_POST['email'];
	$domail = substr( $email, strpos($email,'@') );
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    	// invalid emailaddress
    	$error =+ 0.3;
		//Additionally you can check whether the domain defines an MX record:
		if (!checkdnsrr($domain, 'MX')) {
		    // domain is not valid
		    $error =+ 0.2;
		}
	}

	//MEDIR CONCORDANCIA DE CONTENIDOS
	$profesion = $_POST['profesion'];
	$estudios = $_POST['estuios_asoc'];
	$descripcion = $_POST['descripcion'];
	if( !empty($descripcion) ) {
		if( !empty($estudios) ) {
			if( !preg_match("/($profesion|$estudios)/i", $descripcion) ) {
				//none found
				$error =+ 0.1;
			}
		} else {
			if( !preg_match("/$profesion/i", $descripcion) ) {
				$error =+ 0.05;
		}
	}

	//CORRECCION DE SALARIOS
	$s_past = $_POST['s_past'];
	$s_present = $_POST['s_present'];
	$s_future = $_POST['s_future'];

	if( empty($s_past) )
		$s_past == $p_present;
	if( empty($s_future) )
		$s_future == $p_present;



}
?>