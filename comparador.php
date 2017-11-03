<?php 
//eliminar el limite de ejecucion
set_time_limit(0);

//try {
  require('conexion.php');

  $tablas = array( 
    'salarios'            => array('s_princ_min', 's_princ_med', 's_princ_max', 's_junior_min', 's_junior_med', 's_junior_max', 's_intermedio_min', 's_intermedio_med', 's_intermedio_max', 's_senior_min', 's_senior_med', 's_senior_max'),
    'empleabilidad'       => array('parados', 'contratados', 'mes', 'anyo'),
    'capacidades'         => array('c_analisis', 'c_comunicacion', 'c_equipo', 'c_forma_fisica', 'c_objetivos', 'c_persuasion'),
    'formaciones'         => array('id', 'f_nombre_ppal','f_descripcion','duracion_academica','duracion_real'),
    'info'                => array('descripcion'),
    //'satisfaccion'      => array('experiencia','grado_satisfaccion'),
  );

  function consulta($profesion, $tabla, $tablas, $pdo) {
    $consulta = "SELECT ";
    $tabla_ref = ($tabla == 'info') ? 'p' : $tabla[0];

    foreach ($tablas[$tabla] as $campo) {
      $consulta .= $tabla_ref . '.' . $campo . ', ';
    }
    $consulta = substr($consulta, 0, -2);

    if ($tabla == 'info') {
      $consulta .= " FROM profesiones p 
                    WHERE p.nombre_ppal = '$profesion'";
    } else if ($tabla == 'formaciones') {
      $consulta .= " FROM formaciones f
                    INNER JOIN profesiones_formaciones pf on f.cod = pf.id_formacion
                    INNER JOIN profesiones p on pf.id_profesion = p.id
                    WHERE p.nombre_ppal = '$profesion'";
    } else {
      $consulta .= " FROM profesiones p , ".$tabla." ".$tabla_ref." 
                    WHERE p.id = ".$tabla_ref.".id_profesion AND p.nombre_ppal = '$profesion'";
    }
    
    $rs = $pdo->prepare($consulta);
    $rs->execute();
    $filas = $rs->fetchAll();
    
    return $filas;
  }

  function consultaPDO($campo, $consulta, $pdo) {
    $rs = $pdo->prepare($consulta);
    $rs->execute();
    $count = $rs->rowCount();
    $row = $rs->fetchAll();
    return ($count > 0) ? $row[0][$campo] : false;
  }

  function getNombrePpal($nombre_alt, $pdo) {
    $consulta_alt = "SELECT nombre_ppal FROM profesiones WHERE id = (
                        SELECT id_profesion FROM nombres_alt WHERE nombre_alt LIKE '$nombre_alt'
                      ) ";
    return consultaPDO('nombre_ppal', $consulta_alt, $pdo);
  }

  function getIdProfesion($nombre_prof, $pdo) {
    $consulta_id = "SELECT id FROM profesiones WHERE nombre_ppal LIKE '$nombre_prof'";
    return consultaPDO('id', $consulta_id, $pdo);
  } 

  if (isset($_GET['profesion']) && !empty($_GET['profesion'])) {
    $n_alt = ucfirst(mb_strtolower(getNombrePpal($_GET['profesion'], $pdo),'UTF-8'));
    $profesion = $n_alt ? $n_alt : $_GET['profesion'];
    $id_profesion = getIdProfesion($profesion, $pdo);
  }
  if (isset($_GET['profesion_dos']) && !empty($_GET['profesion_dos'])) { 
    $n_alt_dos = ucfirst(mb_strtolower(getNombrePpal($_GET['profesion_dos'], $pdo),'UTF-8'));
    $profesion_dos = $n_alt_dos ? $n_alt_dos : $_GET['profesion_dos'];
    $id_profesion_dos = getIdProfesion($profesion_dos, $pdo);
  } 

  foreach ($tablas as $tabla => $value) {
    if( isset($profesion) ) {
      $filas = 'filas_'.$tabla;
      $$filas = consulta($profesion, $tabla, $tablas, $pdo);
    }
    if( isset($profesion_dos) ) { 
      $filas_dos = 'filas_'.$tabla.'_dos';
      $$filas_dos = consulta($profesion_dos, $tabla, $tablas, $pdo);
    }
  }

  function verDatos($tablas) {
    foreach ($tablas as $tabla => $campos) {
      foreach ($campos as $campo) {
        $filtab = 'filas_'.$tabla;
        foreach ($$filtab as $fila) {
          echo $campo.": ".$fila[$campo]."<br>";
        }
      }
    }
  }

