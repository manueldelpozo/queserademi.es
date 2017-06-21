<?php
if( !empty( $_POST['verificacion'] ) ){
    // Si se ha insertado informacion en el input oculto 'verificacion'.../Es un SPAMbot
    exit();
} else {
	require('conexion.php');

	// resetear variables
	$error = $trabajas = $tiempo_estudios = $jornada_laboral_min = $jornada_laboral_max = $horas_semana = $horas_real = $movilidad = $edad_jubilacion = $tiempo_trabajo = 0;
	$s_modo = '';
	$s_general_anual = $s_principiante_min = $s_principiante_max = $s_junior_min = $s_junior_max = $s_intermedio_min = $s_intermedio_max = $s_senior_min = $s_senior_max = 0;
	$c_equipo = $c_analisis = $c_objetivos = $c_comunicacion = $c_forma_fisica = $persuasion = 0;
	$i_ingles = $i_frances = $i_aleman = $i_otro = $satisfaccion = $aceptado = $email_enviado = 0;
	$colaborador = $email = $profesion =  $descripcion = $comunidad_autonoma = $estudios_asoc = "";
	$acceso = $sector = $contrato = $puesto = $i_otro_val = $codigo_gen = $colaboracion = ""; 
	$descripcion_sugerencia = $sugerencia = $accion = '';

	// metodos para filtrar valores introducidos 
	function is_this_exist($valor) {
		return isset($_POST[$valor]) ? $_POST[$valor] : null;
	}

	function is_this_number($number) {
		return (is_nan($number) || empty($number) || !isset($number) || is_null($number)) ? 0 : $number;
	}

	function is_this_on($valor) {
		return ($valor == 'off' || !isset($valor) || empty($valor) || is_null($valor)) ? 0 : 1;
	}

	function test_input($data) {
		if ( !is_null($data) ) {
		  	$data = trim($data);
		  	$data = stripslashes($data);
		  	$data = htmlspecialchars($data);
		  	return $data;
	    }
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST['sugerencia'])) {
			$accion                 = 'sugerencia';
			$colaborador 			= test_input( is_this_exist( 'sugeridor' ) );
			$email 					= test_input( is_this_exist( 'email' ) );
			$descripcion_sugerencia = test_input( is_this_exist( 'sugerencia' ) );
			$codigo_gen 			= is_this_exist( 'codigo_gen' );

			//MEDIR VERACIDAD EMAIL
			$email_valido = (!is_null($email) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL));
			    
			$sugerencia .= "INSERT INTO `sugerencias`(`sugeridor`, `email`, `sugerencia`, `codigo_gen`, `email_enviado`) ";
			$sugerencia .= "VALUES ( '$colaborador', '$email', '$descripcion_sugerencia', '$codigo_gen', '$email_enviado');";
		} else {
			$accion                 = 'colaboración';
			$colaborador 			= test_input( is_this_exist( "colaborador" ) );
			$email 					= test_input( is_this_exist( "email" ) );
			$profesion 				= test_input( is_this_exist( 'profesion' ) );
			$descripcion 			= test_input( is_this_exist( 'descripcion' ) );
			$trabajas 				= is_this_on( is_this_exist( 'trabajas' ) );
			$comunidad_autonoma 	= is_this_exist( 'comunidad_autonoma' );
			$estudios_asoc 			= test_input( is_this_exist( 'estudios_asoc' ) );
			$tiempo_estudios 		= is_this_number( is_this_exist( 'tiempo_estudios' ) );
			$acceso 				= is_this_exist( 'acceso' );
			$sector 				= is_this_exist( 'sector' );
			$contrato 				= is_this_exist( 'contrato' );
			$jornada_laboral_min 	= is_this_exist( 'jornada_laboral_min' );
			$jornada_laboral_max 	= is_this_exist( 'jornada_laboral_max' );
			$movilidad 				= is_this_on( is_this_exist( 'movilidad' ) );
			$horas_semana 			= is_this_number( is_this_exist( 'horas_semana' ) );
			$horas_real 			= is_this_number( is_this_exist( 'horas_real' ) );
			$puesto 				= is_this_exist( 'puesto' );
			$edad_jubilacion		= is_this_number( is_this_exist( 'edad_jubilacion' ) );
			$tiempo_trabajo 		= is_this_number( is_this_exist( 'tiempo_trabajo' ) );
			$s_modo					= is_this_exist( 's_modo' );
			$s_general_anual	 	= is_this_number( is_this_exist( 's_general_anual' ) );
			$s_principiante_min 	= is_this_number( is_this_exist( 's_principiante_min' ) );
			$s_principiante_max 	= is_this_number( is_this_exist( 's_principiante_max' ) );
			$s_junior_min 			= is_this_number( is_this_exist( 's_junior_min' ) );
			$s_junior_max 			= is_this_number( is_this_exist( 's_junior_max' ) );
			$s_intermedio_min 		= is_this_number( is_this_exist( 's_intermedio_min' ) );
			$s_intermedio_max 		= is_this_number( is_this_exist( 's_intermedio_max' ) );
			$s_senior_min 			= is_this_number( is_this_exist( 's_senior_min' ) );
			$s_senior_max 			= is_this_number( is_this_exist( 's_senior_max' ) );
			$c_equipo 				= is_this_number( is_this_exist( 'c_equipo' ) );
			$c_analisis 			= is_this_number( is_this_exist( 'c_analisis' ) );
			$c_objetivos	 		= is_this_number( is_this_exist( 'c_objetivos' ) );
			$c_comunicacion 		= is_this_number( is_this_exist( 'c_comunicacion' ) );
			$c_forma_fisica 		= is_this_number( is_this_exist( 'c_forma_fisica' ) );
			$c_persuasion			= is_this_number( is_this_exist( 'c_persuasion' ) );
			$i_ingles 				= is_this_number( is_this_exist( 'i_ingles' ) );
			$i_frances 				= is_this_number( is_this_exist( 'i_frances' ) );
			$i_aleman 				= is_this_number( is_this_exist( 'i_aleman' ) );
			$i_otro 				= is_this_number( is_this_exist( 'i_otro' ) );
			$i_otro_val 			= is_this_exist( 'i_otro_val' );
			$satisfaccion 			= is_this_number( count( is_this_exist( 'stars' ) ) );
			$codigo_gen 			= is_this_exist( 'codigo_gen' );
		
			/* TEST
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
			$email_valido = false;
			if( !is_null( $email ) && !empty( $email ) ) {
				$domain = substr( $email, strpos($email,'@') );
				// invalid emailaddress
			    if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) )
			    	$error += 0.1;
			    else
			    	$email_valido = true;
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

			//CONCORDANCIA DE DATOS NUMERICOS
			function diferencia($valor_antiguo, $valor_nuevo) {
				if(is_null($valor_nuevo) || $valor_antiguo == 0 || is_null($valor_antiguo))
					return 0;
				else
					return 2*abs($valor_antiguo - $valor_nuevo) / ($valor_antiguo + $valor_nuevo);	
			}

			//convertir neto mensual a bruto anual //TODO
			function convertirSalarioAnual($salario, $s_modo) {
				return ($s_modo === 'mensual') ? $salario * 12 : $salario;
			} 

			$s_general_anual 	= convertirSalarioAnual($s_general_anual, $s_modo);
			$s_principiante_max = convertirSalarioAnual($s_principiante_max, $s_modo);
			$s_principiante_min = convertirSalarioAnual($s_principiante_min, $s_modo);
			$s_junior_max 		= convertirSalarioAnual($s_junior_max, $s_modo);
			$s_junior_min 		= convertirSalarioAnual($s_junior_min, $s_modo);
			$s_intermedio_max 	= convertirSalarioAnual($s_intermedio_max, $s_modo);
			$s_intermedio_min 	= convertirSalarioAnual($s_intermedio_min, $s_modo);
			$s_senior_max 		= convertirSalarioAnual($s_senior_max, $s_modo);
			$s_senior_min 		= convertirSalarioAnual($s_senior_min, $s_modo);

			//obtencion de datos guardados
			$consulta = "SELECT * FROM profesiones p 
			INNER JOIN nombres_alt n ON p.id = n.id_profesion 
			INNER JOIN salarios s ON p.id = s.id_profesion 
			INNER JOIN capacidades c ON p.id = c.id_profesion 
			WHERE p.nombre_ppal LIKE '$profesion' OR n.nombre_alt LIKE '$profesion' ";
		    $rs_registro = $pdo->prepare($consulta);
		    $rs_registro->execute();
		    $registro = $rs_registro->fetch(PDO::FETCH_ASSOC);

			if( diferencia( $registro['s_princ_max'], $s_principiante_max ) > 0.5 )
				$error += 0.05;	
			if( diferencia( $registro['s_junior_max'], $s_junior_max ) > 0.5 )
				$error += 0.05;	
			if( diferencia( $registro['s_intermedio_max'], $s_intermedio_max ) > 0.5 )
				$error += 0.05;
			if( diferencia( $registro['s_senior_max'], $s_senior_max ) > 0.5 )
				$error += 0.05;

			if( diferencia( $registro['c_equipo'], $c_equipo ) > 0.5 )
				$error += 0.03;	
			if( diferencia( $registro['c_analisis'], $c_analisis ) > 0.5 )
				$error += 0.03;
			if( diferencia( $registro['c_comunicacion'], $c_comunicacion ) > 0.5 )
				$error += 0.03;
			if( diferencia( $registro['c_forma_fisica'], $c_forma_fisica ) > 0.5 )
				$error += 0.03;
			if( diferencia( $registro['c_objetivos'], $c_objetivos ) > 0.5 )
				$error += 0.03;
			if( diferencia( $registro['c_persuasion'], $c_persuasion ) > 0.5 )
				$error += 0.03;

			// SENTENCIA DE ERROR
			$aceptado = $error > 0.5 ? 0 : 1;

			// COMPROBAR SI YA HAY DATOS INTRODUCIDOS DE LA MISMA COLABORACION
		    $rs_colaboraciones = $pdo->prepare("SELECT * FROM colaboraciones WHERE codigo_gen LIKE '$codigo_gen'");
		    $rs_colaboraciones->execute();

		    $ya_existe = $rs_colaboraciones->rowCount();

		    // Comprobar si el email de agradecimiento ya ha sido enviado
		    $datos_recogidos = $rs_colaboraciones->fetch(PDO::FETCH_ASSOC);
		    $email_enviado = $datos_recogidos['email_enviado'];
		    
			// GENERAR SENTENCIA SQL PARA INTRODUCIR O ACTUALIZAR DATOS EN LA BBDD
			if ($ya_existe > 0) {
				// SI EXISTE GENERAR UPDATE
				$colaboracion .= "UPDATE colaboraciones SET colaborador = '$colaborador' , email = '$email' , profesion = '$profesion' , descripcion = '$descripcion' , trabajas = '$trabajas' , comunidad_autonoma = '$comunidad_autonoma' , estudios_asoc = '$estudios_asoc' , tiempo_estudios = '$tiempo_estudios' , ";
				$colaboracion .= "acceso = '$acceso' , sector = '$sector' , contrato = '$contrato' , jornada_laboral_min = '$jornada_laboral_min' , jornada_laboral_max = '$jornada_laboral_max' , movilidad = '$movilidad' , horas_semana = '$horas_semana' , horas_real = '$horas_real' , puesto = '$puesto' , edad_jubilacion = '$edad_jubilacion' , ";
				$colaboracion .= "tiempo_trabajo = '$tiempo_trabajo' , s_general_anual = '$s_general_anual' , s_principiante_min = '$s_principiante_min' , s_principiante_max = '$s_principiante_max' ,s_junior_min = '$s_junior_min' , s_junior_max = '$s_junior_max' , s_intermedio_min = '$s_intermedio_min' , s_intermedio_max = '$s_intermedio_max' , s_senior_min = '$s_senior_min' , s_senior_max = '$s_senior_max' , ";
				$colaboracion .= "c_equipo = '$c_equipo' , c_analisis = '$c_analisis' , c_comunicacion = '$c_comunicacion' , c_forma_fisica = '$c_forma_fisica' , c_objetivos = '$c_objetivos' , c_persuasion = '$c_persuasion' , i_ingles = '$i_ingles' , i_frances = '$i_frances' , i_aleman = '$i_aleman' , i_otro = '$i_otro' , i_otro_val = '$i_otro_val' , satisfaccion = '$satisfaccion' , aceptado = '$aceptado' ";
				$colaboracion .= "WHERE codigo_gen LIKE '$codigo_gen'";
			} else {
				// SI NO EXISTE GENERAR INSERT
				$colaboracion .= "INSERT INTO `colaboraciones`(`colaborador`, `email`, `profesion`, `descripcion`, `trabajas`, `comunidad_autonoma`, `estudios_asoc`, `tiempo_estudios`, `acceso`, `sector`, `contrato`, `jornada_laboral_min`, `jornada_laboral_max`, `movilidad`, `horas_semana`, `horas_real`, `puesto`, `edad_jubilacion`, `tiempo_trabajo`, `s_general_anual`, `s_principiante_min`, `s_principiante_max`, `s_junior_min`, `s_junior_max`, `s_intermedio_min`, `s_intermedio_max`, `s_senior_min`, `s_senior_max`, `c_equipo`, `c_analisis`, `c_objetivos`, `c_comunicacion`, `c_forma_fisica`, `c_persuasion`, `i_ingles`, `i_frances`, `i_aleman`, `i_otro`, `i_otro_val`, `satisfaccion`, `codigo_gen`, `aceptado`) ";
				$colaboracion .= "VALUES ( '$colaborador', '$email', '$profesion', '$descripcion', $trabajas, '$comunidad_autonoma', '$estudios_asoc', $tiempo_estudios, '$acceso', '$sector', '$contrato', $jornada_laboral_min, $jornada_laboral_max, $movilidad, $horas_semana, $horas_real, '$puesto', $edad_jubilacion, $tiempo_trabajo, $s_general_anual, $s_principiante_min, $s_principiante_max, $s_junior_min, $s_junior_max, $s_intermedio_min, $s_intermedio_max, $s_senior_min, $s_senior_max, $c_equipo, $c_analisis, $c_objetivos, $c_comunicacion, $c_forma_fisica, $c_persuasion, $i_ingles, $i_frances, $i_aleman, $i_otro, '$i_otro_val', $satisfaccion, '$codigo_gen', $aceptado);";
			}
		} 
	}
	
?>

<!DOCTYPE html>
<html>
	<head>
	    <title>queserademi.com | Verificador de colaboraciones</title>
	    <meta name="description" content="Gracias por colaborar con queserademi">
	    <!--Compatibilidad y móvil-->
	    <meta http-equiv="Content-Language" content="es">
	    <meta charset="UTF-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="robots" content="noodp">
	    <meta name="viewport" content="width=device-width, initial-scale = 1.0">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="theme-color" content="#c00">
	    <!--OGs-->
	    <link rel="canonical" href="http://queserademi.com/verificador.php">
	    <meta property="og:locale" content="es_ES">
	    <meta property="og:type" content="website">
	    <meta property="og:title" content="Orientación Laboral y Comparador de Profesiones | queserademi">
	    <meta property="og:description" content="Gracias por colaborar con queserademi">
	    <meta property="og:url" content="http://queserademi.com/verificador.php">
	    <meta property="og:site_name" content="queserademi">
	    <meta property="og:image" content="http://queserademi.com/images/logo.png">
	    <!--Links css-->
	    <link rel="icon" type="image/x-icon" href="images/logo.png">
	    <link rel="stylesheet" href="css/bootstrap.min.css">
	    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css">
	    <link rel="stylesheet" href="css/style.css">
	</head>
	<body>
        <div id="preloader"></div>
        <div class="background-image grayscale"></div>
		<div class="container-full">

			<div class="row header">
				<div class="col-md-6 col-md-offset-3 col-xs-12 text-center">
					<a href="http://queserademi.com">
						<h1 id="titulo" class="lead"><strong>que</strong>sera<strong>de</strong>mi</h1>
						<img class="img-responsive" src="images/logo.svg">
					</a>
			    </div>
			</div>

			<div class="row body">
			  	<div class="col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1 text-center">
<?php
//AGRADECIMIENTOS
	$sql = isset($_POST['sugerencia']) ? $sugerencia : $colaboracion;

	// ejecutar actualizacion sql
	$updating = $pdo->prepare($sql);
	$updating->execute();

	if ($updating) {

		echo "<h1>La información ha sido recibida correctamente!</h1>\n";
		echo "<h2>Muchas gracias por tu " . $accion . "!</h2>\n";	

		//enviar mail de agradecimiento... 
		//solo si tenemos el email, es valido y aun no se ha enviado
		if(!is_null($email) && !$email_enviado && $email_valido) {
			
			if(is_null($colaborador) || empty($colaborador)) {
				$colaborador = 'amigo/a';
			}
		
			$linea1 	= 'Estimado/a ' . $colaborador . ',';
			$linea2 	= "Nos alegra que haya participado en este gran proyecto.";
			$linea2b 	= "Gracias a " . $accion . ", podremos seguir desarrollando esta potente herramienta que servirá de apoyo orientativo a futuras y presentes generaciones.";
			$enlace		= $accion === 'sugerencia' ? 'quenossugieres.html' : 'colabora.php';
			$linea3 	= "Puede seguir " . $accion === 'sugerencia' ? 'sugeriendo' : 'colaborando'; 
			$linea3b 	= $accion === 'sugerencia' ? ', para hacer de queserademi un portal web más completo y accesible.' : ', aportando información profesional de familiares o cercanos.';
			$linea4 	= "Atentamente,";
			$linea5 	= "El equipo 'queserademi'.";
			$linea6 	= "QUESERADEMI";
			$linea7 	= "http://www.queserademi.com/";
			$linea8 	= "info@queserademi.com";
			
			//$headers = "From: info@queserademi.com" . "\r\n" . "CC: ".$email;
			//$asunto = 'Gracias por colaborar con queserademi';

			//mail( $email, $asunto, $mensaje, $headers );
			// Tambien se puede usar PHPmailer
			
			include 'vendor/autoload.php';

			$mail = new PHPMailer();                                // defaults to using php "mail()"

			//$mail->SMTPDebug = 3;                               // Enable verbose debug output
			
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host 		= 'smtp.queserademi.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth 	= true;                               // Enable SMTP authentication
			$mail->Username 	= 'info@queserademi.com';                 // SMTP username
			$mail->Password 	= 'Qsdm2017';                           // SMTP password
			//$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port 		= 587;                                    // TCP port to connect to
			
			$mail->From 		= 'info@queserademi.com';
			$mail->FromName 	= 'queserademi';
			$mail->addAddress($email, $colaborador );     // Add a recipient
			//$mail->addAddress('ellen@example.com');               // Name is optional
			$mail->addReplyTo('info@queserademi.com', 'queserademi');
			//$mail->addCC('cc@example.com');
			//$mail->addBCC('bcc@example.com');

			//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
			$mail->isHTML(true);  
			//$mail­->CharSet = "UTF­8";
			//$mail­->Encoding = "quoted­printable";                                // Set email format to HTML

			$mail->Subject 		= 'Queserademi te agradece tu ' . $accion;
			$mail->Body    		= "<strong>" . $linea1 . "</strong><br><p>" . $linea2 . "<br>" . $linea2b . '</p><p><a href="http://www.queserademi.com/' . $enlace . '">' . $linea3 . "</a>" . $linea3b . "</p><p>" . $linea4 . "<br><br>" . $linea5 . "</p><br><p><strong>" . $linea6 . "</strong><br><a href='http://www.queserademi.com'>" . $linea7 . "</a><br><a href='mailto:info@queserademi.com'>" . $linea8 . "</a><br><br><img src='http://www.queserademi.com/images/logo.png' heigh='60px' width='60px'></p>";
			//'This is the body in plain text for non-HTML mail clients';
			$mail->AltBody 		= $linea1."\n\n".$linea2."\n".$linea2b."\n\n".$linea3.$linea3b."\n\n".$linea4."\n\n".$linea5."\n\n".$linea6."\n".$linea7."\n".$linea8;

			if(!$mail->send()) {
			    echo '<h3>[El mail no ha podido enviarse]</h3><br>';
			    //echo '<h3>Mailer Error: ' . $mail->ErrorInfo . '</h3>'; 
			} else {
				// confirmar envio del mail actualizando el booleano en la bbdd
				$tabla = isset($_POST['sugerencia']) ? 'sugerencias' : 'colaboraciones';
				$update = "UPDATE " . $tabla . " SET email_enviado = '1' WHERE codigo_gen LIKE '$codigo_gen';";
				$enviar_email = $pdo->prepare($update);
			    $enviar_email->execute();
			    echo "<h3>[Recibirá un mail en breves instantes]</h3>";
			}
		}

	} else { 
		echo "<h1>Lo sentimos, tu " . $accion . " no se ha recibido correctamente...<h1>\n";
		echo "<h2>Por favor, vuelve a <a href='colabora.php'>intentarlo</a></h2>";
	}
?>
				</div>
			</div>
			<div class="col-xs-12 margen"></div>
		</div>

		<footer>
			<div class="row">
				<div class="col-lg-12 col-md-12 hidden-sm hidden-xs text-center">
					<button type="button" data-toggle="dropup" aria-expanded="false" class="btn-footer" id="btn-footer-md" ><span class="caret flecha"></span></button>
		        </div>
		        <div class="hidden-lg hidden-md col-sm-12 col-xs-12">
		        	<div class="col-sm-3 col-xs-3 text-center">
		        		<a href="http://queserademi.com">	
			        		<img class="img-menu" src="images/logo.svg" width='35px' height="auto">      	
		          		</a>
		        	</div>
		        	<div class="col-sm-3 col-sm-offset-6 col-xs-3 col-xs-offset-6">
						<button type="button" data-toggle="dropup" aria-expanded="false" class="btn-footer" id="btn-footer-xs" ><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span></button>
					</div>
		        </div>
				<div class="col-md-2 col-md-offset-0 hidden-sm hidden-xs col-xs-6 col-xs-offset-3 text-center">
			        <a href="http://queserademi.com">	
			          	<p id="titulo" style='opacity:1;margin-top:-10px;'>
				          	<img class="image-container" src="images/logo.svg">
				          	<strong>que</strong>sera<strong>de</strong>mi
			          	</p>
			        </a>
		        </div>
			    <div class="col-md-10 col-sm-12 col-xs-12 text-center">
			        <div class="col-md-2 col-md-offset-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
					  <a href="colabora.php">cómo colaborar</a>
					  <span class="hidden-sm hidden-xs separador">|</span>
					</div>
					<div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
					  <a href="porquecolaborar.html">por qué colaborar</a>
					  <span class="hidden-sm hidden-xs separador">|</span>
					</div>
					<div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
					  <a href="quienessomos.html">quiénes somos</a>
					  <span class="hidden-sm hidden-xs separador">|</span>
					</div>
					<div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
					  <a href="noticias/">qué noticias</a>
					</div>
			        <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu social">
			          <ul class="share-buttons">
			            <li><a href="https://www.facebook.com/queserademicom" target="_blank" title="Share on Facebook" onclick="window.open('https://www.facebook.com/queserademicom'); return false;"><i class="fa fa-facebook-square fa-2x"></i></a></li>
			            <li><a href="mailto:?subject=Comparador%20de%20profesiones&body=:%20http%3A%2F%2Fwww.queserademi.com" target="_blank" title="Email" onclick="window.open('mailto:?subject=' + encodeURIComponent(document.title) + '&body=' +  encodeURIComponent(document.URL)); return false;"><i class="fa fa-envelope-square fa-2x"></i></a></li>
			          </ul>
			        </div>
		        </div>
		        <div class="col-md-10 col-md-offset-2 col-sm-12 col-xs-12 terminos text-center">
	                <div class="col-md-2 col-md-offset-4 col-sm-12 col-xs-12 hidden-xs mobile-menu">
	                    <a href="quenossugieres.html">qué nos sugieres</a>
	                    <span class="hidden-sm hidden-xs separador">|</span>
	                </div>
	                <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
	                    <a rel="license" href="http://ec.europa.eu/justice/data-protection/index_es.htm">privacidad de datos</a>
	                    <span class="hidden-sm hidden-xs separador">|</span>
	                </div>
	                <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
	                    <a rel="license" href="https://creativecommons.org/licenses/by/4.0/">terminos de uso</a>
	                </div>
	                <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
	                    <small>&copy; 2017 queserademi.com</small>
	                </div>
	            </div>
			</div>
	    </footer>

	    <!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
	    <!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	    <![endif]-->
		<script type="text/javascript" src="js/jquery-2.1.3.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/scripts.js"></script>
	</body>

</html>
<?php 
}
?>