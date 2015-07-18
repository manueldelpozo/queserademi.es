<?php
if ($_POST['verificacion'] != ""){
    // Es un SPAMbot
    exit();
}else{
    // Es un usuario real, proceder a enviar el formulario.


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

	
}
?>