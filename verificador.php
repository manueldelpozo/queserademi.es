<?php

if( !empty( $_POST['verificacion'] ) ){
    // Si se ha insertado informacion en el input oculto 'verificacion'.../Es un SPAMbot
    exit();
} else {
	$error;

	// VALIDAR EMAIL??
	$email = $_POST['email'];
	if( !empty( $email ) ) {
		$domain = substr( $email, strpos($email,'@') );
		// invalid emailaddress
	    if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) )
	    	$error += 0.1;
		//Additionally you can check whether the domain defines an MX record:
		if ( !checkdnsrr( $domain, 'MX' ) )
			$error += 0.1;
	}

	//MEDIR CONCORDANCIA DE CONTENIDOS
	$profesion = $_POST['profesion'];
	$estudios = $_POST['estuios_asoc'];
	$descripcion = $_POST['descripcion'];
	if( !empty($descripcion) ) {
		if( !empty($estudios) ) {
			if( !preg_match("/($profesion|$estudios)/i", $descripcion) )
				$error += 0.1; 
		} else {
			if( !preg_match("/$profesion/i", $descripcion) )
				$error += 0.05;
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
		$error += 0.05;	
	if( diferencia( $registro['s_present'], $s_present ) > 0.5 )
		$error += 0.05;
	if( diferencia( $registro['s_future'], $s_future ) > 0.5 )
		$error += 0.05;

	if( diferencia( $registro['p_past'], $p_past ) > 0.5 )
		$error += 0.05;	
	if( diferencia( $registro['p_present'], $p_present ) > 0.5 )
		$error += 0.05;
	if( diferencia( $registro['p_future'], $p_future ) > 0.5 )
		$error += 0.05;

	if( diferencia( $registro['c_memoria'], $c_memoria ) > 0.5 )
		$error += 0.05;	
	if( diferencia( $registro['c_logica'], $c_logica ) > 0.5 )
		$error += 0.05;
	if( diferencia( $registro['c_comunicacion'], $c_comunicacion ) > 0.5 )
		$error += 0.05;
	if( diferencia( $registro['c_forma_fisica'], $c_formafisica ) > 0.5 )
		$error += 0.05;
	if( diferencia( $registro['c_creatividad'], $c_creatividad ) > 0.5 )
		$error += 0.05;

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
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es">
		<meta charset="UTF-8">
		<title>Verificador de colaboraciones</title>
		<meta name="description" content="Gracias por colaborar con queserademi">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta prefix="og: http://ogp.me/ns#" property="og:title" content="Bienvenido a queserademi" />
	    <meta prefix="og: http://ogp.me/ns#" property="og:image" content="http://www.queserademi.es/images/logo.png" />
	    <meta prefix="og: http://ogp.me/ns#" property="og:url" content="http://www.queserademi.es/" />
	    <link rel="icon" type="image/x-icon" href="images/logo.png">
	    <link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/style.css" />
		<!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
	    <!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	    <![endif]-->
		<script type="text/javascript" src="js/jquery-2.1.3.js" defer></script>
		<script type="text/javascript" src="js/bootstrap.min.js" defer></script>
		<script type="text/javascript" src="js/scripts.js" defer></script>
	</head>
	<body>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-64706657-1', 'auto');
            ga('send', 'pageview');
        </script>
        <!-- Google Tag Manager -->
        <noscript>
            <iframe src="//www.googletagmanager.com/ns.html?id=GTM-5MQKZX"
        height="0" width="0" style="display:none;visibility:hidden"></iframe>  
        </noscript>
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-5MQKZX');
        </script>
        <!-- End Google Tag Manager -->
		<div class="container-full">

			<div class="row header">
				<div class="col-md-6 col-md-offset-3 col-xs-12 text-center">
					<a href="">
						<h1 id="titulo" class="lead"><strong>que</strong>sera<strong>de</strong>mi</h1>
						<img class="img-responsive" src="images/logo.svg">
					</a>
					<h6 class="sublead">Tu comparador de profesiones</h6>
			    </div>
			</div>

			<div class="row body">
			  	<div class="col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1 text-center">
<?php
	//AGRADECIMIENTOS
	if ( $pdo->query($sql_insert) ) {

		echo "<h1>La informacion ha sido recibida correctamente!</h1>\n";
		echo "<h2>Muchas gracias por colaborar con queserademi.</h2>\n";
		echo "<h2>[Recibira un mail en breves instantes]</h2>";

		//enviar mail de agradecimiento... y concurso?
		//solo si tenemos el email
		if( !empty( $email ) ) {

			if( empty( $colaborador ) )
				$colaborador = "colaborador";
			
			$mensaje = "Estimado ".$colaborador.",\n\n";
			$mensaje .= "Nos alegra que haya participado en este gran proyecto. Gracias a la informacion que ha aportado, podremos seguir desarrollando esta potente herramienta que servira de apoyo orientativo a futuras y presentes generaciones."."\n\n";
			$mensaje .= "Puede seguir colaborando, aportando informacion profesional de familiares o cercanos. "."\n\n";
			$mensaje .= "Cordialmente,"."\n\n";
			$mensaje .= "El equipo 'queserademi'."."\n\n";
			$mensaje .= "QUESERADEMI"."\n";
			$mensaje .= "http://www.queserademi.es/"
			
			$headers = "From: info@queserademi.es" . "\r\n" . "CC: ".$email;
			$asunto = 'Gracias por colaborar con queserademi';

			mail( $email, $asunto, $mensaje, $headers );
			// Tambien se puede usar PHPmailer
		}

	} else { 
		echo "<h1>Lo sentimos, su colaboracion no se ha recibido correctamente...<h1>\n";
		echo "<h2>Por favor, vuelva a <a href='colabora.php'>intentarlo</a></h2>";
	}
}
?>
				</div>
			</div>
			
		</div>
	</body>
</html>                         
