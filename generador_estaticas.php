<?php 
try { 
  require('conexion.php');

  $tablas = array( 
    'salarios'      => array('s_junior_min', 's_junior_max', 's_intermedio_min', 's_intermedio_max', 's_senior_min', 's_senior_max'),
    'empleabilidad' => array('parados', 'contratados', 'mes', 'anyo'),
    'capacidades'   => array('c_analisis', 'c_comunicacion', 'c_equipo', 'c_forma_fisica', 'c_objetivos', 'c_persuasion'),
    'info'          => array('nombre_ppal', 'descripcion'),
    'satisfaccion'  => array('experiencia','grado_satisfaccion'),
    'formaciones'   => array('nombre_ppal', 'nombre_alt', 'duracion_academica', 'duracion_real', 'acceso', 'nivel')
  );

  function consulta( $id_profesion, $tabla, $tablas, $pdo ) {
    $consulta = "SELECT ";
    $tabla_ref = $tabla[0];
    foreach ( $tablas[$tabla] as $campo) {
      $consulta .= $campo." AS ".$tabla_ref."_".$campo.", ";
    }
    $id_profesion = "id_profesion = ".$id_profesion;
    if ($tabla == 'info') {
      $tabla = "profesiones_test";
      str_replace("id_profesion", "id", $id_profesion);
    }
    $consulta .= "FROM ".$tabla." WHERE ".$id_profesion;
    $rs = $pdo->prepare($consulta);
    $rs->execute();
    $filas = $rs->fetchAll();
    return $filas;
  }

  // Primero, consulta de nombres principales y alternativos
  $consulta_nombres = "SELECT id_profesion, nombre_ppal, nombre_alt FROM profesiones_test p, nombres_alt n WHERE p.id = n.id_profesion";
  $rs_nombres = $pdo->prepare($consulta_nombres);
  $rs_nombres->execute();
  $nombres = $rs_nombres->fetchAll();

  $nombres_usados = array();

  // bucle de todos los nombres
  foreach ($nombres as $nombre) {
    
    $repetir = true; // accede para cada iteracion del foreach
    while($repetir) { // mientras haya un nombre ppal se repetira este bucle
      
      $nombre_ppal = null; // por defecto no se da valor al nombre ppal
      $nombre_alt = $nombre['nombre_alt'];
      $id_profesion = $nombre['id_profesion'];
      $repetir = false;  //niega la repeticion 
      $nombre = $nombre_alt;
      // coger el nombre ppal si el nombre aun no ha sido usado
      if ( !in_array($id_profesion, $nombres_usados, TRUE) ) { // solo una vez!!!
        $nombre_ppal = $nombre['nombre_ppal']; 
        $repetir = true; // repetimos while en este caso
        $nombre = $nombre_ppal; // en este caso $nombre sera el nombre_ppal en lugar del alternativo
      } 
      // incluir profesion en nombres usados
      array_push($nombres_usados, $id_profesion);

      /*$filas_salarios       = consulta( $id_profesion, 'salarios', $tablas, $pdo);
      $filas_empleabilidad  = consulta( $id_profesion, 'empleabilidad', $tablas, $pdo);
      $filas_capacidades    = consulta( $id_profesion, 'capacidades', $tablas, $pdo);
      $filas_info           = consulta( $id_profesion, 'info', $tablas, $pdo);
      $filas_satisfaccion   = consulta( $id_profesion, 'satisfaccion', $tablas, $pdo);
      $filas_formaciones    = consulta( $id_profesion, 'formaciones', $tablas, $pdo);*/
      foreach ($tablas as $tabla => $value) {
        $filas = 'filas_'.$tabla;
        $$filas = consulta( $id_profesion, $tabla, $tablas, $pdo);
      }
    
// comenzamos a generar el html como string
$html = '
<!DOCTYPE html>\n
<html>\n
  <head>\n
      <meta http-equiv="Content-Language" content="es">\n
      <meta charset="utf-8">\n
      <title>'; $html .= $nombre . '</title>\n
      <meta name="description" content="'; $html .= $nombre . '">\n
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />\n
      <meta name="viewport" content="width=device-width, initial-scale=1.0">\n
      <meta name="apple-mobile-web-app-capable" content="yes" />\n
      <meta prefix="og: http://ogp.me/ns#" property="og:title" content="'; $html .= $nombre . '" />\n
      <meta prefix="og: http://ogp.me/ns#" property="og:image" content="http://www.queserademi.es/images/logo.png" />\n
      <meta prefix="og: http://ogp.me/ns#" property="og:url" content="http://www.queserademi.es/profesiones/'; $html .= $nombre . '" />\n   
      <link rel="icon" type="image/x-icon" href="images/logo.png">\n
      <link rel="stylesheet" href="css/bootstrap.min.css" />\n
      <link href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">\n
      <link rel="stylesheet" href="css/style.css" />\n
      <link rel="stylesheet" href="css/style-comparador.css" />\n
      <!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->\n
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>\n
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>\n
      <![endif]-->
      <script type="text/javascript" src="js/jquery-2.1.3.js" ></script>\n
      <script type="text/javascript" src="js/bootstrap.min.js" ></script>\n
      <script type="text/javascript" src="js/typeahead.bundle.js"></script>\n
      <script src="//cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.9.3/typeahead.min.js"></script>\n
      <script type="text/javascript" src="js/highcharts.js" ></script>\n
      <script type="text/javascript" src="js/highcharts-more.js" ></script>\n
      <script type="text/javascript" src="js/modules/exporting.js"></script>\n
      <script type="text/javascript" src="js/scripts.js" defer></script>\n 
      <script type="text/javascript" src="js/graficas.js" ></script>\n
  </head>\n\n

  <body>\n\n

    <!-- Google Tag Manager -->\n
    <noscript>\n
      <iframe src="//www.googletagmanager.com/ns.html?id=GTM-WS6V49" height="0" width="0" style="display:none;visibility:hidden"></iframe>\n
    </noscript>\n
    <script>\n
      (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({"gtm.start":
      new Date().getTime(),event:"gtm.js"});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!="dataLayer"?"&l="+l:"";j.async=true;j.src=
      "//www.googletagmanager.com/gtm.js?id="+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,"script","dataLayer","GTM-WS6V49");\n
    </script>\n
    <!-- End Google Tag Manager -->\n\n

    <div id="preloader"></div>\n
    <div class="background-image grayscale"></div>\n\n

    <div class="container-full">\n\n
      <form id="formulario" role="form" action="comparador.php" method="get" onsubmit="return validacion()">\n
          <div class="row header">\n
            <div class="col-xs-12 hidden-sm hidden-md hidden-lg margen"></div>\n

            <div class="col-md-4">\n
              <div class="dropdown clearfix">\n
                <div class="input-group" id="scrollable-dropdown-menu">\n
                  <input name="profesion_uno" id="buscador" class="typeahead principal center-block form-control input-lg" type="text" data-tipo="profesiones" placeholder="Busca otra profesión y compara" value="'; $html .= $nombre . '" required>\n
                  <span class="input-group-btn" >\n
                    <button class="btn btn-default btn-submit" type="submit" style="background-color: rgba(255, 255, 255, 0.6);border-color: rgb(204, 204, 204);height: 50px;position: absolute;top: 0;"><strong>&gt;</strong></button>\n
                  </span>\n
                </div>\n
              </div>\n
            </div>\n

            <div class="col-md-4 hidden-sm hidden-xs text-center">\n
              <a href="index.html">\n
                <h6 class="sublead">Tu comparador de profesiones</h6>\n
                <img class="img-responsive" src="images/logo.svg" height="60px">\n 
              </a>\n
            </div>\n

            <div class="col-md-4">\n
              <div class="dropdown clearfix">\n
                <div class="input-group" id="scrollable-dropdown-menu">\n
                  <input name="profesion_dos" id="buscador_dos" class="typeahead secundaria center-block form-control input-lg" type="text" data-tipo="profesiones" placeholder="Busca otra profesión y compara" required autofocus>\n
                  <span class="input-group-btn">\n
                    <button class="btn btn-default btn-submit" type="submit" style="background-color: rgba(255, 255, 255, 0.6);border-color: rgb(204, 204, 204);height: 50px;position: absolute;top: 0;"><strong>&gt;</strong></button>\n
                  </span>\n
                </div>\n
              </div>\n
            </div>\n

          </div>\n\n 

          <div class="row body" style="margin-top:5px;height:120%;">\n
            <div class="col-md-6 col-xs-12 text-center">\n
              <div id="container_salarios" class="grafica"></div>\n
            </div>\n
            <div class="col-md-6 col-xs-12 text-center">\n
              <div id="container_capacidades" class="grafica"></div>\n
            </div>\n
            <div class="col-md-6 col-xs-12 text-center">\n
              <div id="container_empleabilidad" class="grafica"></div>\n
            </div>\n
            <div class="col-md-6 col-xs-12 text-center">\n
              <div id="container_formacion" class="grafica"></div>\n
            </div>\n
            <div class="col-md-6 col-xs-12 text-center">\n
              <div id="container_satisfaccion" class="grafica"></div>\n
            </div>\n
            <div class="col-md-6 col-xs-12 text-center">\n
              <div id="container_info" class="grafica"></div>\n
            </div>\n
          </div>\n
      </form>\n
    </div>\n\n

    <footer>\n
      <div class="row">\n
        <div class="col-lg-12 col-md-12 hidden-sm hidden-xs text-center">\n
          <button type="button" data-toggle="dropup" aria-expanded="false" class="btn-footer" id="btn-footer-md" ><span class="caret flecha"></span></button>\n
            </div>\n
            <div class="hidden-lg hidden-md col-sm-12 col-xs-12">\n
              <div class="col-sm-3 col-xs-3 text-center">\n
                <a href="index.html">\n 
                  <img class="img-menu" src="images/logo.svg" width="35px" height="auto">\n       
                  </a>\n
              </div>\n
              <div class="col-sm-3 col-sm-offset-6 col-xs-3 col-xs-offset-6">\n
            <button type="button" data-toggle="dropup" aria-expanded="false" class="btn-footer" id="btn-footer-xs" ><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span></button>\n
          </div>\n
            </div>\n
        <div class="col-md-2 col-md-offset-0 hidden-sm hidden-xs col-xs-6 col-xs-offset-3 text-center">\n
              <a href="index.html">\n 
                  <p id="titulo" style="opacity:1;margin-top:-10px;">\n
                    <img class="image-container" src="images/logo.svg">\n
                    <strong>que</strong>sera<strong>de</strong>mi\n
                  </p>\n
              </a>\n
            </div>\n
          <div class="col-md-10 col-sm-12 col-xs-12 text-center">\n
              <div class="col-md-2 col-md-offset-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">\n
                <a href="colabora.php">Puedes colaborar</a>\n
                <span class="hidden-sm hidden-xs separador">|</span>\n
              </div>\n
              <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">\n
                <a href="porquecolaborar.html">Por qué colaborar</a>\n
                <span class="hidden-sm hidden-xs separador">|</span>\n
              </div>\n
              <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">\n
                <a href="quienessomos.html">Quiénes somos</a>\n
                <span class="hidden-sm hidden-xs separador">|</span>\n
              </div>\n
              <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">\n
                <a href="mailto:info@queserademi.es?subject=Pregunta%20para%20queserademi&body=Hola,%0D%0A%0D%0AQuiero contactar con vosotros para..." target="_top">Qué nos sugieres</a>\n
              </div>\n
              <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu social">\n
                <ul class="share-buttons">\n
                  <li><a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.queserademi.es&t=Comparador%20de%20profesiones" target="_blank" title="Share on Facebook" onclick="window.open("https://www.facebook.com/sharer/sharer.php?u="" + encodeURIComponent(document.URL) + "&t="" + encodeURIComponent(document.URL)); return false;"><i class="fa fa-facebook-square fa-2x"></i></a></li>\n
                  <li><a href="https://plus.google.com/share?url=http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Share on Google+" onclick="window.open("https://plus.google.com/share?url=" + encodeURIComponent(document.URL)); return false;"><i class="fa fa-google-plus-square fa-2x"></i></a></li>\n
                  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url=http%3A%2F%2Fwww.queserademi.es&title=Comparador%20de%20profesiones&summary=&source=http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Share on LinkedIn" onclick="window.open("http://www.linkedin.com/shareArticle?mini=true&url=" + encodeURIComponent(document.URL) + "&title=" +  encodeURIComponent(document.title)); return false;"><i class="fa fa-linkedin-square fa-2x"></i></a></li>\n
                  <li><a href="mailto:?subject=Comparador%20de%20profesiones&body=:%20http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Email" onclick="window.open("mailto:?subject=" + encodeURIComponent(document.title) + "&body=" +  encodeURIComponent(document.URL)); return false;"><i class="fa fa-envelope-square fa-2x"></i></a></li>\n
                </ul>\n
              </div>\n
            </div>\n
            <div class="col-md-10 col-md-offset-2 col-sm-12 col-xs-12 terminos text-center">\n
              <div class="col-md-2 col-md-offset-6 col-sm-12 col-xs-12 hidden-xs mobile-menu">\n
                <a rel="license" href="http://ec.europa.eu/justice/data-protection/index_es.htm">Privacidad de datos</a>\n
                <span class="hidden-sm hidden-xs separador">|</span>\n
              </div>\n
              <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">\n
                <a rel="license" href="https://creativecommons.org/licenses/by/4.0/">Terminos de uso</a>\n
              </div>\n
              <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">\n
                <small>&copy; 2015 queserademi.es</small>\n
              </div>\n
            </div>\n
      </div>\n
    </footer>\n\n

  </body>\n\n

  <script type="text/javascript" async>\n
    '; 
      $html .= '"' . include('js/grafica_salarios.js') . '"\n';
      $html .= '"' . include('js/grafica_capacidades.js') . '"\n';
      $html .= '"' . include('js/grafica_empleabilidad.js') . '"\n'; 
      $html .= '"' . include('js/grafica_formacion.js') . '"\n';
      $html .= '"' . include('js/grafica_satisfaccion.js') . '"\n';
      $html .= '"' . include('js/grafica_info.js') . '"\n'; 
    $html .= '
  </script>
</html>';
   
    // darle url al html estatico
    // setlocale(LC_ALL, 'en_GB'); esta configuracion evitara tambien las ene espanola
    $nombre = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', mb_strtolower($nombre, 'UTF-8')); // eliminar acentos y transformar en minusculas
    $nombre = str_replace(' ', '_', $nombre); // remplazar espacios en blanco por underscore
    $url_html = "profesiones/" . $nombre . ".html"; // agregar path y extension 
    // crear html estatico
    $pagina_html = fopen($url_html, "w+") or die("No se puede crear este documento");
    // guardar html
    fwrite($pagina_html, $html);
    fclose($pagina_html);

    } // end while
  } // end foreach

} catch( Exception $e ) {
  die('Error: '.$e->GetMessage());
}
?>