?>
<!DOCTYPE html>
<html>
  <head>
    <title>queserademi.com | Comparador de profesiones</title>
    <meta name="description" content="Comparador de profesiones">
    <!--Compatibilidad y móvil-->
    <meta http-equiv="Content-Language" content="es">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noodp">
    <meta name="viewport" content="width=device-width, initial-scale = 1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#c00">
    <!--OGs-->
    <link rel="canonical" href="https://queserademi.com/comparador.php">
    <meta property="og:locale" content="es_ES">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Orientación Laboral y Comparador de Profesiones | queserademi">
    <meta property="og:description" content="Comparador de profesiones">
    <meta property="og:url" content="https://queserademi.com/comparador.php">
    <meta property="og:site_name" content="queserademi">
    <meta property="og:image" content="https://queserademi.com/images/logo.png">
    <!--Links css-->
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style-comparador.css">
    <script src="https://www.w3schools.com/lib/w3.js"></script>
    <!-- Google Tag Manager -->
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-64706657-1', 'auto');
      ga('send', 'pageview');

      (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
      new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
      'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','GTM-KSJZX5B');
    </script>
    <!-- End Google Tag Manager -->
  </head>
  <body>
    <!-- Google Tag Manager (noscript) -->
    <noscript>
      <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KSJZX5B"
      height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!-- Facebook script -->
    <div id="fb-root"></div>
    <script>
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = 'https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v2.10';
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    </script>
    <!-- End Facebook script -->
    <div id="preloader"></div>
    <div id="popUp" class="text-center" hidden>
        <div id="popUpBox" class="col-md-6 col-md-offset-3 col-xs-12">
            <div class="cerrar-popup">
                <a href="#"><img class="icon" src="images/cross.svg"></img>
                </a>
            </div>
            <div class="col-md-10 col-md-offset-1">
                <h2>Te gustaría colaborar?</h2>
                <h3>Con tu ayuda podremos mejorar <span id="titulo"><strong>que</strong>sera<strong>de</strong>mi</span></h3>
                <br>
                <a href="colabora.php" class="btn btn-aviso" style="border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);">Colabora!</a>
                <a href="porquecolaborar.html" class="btn btn-aviso" style="border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);">Por qué colaborar?</a>
            </div>
        </div>
        <div id="popUpBg"></div>
    </div>
  
      <form id="formulario" role="form" action="comparador.php">

          <div class="row header">

            <div class="col-md-4 ux-mobile-input-container">
              <div class="dropdown clearfix">
                <div class="input-group" id="scrollable-dropdown-menu">
                  <input name="profesion" id="buscador" class="typeahead principal center-block form-control input-lg" type="text" data-tipo="profesiones" placeholder="Busca otra profesión y compara" autofocus required value="<?php echo @$profesion; ?>" spellcheck="true" autocomplete="off">
                </div>
              </div>
            </div>

            <div class="col-md-4 hidden-sm hidden-xs text-center">
              <a href="https://queserademi.com">
                <h6 class="sublead qsdm-color-white">tu <strong>comparador</strong> de profesiones</h6>
                <img class="img-responsive" src="images/logo-blanco.svg"> 
              </a>
            </div>

            <div class="col-md-4 ux-mobile-input-container">
              <div class="dropdown clearfix">
                <div class="input-group" id="scrollable-dropdown-menu">
                  <input name="profesion_dos" id="buscador_dos" class="typeahead secundaria center-block form-control input-lg" type="text" data-tipo="profesiones" placeholder="Busca otra profesión y compara" required autofocus value="<?php echo @$profesion_dos; ?>" spellcheck="true" autocomplete="off" >
                </div>
              </div>
            </div>

          </div> 

          <div class="col-xs-12 margen"></div>
          <div class="col-xs-12 margen"></div>

          <div class="row body" style="margin-top:5px;height:120%;">
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_empleabilidad" class="grafica"></div>
            </div>
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_salarios" class="grafica"></div>
            </div>
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_capacidades" class="grafica"></div>
            </div>
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_formacion" class="grafica"></div>
            </div>
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_noticias" class="grafica"></div>
            </div>
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_info" class="grafica"></div>
            </div>
            <!--div class="col-md-6 col-xs-12 text-center">
              <div id="container_satisfaccion" class="grafica"></div>
            </div--> 
          </div> 

      </form>

      <div class="col-xs-12 margen"></div>
  

      <footer w3-include-html="footer.html"></footer>
      <script type="text/javascript">
          w3.includeHTML();
      </script>

      <!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
      <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
      <script type="text/javascript" src="js/jquery-2.1.3.js"></script>
      <!--script type="text/javascript" src="js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.js"></script-->
      <script type="text/javascript" src="js/bootstrap.min.js"></script>
      <script type="text/javascript" src="js/typeahead.0.9.3.min.js"></script>
      <script type="text/javascript" src="js/highcharts.js"></script>
      <script type="text/javascript" src="js/highcharts-more.js"></script>
      <script type="text/javascript" src="js/modules/exporting.js"></script>
      <script type="text/javascript" src="js/scripts.js" defer></script> 
      <script type="text/javascript" src="js/scripts-combobox.js"></script>  
      <script type="text/javascript" src="js/graficas.js"></script>

      <script type="text/javascript" async><?php include('js/grafica_empleabilidad.js'); ?></script>
      <script type="text/javascript" async><?php include('js/grafica_salarios.js'); ?></script>
      <script type="text/javascript" async><?php include('js/grafica_capacidades.js'); ?></script>
      <script type="text/javascript" async><?php include('js/grafica_formacion.js'); ?></script>
      <script type="text/javascript" async><?php include('js/grafica_noticias.js'); ?></script>
      <script type="text/javascript" async><?php include('js/grafica_info.js'); ?></script>
      <!--script type="text/javascript" async><?php //include('js/grafica_satisfaccion.js'); ?></script-->
  </body>
</html>

<?php
/*} catch( Exception $e ) {
  die('Error: '.$e->GetMessage());
}*/
?>