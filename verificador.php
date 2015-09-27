<?php

if( !empty( $_POST['verificacion'] ) ){
    // Si se ha insertado informacion en el input oculto 'verificacion'.../Es un SPAMbot
    exit();
} else {
	require('conexion.php');

	// resetear variables
	$error = $trabajas = $tiempo_estudios = $jornada_laboral_min = $jornada_laboral_max = $horas_semana = $horas_real = $movilidad = $edad_jubilacion = $tiempo_trabajo = 0;
	$s_junior_min = $s_junior_max = $s_intermedio_min = $s_intermedio_max = $s_senior_min = $s_senior_max = 0;
	$c_equipo = $c_analisis = $c_organizacion = $c_comunicacion = $c_forma_fisica = 0;
	$i_ingles = $i_frances = $i_aleman = $i_otro = $satisfaccion = $aceptado = $email_enviado = 0;
	$colaborador = $email = $profesion =  $descripcion = $comunidad_autonoma = $estudios_asoc = "";
	$acceso = $sector = $contrato = $puesto = $i_otro_val = $codigo_gen = $colaboracion = ""; 

	// filtrar valores introducidos 

	function is_this_exist( $valor ) {
		if ( !isset( $valor ) || empty( $valor ) || is_null($valor) )
			$valor = null;
		return $valor;
	}
	function is_this_number( $number ) {
		if ( is_nan( $number ) || empty( $number ) || !isset( $number ) || is_null($number) )
			return 0;
		else
			return $number;
	}
	function is_this_on( $valor ) {
		if ( $valor == 'off' || !isset( $valor ) || empty( $valor ) || is_null($valor) )
			return 0;
		else
			return 1;
	}
	function test_input( $data ) {
		if ( !is_null($data) ) {
		  	$data = trim($data);
		  	$data = stripslashes($data);
		  	$data = htmlspecialchars($data);
		  	return $data;
	    }
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	  $colaborador 			= test_input( is_this_exist( $_POST["colaborador"]) );
	  $email 				= test_input( is_this_exist( $_POST["email"]) );
	  $profesion 			= test_input( is_this_exist( $_POST['profesion']) );
	  $descripcion 			= test_input( is_this_exist( $_POST['descripcion']) );
	  $trabajas 			= is_this_on( $_POST['trabajas'] );
	  $comunidad_autonoma 	= is_this_exist( $_POST['comunidad_autonoma'] );
	  $estudios_asoc 		= test_input( is_this_exist( $_POST['estudios_asoc']) );
	  $tiempo_estudios 		= is_this_number( $_POST['tiempo_estudios'] );
	  $acceso 				= is_this_exist( $_POST['acceso'] );
	  $sector 				= is_this_exist( $_POST['sector'] );
	  $contrato 			= is_this_exist( $_POST['contrato'] );
	  $jornada_laboral_min 	= is_this_exist( $_POST['jornada_laboral_min'] );
	  $jornada_laboral_max 	= is_this_exist( $_POST['jornada_laboral_max'] );
	  $movilidad 			= is_this_on( $_POST['movilidad'] );
	  $horas_semana 		= is_this_number( $_POST['horas_semana'] );
	  $horas_real 			= is_this_number( $_POST['horas_real'] );
	  $puesto 				= is_this_exist( $_POST['puesto'] );
	  $edad_jubilacion		= is_this_number( $_POST['edad_jubilacion'] );
	  $tiempo_trabajo 		= is_this_number( $_POST['tiempo_trabajo'] );
	  $s_junior_min 		= is_this_number( $_POST['s_junior_min'] );
	  $s_junior_max 		= is_this_number( $_POST['s_junior_max'] );
	  $s_intermedio_min 	= is_this_number( $_POST['s_intermedio_min'] );
	  $s_intermedio_max 	= is_this_number( $_POST['s_intermedio_max'] );
	  $s_senior_min 		= is_this_number( $_POST['s_senior_min'] );
	  $s_senior_max 		= is_this_number( $_POST['s_senior_max'] );
	  $c_equipo 			= is_this_number( $_POST['c_equipo'] );
	  $c_analisis 			= is_this_number( $_POST['c_analisis'] );
	  $c_organizacion 		= is_this_number( $_POST['c_organizacion'] );
	  $c_comunicacion 		= is_this_number( $_POST['c_comunicacion'] );
	  $c_forma_fisica 		= is_this_number( $_POST['c_forma_fisica'] );
	  $i_ingles 			= is_this_number( $_POST['i_ingles'] );
	  $i_frances 			= is_this_number( $_POST['i_frances'] );
	  $i_aleman 			= is_this_number( $_POST['i_aleman'] );
	  $i_otro 				= is_this_number( $_POST['i_otro'] );
	  $i_otro_val 			= is_this_exist( $_POST['i_otro_val'] );
	  $satisfaccion 		= is_this_number( count($_POST['stars']) );
	  $codigo_gen 			= $_POST['codigo_gen'];
	}

	/*
	echo $colaborador.' - colaborador<br>';
	echo $email." - email<br>";
	echo $profesion.' - profesion<br>';
	echo $descripcion.' - descripcion<br>';
	echo $trabajas.' - trabajas<br>';
	echo $comunidad_autonoma.' - comunidad_autonoma<br>';
	echo $estudios_asoc.' - estudios_asoc<br>';
	echo $tiempo_estudios.' - tiempo_estudios<br>';
	echo $acceso.' - acceso<br>';
	echo $sector.' - sector<br>';
	echo $contrato.' - contrato<br>';
	echo $jornada_laboral_min.' - jornada_laboral_min<br>';
	echo $jornada_laboral_max.' - jornada_laboral_max<br>';
	echo $movilidad.' - movilidad<br>';
	echo $horas_semana.' - horas_semana<br>';
	echo $horas_real.' - horas_real<br>';
	echo $puesto.' - puesto<br>';
	echo $edad_jubilacion.' - edad_jubilacion<br>';
	echo $tiempo_trabajo.' - tiempo_trabajo<br>';
	echo $s_junior_min.' - s_junior_min<br>';
	echo $s_junior_max.' - s_junior_max<br>';
	echo $s_intermedio_min.' - s_intermedio_min<br>';
	echo $s_intermedio_max .' - s_intermedio_max<br>';
	echo $s_senior_min.' - s_senior_min<br>';
	echo $s_senior_max.' - s_senior_max<br>';
	echo $c_equipo.' - c_equipo<br>';
	echo $c_analisis.' - c_analisis<br>';
	echo $c_organizacion.' - c_organizacion<br>';
	echo $c_comunicacion.' - c_comunicacion<br>';
	echo $c_forma_fisica.' - c_forma_fisica<br>';
	echo $i_ingles.' - i_ingles<br>';
	echo $i_frances.' - i_frances<br>';
	echo $i_aleman.' - i_aleman<br>';
	echo $i_otro.' - i_otro<br>';
	echo $i_otro_val.' - i_otro_val<br>';
	echo $satisfaccion.' - satisfaccion<br>';
	echo $codigo_gen.' - codigo_gen<br>';
	*/

	//MEDIR VERACIDAD EMAIL??
	if( !is_null( $email ) || empty( $email ) ) {
		$domain = substr( $email, strpos($email,'@') );
		// invalid emailaddress
	    if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) )
	    	$error += 0.1;
		//Additionally you can check whether the domain defines an MX record:
		if ( !checkdnsrr( $domain, 'MX' ) )
			$error += 0.1;
	} 

	//MEDIR CONCORDANCIA DE CONTENIDOS
	if ( !is_null( $estudios_asoc ) ) {
		similar_text( mb_strtolower($profesion,'UTF-8'), mb_strtolower($estudios_asoc,'UTF-8'), $percent_prof_est );
		if( $percent_prof_est < 10 )
			$error += 0.05;
	} 

	if ( !is_null( $descripcion ) ) {	
		similar_text( mb_strtolower($profesion,'UTF-8'), mb_strtolower($descripcion,'UTF-8'), $percent_prof_desc );
		if( $percent_prof_desc < 5 )
			$error += 0.05;
		if( !is_null($estudios_asoc) ) {
			similar_text( mb_strtolower($estudios_asoc,'UTF-8'), mb_strtolower($descripcion,'UTF-8'), $percent_est_desc ); 
			if( $percent_est_desc < 5 )
				$error += 0.05;
		}
	} 
	
	//OBTENER DATOS NUMERICOS
	//CORRECCION DE SALARIOS -- se da la posibilidad que sean nulos
	/*
	$s_present = $_POST['s_present'];
	if ( isset( $_POST['s_past'] ) )
		$s_past = $_POST['s_past'];
	else
		$s_past = null;
	if ( isset( $_POST['s_future'] ) )
		$s_future = $_POST['s_future'];
	else
		$s_future = null;
		*/
	/*
	if( empty($s_past) )
		$s_past == $s_present;
	if( empty($s_future) )
		$s_future == $s_present;
	*/
