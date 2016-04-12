<?php 
//eliminar el limite de ejecucion
set_time_limit(0);

//try {
  require('conexion.php');

  $tablas = array( 
    'salarios'      => array('s_princ_min', 's_princ_med', 's_princ_max', 's_junior_min', 's_junior_med', 's_junior_max', 's_intermedio_min', 's_intermedio_med', 's_intermedio_max', 's_senior_min', 's_senior_med', 's_senior_max'),
    'empleabilidad' => array('parados', 'contratados', 'mes', 'anyo'),
    'capacidades'   => array('c_analisis', 'c_comunicacion', 'c_equipo', 'c_forma_fisica', 'c_objetivos', 'c_persuasion'),
    'info'          => array('descripcion'),
    'satisfaccion'  => array('experiencia','grado_satisfaccion'),
    'formaciones'   => array('f_nombre_ppal','f_nombre_alt','duracion_academica','duracion_real','acceso','nivel')
  );

  function consulta($profesion, $tabla, $tablas, $pdo) {
    $consulta = "SELECT ";
    foreach ($tablas[$tabla] as $campo) {
      $consulta .= $campo . ",";
    }
    $consulta = substr($consulta, 0, -1);
    
    $tabla_ref = $tabla[0];

    if ($tabla == 'info')
      $where = "WHERE ";
    else if ($tabla == 'formaciones')
      $where = "INNER JOIN profesiones_formaciones pf ON p.id = pf.id_profesion INNER JOIN formaciones f ON f.cod = pf.id_formacion WHERE ";
    else
      $where = ", ".$tabla." ".$tabla_ref." WHERE p.id = ".$tabla_ref.".id_profesion AND ";

    $consulta .= " FROM profesiones_test p ".$where."p.nombre_ppal LIKE '$profesion'";

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
    $consulta_alt = "SELECT nombre_ppal FROM profesiones_test WHERE id = (
                        SELECT id_profesion FROM nombres_alt WHERE nombre_alt LIKE '$nombre_alt'
                      ) ";
    return consultaPDO('nombre_ppal', $consulta_alt, $pdo);
  }

  function getIdProfesion($nombre_prof, $pdo) {
    $consulta_id = "SELECT id FROM profesiones_test WHERE nombre_ppal LIKE '$nombre_prof'";
    return consultaPDO('id', $consulta_id, $pdo);
  } 

  if (isset($_GET['profesion'])) {
    $n_alt = ucfirst(mb_strtolower(getNombrePpal($_GET['profesion'], $pdo),'UTF-8'));
    $profesion = $n_alt ? $n_alt : $_GET['profesion'];
    $id_profesion = getIdProfesion($profesion, $pdo);
  }
  if (isset($_GET['profesion_dos'])) { 
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
      <meta http-equiv="Content-Language" content="es">
      <meta charset="utf-8">
      <title>Comparador de Profesiones</title>
      <meta name="description" content="Comparador de profesiones queserademi">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="apple-mobile-web-app-capable" content="yes" />

      <meta prefix="og: http://ogp.me/ns#" property="og:title" content="Comparador de profesiones queserademi" />
      <meta prefix="og: http://ogp.me/ns#" property="og:image" content="http://www.queserademi.es/images/logo.png" />
      <meta prefix="og: http://ogp.me/ns#" property="og:url" content="http://www.queserademi.es/comparador.php" />
      <link rel="icon" type="image/x-icon" href="images/logo.png">
      <link rel="stylesheet" href="css/bootstrap.min.css" />
      <link href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">
      <link rel="stylesheet" href="css/style.css" />
      <link rel="stylesheet" href="css/style-comparador.css" />
      <!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
      <script type="text/javascript" src="js/jquery-2.1.3.js" ></script>
      <script type="text/javascript" src="js/bootstrap.min.js" ></script>
      <script type="text/javascript" src="js/typeahead.bundle.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.9.3/typeahead.min.js"></script>
      <script type="text/javascript" src="js/highcharts.js" ></script>
      <script type="text/javascript" src="js/highcharts-more.js" ></script>
      <script type="text/javascript" src="js/modules/exporting.js"></script>
      <script type="text/javascript" src="js/scripts.js" defer></script>   
      <script type="text/javascript" src="js/graficas.js" ></script>
  </head>
  <body>
    <!-- Google Tag Manager -->
    <noscript>
      <iframe src="//www.googletagmanager.com/ns.html?id=GTM-WS6V49" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <script>
      (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
      new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
      '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','GTM-WS6V49');
    </script>
    <!-- End Google Tag Manager -->
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
    <div class="background-image grayscale"></div>
    <div class="container-full">
      <form id="formulario" role="form" action="comparador.php" method="get">

          <div class="row header">
            <div class="col-xs-12 hidden-sm hidden-md hidden-lg margen"></div>

            <div class="col-md-4">
              <div class="dropdown clearfix">
                <div class="input-group" id="scrollable-dropdown-menu">
                  <input name="profesion" id="buscador" class="typeahead principal center-block form-control input-lg" type="text" data-tipo='profesiones' placeholder="Busca otra profesión y compara" value="<?php echo @$profesion; ?>" required> 
                  <span class="input-group-btn" >
                    <button class="btn btn-default btn-submit" style="background-color: rgba(255, 255, 255, 0.6);border-color: rgb(204, 204, 204);height: 50px;position: absolute;top: 0;"><strong>&gt;</strong></button>
                  </span>
                </div>
              </div>
            </div>

            <div class="col-md-4 hidden-sm hidden-xs text-center">
              <a href="index.html">
                <h6 class="sublead">Tu comparador de profesiones</h6>
                <img class="img-responsive" src="images/logo.svg" height="60px"> 
              </a>
            </div>

            <div class="col-md-4">
              <div class="dropdown clearfix">
                <div class="input-group" id="scrollable-dropdown-menu">
                  <input name="profesion_dos" id="buscador_dos" class="typeahead secundaria center-block form-control input-lg" type="text" data-tipo='profesiones' placeholder="Busca otra profesión y compara" value="<?php echo @$profesion_dos; ?>" required autofocus>
                  <span class="input-group-btn" >
                    <button class="btn btn-default btn-submit" style="background-color: rgba(255, 255, 255, 0.6);border-color: rgb(204, 204, 204);height: 50px;position: absolute;top: 0;"><strong>&gt;</strong></button>
                  </span>
                </div>
              </div>
            </div>

          </div> 

          <div class="row body" style="margin-top:5px;height:120%;">
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_empleabilidad" class="grafica"></div>
            </div>
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_capacidades" class="grafica"></div>
            </div>
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_salarios" class="grafica"></div>
            </div>
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_info" class="grafica"></div>
            </div>
            <!--div class="col-md-6 col-xs-12 text-center">
              <div id="container_formacion" class="grafica"></div>
            </div>
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_satisfaccion" class="grafica"></div>
            </div> 
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_noticias" class="grafica"></div>
            </div-->
          </div> 

      </form>
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
              <div class="col-md-2 col-md-offset-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
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

  <script type="text/javascript" async>
    <?php 
      include('js/grafica_empleabilidad.js');  
      include('js/grafica_capacidades.js');
      include('js/grafica_salarios.js');
      include('js/grafica_info.js'); 
      //include('js/grafica_formacion.js');
      //include('js/grafica_satisfaccion.js');
      //include('js/grafica_noticias.js');
    ?>
  </script>

</html>

<?php
/*} catch( Exception $e ) {
  die('Error: '.$e->GetMessage());
}*/
?>