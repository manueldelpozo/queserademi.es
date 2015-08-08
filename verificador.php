<?php

if( !empty( $_POST['verificacion'] ) ){
    // Si se ha insertado informacion en el input oculto 'verificacion'.../Es un SPAMbot
    exit();
} else {
	$error = 0;

	// VALIDAR EMAIL??
	if( isset( $_POST['email'] ) ) {
		$email = $_POST['email'];
		$domain = substr( $email, strpos($email,'@') );
		// invalid emailaddress
	    if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) )
	    	$error += 0.1;
		//Additionally you can check whether the domain defines an MX record:
		if ( !checkdnsrr( $domain, 'MX' ) )
			$error += 0.1;
	} else {
		$email = null;
	}

	//MEDIR CONCORDANCIA DE CONTENIDOS
	$profesion = $_POST['profesion'];

	if ( isset( $_POST['estudios_asoc'] ) )
		$estudios_asoc = $_POST['estudios_asoc'];
	else
		$estudios_asoc = null;

	if ( isset( $_POST['descripcion'] ) ) {	
		$descripcion = $_POST['descripcion'];
		if( !empty($descripcion) ) {
			if( !empty($estudios_asoc) ) {
				if( !preg_match("/($profesion|$estudios_asoc)/i", $descripcion) )
					$error += 0.1; 
			} else {
				if( !preg_match("/$profesion/i", $descripcion) )
					$error += 0.05;
			}
		}
	} else {
		$descripcion = null;
	}
	
	//OBTENER DATOS NUMERICOS
	//CORRECCION DE SALARIOS -- se da la posibilidad que sean nulos
	$s_present = $_POST['s_present'];
	if ( isset( $_POST['s_past'] ) )
		$s_past = $_POST['s_past'];
	else
		$s_past = null;
	if ( isset( $_POST['s_future'] ) )
		$s_future = $_POST['s_future'];
	else
		$s_future = null;
	/*
	if( empty($s_past) )
		$s_past == $s_present;
	if( empty($s_future) )
		$s_future == $s_present;
	*/
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
	/*
	if( empty($p_past) )
		$p_past == $p_present;
	if( empty($p_future) )
		$p_future == $p_present;
	*/

	//Colectar datos de capacidades
	$c_memoria = $_POST['c_memoria'];
	$c_logica = $_POST['c_logica'];
	$c_comunicacion = $_POST['c_comunicacion'];
	$c_forma_fisica = $_POST['c_forma_fisica'];
	$c_creatividad = $_POST['c_creatividad'];

	//CONCORDANCIA DE DATOS FINALES
	function diferencia( $valor_antiguo, $valor_nuevo ) {
		if( is_null( $valor_nuevo ) || $valor_antiguo == 0 || is_null( $valor_antiguo ) ) {
			$diff = 0;
		} else {
			$diff = 2*abs($valor_antiguo - $valor_nuevo) / ($valor_antiguo + $valor_nuevo);
		}	
		return $diff;
	}
	//obtencion de datos guardados
	require('conexion.php');
  
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

	//SENTENCIA DE ERROR
	if( $error > 0.5 )
		$aceptado = 0;
	else
		$aceptado = 1;
		
	//GUARDAR COLABORACIONES
	if( isset($_POST['colaborador']) )
		$colaborador = $_POST['colaborador'];
	else
		$colaborador = null;
	//$fecha = date_default_timezone_get(); //Se establece en mySQL
	//$fecha = date('m/d/Y h:i:s a', time());

	$sql_insert = "INSERT INTO `colaboraciones` ( `colaborador` , `email` , `profesion` , `descripcion` , `estudios_asoc` , `p_past` , `p_present` , `p_future` , `s_past` , `s_present` , `s_future` , `c_memoria` , `c_creatividad` , `c_comunicacion` , `c_forma_fisica` , `c_logica` , `aceptado` ) VALUES ( '$colaborador','$email','$profesion','$descripcion','$estudios_asoc','$p_past','$p_present','$p_future','$s_past','$s_present','$s_future','$c_memoria','$c_creatividad','$c_comunicacion','$c_forma_fisica','$c_logica','$aceptado');";
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
	    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">
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
					<h6 class="sublead">Te agradecemos tu colaboración</h6>
			    </div>
			</div>

			<div class="row body">
			  	<div class="col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1 text-center">