/*	
	//CORRECCION DE PAROS
	$p_present = $_POST['p_present'];
	if ( isset( $_POST['p_past'] ) )
		$p_past = $_POST['p_past'];
	else
		$p_past = null;
	if ( isset( $_POST['p_future'] ) )
		$p_future = $_POST['p_future'];
	else
		$p_future = null;


	//CONCORDANCIA DE DATOS FINALES
	function diferencia( $valor_antiguo, $valor_nuevo ) {
		if( is_null( $valor_nuevo ) || $valor_antiguo == 0 || is_null( $valor_antiguo ) )
			return 0;
		else
			return 2*abs($valor_antiguo - $valor_nuevo) / ($valor_antiguo + $valor_nuevo);	
	}
	//obtencion de datos guardados
  
    $consulta = "SELECT * FROM profesiones WHERE profesion LIKE '$profesion'";
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
	if( diferencia( $registro['c_forma_fisica'], $c_forma_fisica ) > 0.5 )
		$error += 0.05;
	if( diferencia( $registro['c_creatividad'], $c_creatividad ) > 0.5 )
		$error += 0.05;
*/
	// SENTENCIA DE ERROR
	if( $error > 0.5 )
		$aceptado = 0;
	else
		$aceptado = 1;

	// COMPROBAR SI YA HAY DATOS INTRODUCIDOS DE LA MISMA COLABORACION
    $rs_colaboraciones = $pdo->prepare("SELECT * FROM colaboraciones WHERE codigo_gen LIKE '$codigo_gen'");
    $rs_colaboraciones->execute();

    $ya_existe = $rs_colaboraciones->rowCount();

    // Comprobar si el email de agradecimiento ya ha sido enviado
    $datos_recogidos = $rs_colaboraciones->fetch(PDO::FETCH_ASSOC);
    $email_enviado = $datos_recogidos['email_enviado'];
    
	// GENERAR SENTENCIA SQL PARA INTRODUCIR DATOS EN LA BBDD
	if ( $ya_existe > 0 ) {
		// SI EXISTE GENERAR UPDATE
		$colaboracion .= "UPDATE colaboraciones SET colaborador = '$colaborador' , email = '$email' , profesion = '$profesion' , descripcion = '$descripcion' , trabajas = '$trabajas' , comunidad_autonoma = '$comunidad_autonoma' , estudios_asoc = '$estudios_asoc' , tiempo_estudios = '$tiempo_estudios' , ";
		$colaboracion .= "acceso = '$acceso' , sector = '$sector' , contrato = '$contrato' , jornada_laboral_min = '$jornada_laboral_min' , jornada_laboral_max = '$jornada_laboral_max' , movilidad = '$movilidad' , horas_semana = '$horas_semana' , horas_real = '$horas_real' , puesto = '$puesto' , edad_jubilacion = '$edad_jubilacion' , ";
		$colaboracion .= "tiempo_trabajo = '$tiempo_trabajo' , s_junior_min = '$s_junior_min' , s_junior_max = '$s_junior_max' , s_intermedio_min = '$s_intermedio_min' , s_intermedio_max = '$s_intermedio_max' , s_senior_min = '$s_senior_min' , ";
		$colaboracion .= "c_equipo = '$c_equipo' , c_analisis = '$c_analisis' , c_comunicacion = '$c_comunicacion' , c_forma_fisica = '$c_forma_fisica' , c_organizacion = '$c_organizacion' , i_ingles = '$i_ingles' , i_frances = '$i_frances' , i_aleman = '$i_aleman' , i_otro = '$i_otro' , i_otro_val = '$i_otro_val' , satisfaccion = '$satisfaccion' , aceptado = '$aceptado' ";
		$colaboracion .= "WHERE codigo_gen LIKE '$codigo_gen'";
	} else {
		// SI NO EXISTE GENERAR INSERT
		$colaboracion .= "INSERT INTO `colaboraciones`(`id`, `colaborador`, `email`, `profesion`, `descripcion`, `trabajas`, `comunidad_autonoma`, `estudios_asoc`, `tiempo_estudios`, `acceso`, `sector`, `contrato`, `jornada_laboral_min`, `jornada_laboral_max`, `movilidad`, `horas_semana`, `horas_real`, `puesto`, `edad_jubilacion`, `tiempo_trabajo`, `s_junior_min`, `s_junior_max`, `s_intermedio_min`, `s_intermedio_max`, `s_senior_min`, `s_senior_max`, `c_equipo`, `c_organizacion`, `c_comunicacion`, `c_forma_fisica`, `c_analisis`, `i_ingles`, `i_frances`, `i_aleman`, `i_otro`, `i_otro_val`, `satisfaccion`, `codigo_gen`, `aceptado`) ";
		$colaboracion .= "VALUES ( '$colaborador', '$email', '$profesion', '$descripcion', $trabajas, '$comunidad_autonoma', '$estudios_asoc', $tiempo_estudios, '$acceso', '$sector', '$contrato', $jornada_laboral_min, $jornada_laboral_max, $movilidad, $horas_semana, $horas_real, '$puesto', $edad_jubilacion, $tiempo_trabajo, $edad_jubilacion, $s_junior_min, $s_junior_max, $s_intermedio_min, $s_intermedio_max, $s_senior_min, $s_senior_max, $c_equipo, $c_analisis, $c_organizacion, $c_comunicacion, $c_forma_fisica, $i_ingles, $i_frances, $i_aleman, $i_otro, '$i_otro_val', $satisfaccion, '$codigo_gen', $aceptado);";
	}
	
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es">
		<meta charset="utf-8">
		<title>Verificador de colaboraciones</title>
		<meta name="description" content="Gracias por colaborar con queserademi">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes" />

	    <meta prefix="og: http://ogp.me/ns#" property="og:title" content="Gracias por colaborar con queserademi" />
	    <meta prefix="og: http://ogp.me/ns#" property="og:image" content="http://www.queserademi.es/images/logo.png" />
	    <meta prefix="og: http://ogp.me/ns#" property="og:url" content="http://www.queserademi.es/verificador.php" />
	    <link rel="icon" type="image/x-icon" href="images/logo.png">
	    <link rel="stylesheet" href="css/bootstrap.min.css" />
	    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css" />
		<!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
	    <!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	    <![endif]-->
		<script type="text/javascript" src="js/jquery-2.1.3.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.9.3/typeahead.min.js"></script>
		<script type="text/javascript" src="js/scripts.js"></script>
		
	</head>
	<body>
        <div id="preloader"></div>
        <div class="background-image grayscale"></div>
		<div class="container-full">

			<div class="row header">
				<div class="col-xs-12 hidden-sm hidden-md hidden-lg margen"></div>
				<div class="col-md-6 col-md-offset-3 col-xs-12 text-center">
					<a href="index.html">
						<h1 id="titulo" class="lead"><strong>que</strong>sera<strong>de</strong>mi</h1>
						<img class="img-responsive" src="images/logo.svg">
					</a>
					<h6 class="sublead">Te agradecemos tu colaboración</h6>
			    </div>
			</div>

			<div class="row body">
			  	<div class="col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1 text-center">
<?php
//AGRADECIMIENTOS

	// ejecutar actualizacion sql
	$updating = $pdo->prepare( $colaboracion );
	$updating->execute();

	if ( $updating ) {

		echo "<h1>La información ha sido recibida correctamente!</h1>\n";
		echo "<h2>Muchas gracias por colaborar con queserademi.</h2>\n";	

		//enviar mail de agradecimiento... y concurso?
		//solo si tenemos el email y aun no se ha enviado
		if( !is_null( $email ) && !$email_enviado ) {
			

			if( is_null( $colaborador ) )
				$colaborador = "colaborador/a";
		
			$linea1 		= "Estimado/a ".$colaborador.",";
			$linea2 		= "Nos alegra que haya participado en este gran proyecto.";
			$linea2b 		= "Gracias a la información que ha aportado, podremos seguir desarrollando esta potente herramienta que servira de apoyo orientativo a futuras y presentes generaciones.";
			$linea3 		= "Puede seguir colaborando"; 
			$linea3b 		= ", aportando información profesional de familiares o cercanos. ";
			$linea4 		= "Cordialmente,";
			$linea5 		= "El equipo 'queserademi'.";
			$linea6 		= "QUESERADEMI";
			$linea7 		= "http://www.queserademi.es/";
			$linea8 		= "info@queserademi.es";
			
			//$headers = "From: info@queserademi.es" . "\r\n" . "CC: ".$email;
			//$asunto = 'Gracias por colaborar con queserademi';

			//mail( $email, $asunto, $mensaje, $headers );
			// Tambien se puede usar PHPmailer
			
			include 'vendor/autoload.php';

			$mail = new PHPMailer();                                // defaults to using php "mail()"

			//$mail->SMTPDebug = 3;                               // Enable verbose debug output
			
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host 		= 'smtp.queserademi.es';  // Specify main and backup SMTP servers
			$mail->SMTPAuth 	= true;                               // Enable SMTP authentication
			$mail->Username 	= 'info@queserademi.es';                 // SMTP username
			$mail->Password 	= 'Qsdm2015';                           // SMTP password
			//$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port 		= 587;                                    // TCP port to connect to
			
			$mail->From 		= 'info@queserademi.es';
			$mail->FromName 	= 'queserademi';
			$mail->addAddress($email, $colaborador );     // Add a recipient
			//$mail->addAddress('ellen@example.com');               // Name is optional
			$mail->addReplyTo('info@queserademi.es', 'queserademi');
			//$mail->addCC('cc@example.com');
			//$mail->addBCC('bcc@example.com');

			//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
			$mail->isHTML(true);  
			//$mail­->CharSet = "UTF­8";
			//$mail­->Encoding = "quoted­printable";                                // Set email format to HTML

			$mail->Subject 		= 'Gracias por colaborar con queserademi';
			$mail->Body    		= "<strong>".$linea1."</strong><br><p>".$linea2."<br>".$linea2b."</p><p><a href='http://www.queserademi.es/colabora.php'>".$linea3."</a>".$linea3b."</p><p>".$linea4."<br>".$linea5."</p><br><p><strong>".$linea6."</strong><br><a href='http://www.queserademi.es'>".$linea7."</a><br><a href='mailto:info@queserademi.es'>".$linea8."</a><br><br><img src='http://www.queserademi.es/images/logo.png' heigh='60px' width='60px'></p>";
			//'This is the body in plain text for non-HTML mail clients';
			$mail->AltBody 		= $linea1."\n\n".$linea2."\n".$linea2b."\n\n".$linea3.$linea3b."\n\n".$linea4."\n\n".$linea5."\n\n".$linea6."\n".$linea7."\n".$linea8;

			if(!$mail->send()) {
			    echo '<h3>[El mail no ha podido enviarse]</h3><br>';
			    echo '<h3>Mailer Error: ' . $mail->ErrorInfo . '</h3>'; 
			} else {
				// confirmar envio del mail actualizando el booleano en la bbdd
				$enviar_email = $pdo->prepare("UPDATE colaboraciones SET email_enviado = '1' WHERE codigo_gen LIKE '$codigo_gen'");
			    $enviar_email->execute();
			    echo "<h3>[Recibirá un mail en breves instantes]</h3>";
			}
		}

	} else { 
		echo "<h1>Lo sentimos, su colaboración no se ha recibido correctamente...<h1>\n";
		echo "<h2>Por favor, vuelva a <a href='colabora.php'>intentarlo</a></h2>";
	}