<?php
	//AGRADECIMIENTOS
	if ( $pdo->query($sql_insert) ) {

		echo "<h1>La información ha sido recibida correctamente!</h1>\n";
		echo "<h2>Muchas gracias por colaborar con queserademi.</h2>\n";
		

		//enviar mail de agradecimiento... y concurso?
		//solo si tenemos el email
		if( !is_null( $email ) ) {
			echo "<h2>[Recibirá un mail en breves instantes]</h2>";

			if( is_null( $colaborador ) )
				$colaborador = "colaborador";
			
			$mensaje = "Estimado ".$colaborador.",\n\n";
			$mensaje .= "Nos alegra que haya participado en este gran proyecto. Gracias a la informacion que ha aportado, podremos seguir desarrollando esta potente herramienta que servira de apoyo orientativo a futuras y presentes generaciones."."\n\n";
			$mensaje .= "Puede seguir colaborando, aportando informacion profesional de familiares o cercanos. "."\n\n";
			$mensaje .= "Cordialmente,"."\n\n";
			$mensaje .= "El equipo 'queserademi'."."\n\n";
			$mensaje .= "QUESERADEMI"."\n";
			$mensaje .= "http://www.queserademi.es/";
			
			$headers = "From: info@queserademi.es" . "\r\n" . "CC: ".$email;
			$asunto = 'Gracias por colaborar con queserademi';

			mail( $email, $asunto, $mensaje, $headers );
			// Tambien se puede usar PHPmailer
		}

	} else { 
		echo "<h1>Lo sentimos, su colaboracion no se ha recibido correctamente...<h1>\n";
		echo "<h2>Por favor, vuelva a <a href='colabora.php'>intentarlo</a></h2>";
	}
?>
				</div>
			</div>
			
		</div>

		<footer>
	      <div class="row">
	        <div class="col-md-2 col-xs-12 text-center">
	          <a href="index.html">
	            <img class="image-container" src="images/logo.svg">
	          </a>
	        </div>
	        <div class="col-md-2 col-xs-12 text-center">
	          <a href="colabora.php">Colabora con qsdm</a>
	        </div>
	        <div class="col-md-2 col-xs-12 text-center">
	          <a href="porquecolaborar.html">Por qué colaborar</a>
	        </div>
	        <div class="col-md-2 col-xs-12 text-center">
	          <a href="quienessomos.html">Quiénes somos</a>
	        </div>
	        <div class="col-md-2 col-xs-12 text-center">
	          <a href="mailto:info@queserademi.es?subject=Pregunta%20para%20queserademi&body=Hola,%0D%0A%0D%0AQuiero contactar con vosotros para..." target="_top">Contacta con nosotros</a>
	        </div>
	        <div class="col-md-2 col-xs-12 text-center">
	          <ul class="share-buttons">
	            <li><a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.queserademi.es&t=Comparador%20de%20profesiones" target="_blank" title="Share on Facebook" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL) + '&t=' + encodeURIComponent(document.URL)); return false;"><i class="fa fa-facebook-square fa-2x"></i></a></li>
	            <li><a href="https://plus.google.com/share?url=http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Share on Google+" onclick="window.open('https://plus.google.com/share?url=' + encodeURIComponent(document.URL)); return false;"><i class="fa fa-google-plus-square fa-2x"></i></a></li>
	            <li><a href="http://www.linkedin.com/shareArticle?mini=true&url=http%3A%2F%2Fwww.queserademi.es&title=Comparador%20de%20profesiones&summary=&source=http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Share on LinkedIn" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(document.URL) + '&title=' +  encodeURIComponent(document.title)); return false;"><i class="fa fa-linkedin-square fa-2x"></i></a></li>
	            <li><a href="mailto:?subject=Comparador%20de%20profesiones&body=:%20http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Email" onclick="window.open('mailto:?subject=' + encodeURIComponent(document.title) + '&body=' +  encodeURIComponent(document.URL)); return false;"><i class="fa fa-envelope-square fa-2x"></i></a></li>
	          </ul>
	        </div>
	      </div>
	    </footer>
	</body>

</html>
<?php                         
}
?>