?>
				</div>
			</div>
			
		</div>

		<footer>
			<div class="row">
				<div class="col-lg-12 col-md-12 hidden-sm hidden-xs text-center">
					<button type="button" data-toggle="dropup" aria-expanded="false" class="btn-footer" id="btn-footer-md" ><span class="caret flecha"></span></button>
		        </div>
		        <div class="hidden-lg hidden-md col-sm-12 col-xs-12">
		        	<div class="col-sm-3 col-xs-3 text-center">
		        		<a href="index.html">	
			        		<img class="img-menu" src="images/logo.svg" width='35px' height="auto">      	
		          		</a>
		        	</div>
		        	<div class="col-sm-3 col-sm-offset-6 col-xs-3 col-xs-offset-6">
						<button type="button" data-toggle="dropup" aria-expanded="false" class="btn-footer" id="btn-footer-xs" ><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span></button>
					</div>
		        </div>
				<div class="col-md-2 col-md-offset-0 hidden-sm hidden-xs col-xs-6 col-xs-offset-3 text-center">
			        <a href="index.html">	
			          	<p id="titulo" style='opacity:1;margin-top:-10px;'>
				          	<img class="image-container" src="images/logo.svg">
				          	<strong>que</strong>sera<strong>de</strong>mi
			          	</p>
			        </a>
		        </div>
			    <div class="col-md-10 col-sm-12 col-xs-12 text-center">
			        <div class="col-md-2 col-md-offset-2 col-sm-12 col-xs-12 hidden-xs mobile-menu sel-menu">
			          <a href="colabora.php">Puedes colaborar</a>
			          <span class='hidden-sm hidden-xs separador'>|</span>
			        </div>
			        <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
			          <a href="porquecolaborar.html">Por qué colaborar</a>
			          <span class='hidden-sm hidden-xs separador'>|</span>
			        </div>
			        <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
			          <a href="quienessomos.html">Quiénes somos</a>
			          <span class='hidden-sm hidden-xs separador'>|</span>
			        </div>
			        <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
			          <a href="mailto:info@queserademi.es?subject=Pregunta%20para%20queserademi&body=Hola,%0D%0A%0D%0AQuiero contactar con vosotros para..." target="_top">Qué nos sugieres</a>
			        </div>
			        <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu social">
			          <ul class="share-buttons">
			            <li><a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.queserademi.es&t=Comparador%20de%20profesiones" target="_blank" title="Share on Facebook" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL) + '&t=' + encodeURIComponent(document.URL)); return false;"><i class="fa fa-facebook-square fa-2x"></i></a></li>
			            <li><a href="https://plus.google.com/share?url=http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Share on Google+" onclick="window.open('https://plus.google.com/share?url=' + encodeURIComponent(document.URL)); return false;"><i class="fa fa-google-plus-square fa-2x"></i></a></li>
			            <li><a href="http://www.linkedin.com/shareArticle?mini=true&url=http%3A%2F%2Fwww.queserademi.es&title=Comparador%20de%20profesiones&summary=&source=http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Share on LinkedIn" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(document.URL) + '&title=' +  encodeURIComponent(document.title)); return false;"><i class="fa fa-linkedin-square fa-2x"></i></a></li>
			            <li><a href="mailto:?subject=Comparador%20de%20profesiones&body=:%20http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Email" onclick="window.open('mailto:?subject=' + encodeURIComponent(document.title) + '&body=' +  encodeURIComponent(document.URL)); return false;"><i class="fa fa-envelope-square fa-2x"></i></a></li>
			          </ul>
			        </div>
		        </div>
		        <div class="col-md-10 col-md-offset-2 col-sm-12 col-xs-12 terminos text-center">
	        		<div class="col-md-2 col-md-offset-6 col-sm-12 col-xs-12 hidden-xs mobile-menu">
			        	<a rel="license" href="http://ec.europa.eu/justice/data-protection/index_es.htm">Privacidad de datos</a>
			        	<span class='hidden-sm hidden-xs separador'>|</span>
			        </div>
        			<div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
		        		<a rel="license" href="https://creativecommons.org/licenses/by/4.0/">Terminos de uso</a>
        			</div>
			        <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
			        	<small>&copy; 2015 queserademi.es</small>
			        </div>
		        </div>
			</div>
	    </footer>

	    
	</body>

</html>
<?php 
}
?>