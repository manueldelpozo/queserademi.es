<?php 
try { 
  require('conexion.php');

  $tablas = array( 
    'salarios'      => array('s_junior_min', 's_junior_max', 's_intermedio_min', 's_intermedio_max', 's_senior_min', 's_senior_max'),
    'empleabilidad' => array('parados', 'contratados', 'mes', 'anyo'),
    'capacidades'   => array('c_analisis', 'c_comunicacion', 'c_equipo', 'c_forma_fisica', 'c_objetivos', 'c_persuasion'),
    'info'          => array('nombre_ppal', 'descripcion'),
    'satisfaccion'  => array('experiencia','grado_satisfaccion'),
    'formaciones'   => array('f_nombre_ppal', 'f_nombre_alt', 'f_descripcion', 'duracion_academica', 'duracion_real', 'acceso', 'nivel')
  );

  function consulta( $id_profesion, $tabla, $tablas, $pdo ) {
    $consulta = "SELECT";
    $tabla_ref = $tabla[0];
    foreach ( $tablas[$tabla] as $campo) {
      //$consulta .= $campo." AS ".$tabla_ref."_".$campo.", ";
      $consulta .= " ".$campo.",";
    }
    $consulta = substr($consulta, 0, -1);

    $where_id_profesion = " WHERE id_profesion = ";
    if ($tabla == 'info') {
      $tabla = "profesiones_test";
      $where_id_profesion = " WHERE id = ";
    } else if ($tabla == 'formaciones') {
      $where_id_profesion = " f INNER JOIN profesiones_formaciones pf ON f.id = pf.id_formacion INNER JOIN profesiones_test p ON p.id = pf.id_profesion WHERE p.id = ";
    }

    $consulta .= " FROM ".$tabla.$where_id_profesion.$id_profesion.";";
    echo $consulta.'<br>';
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
      $profesion = $nombre_ppal = $nombre_alt = $id_profesion = '';
      $nombre_ppal    = $nombre['nombre_ppal']; // por defecto no se da valor al nombre ppal
      $nombre_alt     = $nombre['nombre_alt'];
      $id_profesion   = $nombre['id_profesion'];
      echo '<ul><li>'.$nombre_ppal.'</li><li>'.$nombre_alt.'</li><li>'.$id_profesion.'</li></ul>';

      $repetir = false;  //niega la repeticion 
      $profesion = $nombre_alt;
      echo '<h1>alt: '.$profesion.'</h1>';
      // coger el nombre ppal si el nombre aun no ha sido usado
      if ( !in_array($id_profesion, $nombres_usados, TRUE) ) { // solo una vez!!!
        //$nombre_ppal = $nombre['nombre_ppal']; 
        $repetir = true; // repetimos while en este caso
        $profesion = $nombre_ppal; // en este caso $nombre sera el nombre_ppal en lugar del alternativo
        echo '<h1>ppal: '.$profesion.'</h1>';
      } 
      // incluir profesion en nombres usados
      array_push($nombres_usados, $id_profesion);

      /*$filas_salarios       = consulta( $id_profesion, 'salarios', $tablas, $pdo);
      $filas_empleabilidad  = consulta( $id_profesion, 'empleabilidad', $tablas, $pdo);
      $filas_capacidades    = consulta( $id_profesion, 'capacidades', $tablas, $pdo);
      $filas_info           = consulta( $id_profesion, 'info', $tablas, $pdo);
      $filas_satisfaccion   = consulta( $id_profesion, 'satisfaccion', $tablas, $pdo);
      $filas_formaciones    = consulta( $id_profesion, 'formaciones', $tablas, $pdo);*/
      echo '<h1>nombre: '.$profesion.'</h1>';

      foreach ($tablas as $tabla => $value) {
        $filas = 'filas_'.$tabla;
        $$filas = consulta( $id_profesion, $tabla, $tablas, $pdo);
      }

      // darle url al html estatico
      // setlocale(LC_ALL, 'en_GB'); esta configuracion evitara tambien las ene espanola
      $profesion_noacentos = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', mb_strtolower($profesion, 'UTF-8')); // eliminar acentos y transformar en minusculas
      $profesion_underscore = str_replace(' ', '_', $profesion_noacentos); // remplazar espacios en blanco por underscore
      $url_html = "profesiones/" . $profesion_underscore . ".html"; // agregar path y extension 
      // crear html estatico
      $pagina_html = fopen($url_html, "w+") or die("No se puede crear este documento");

// comenzamos a generar el html como string
$html = '
<!DOCTYPE html>
<html>
  <head>
      <meta http-equiv="Content-Language" content="es">
      <meta charset="utf-8">
      <title>'; $html .= $profesion . '</title>
      <meta name="description" content="'; $html .= $profesion . '">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="apple-mobile-web-app-capable" content="yes" />
      <meta prefix="og: http://ogp.me/ns#" property="og:title" content="'; $html .= $profesion . '" />
      <meta prefix="og: http://ogp.me/ns#" property="og:image" content="../images/logo.png" />
      <meta prefix="og: http://ogp.me/ns#" property="og:url" content="http://www.queserademi.es/'; $html .= $url_html . '" />   
      <link rel="icon" type="image/x-icon" href="../images/logo.png">
      <link rel="stylesheet" href="../css/bootstrap.min.css" />
      <link href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">
      <link rel="stylesheet" href="../css/style.css" />
      <link rel="stylesheet" href="../css/style-comparador.css" />
      <!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
      <script type="text/javascript" src="../js/jquery-2.1.3.js" ></script>
      <script type="text/javascript" src="../js/bootstrap.min.js" ></script>
      <script type="text/javascript" src="../js/typeahead.bundle.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.9.3/typeahead.min.js"></script>
      <script type="text/javascript" src="../js/highcharts.js" ></script>
      <script type="text/javascript" src="../js/highcharts-more.js" ></script>
      <script type="text/javascript" src="../js/modules/exporting.js"></script>
      <script type="text/javascript" src="../js/scripts.js" defer></script> 
      <script type="text/javascript" src="../js/graficas.js" ></script>
  </head>

  <body>

    <!-- Google Tag Manager -->
    <noscript>
      <iframe src="//www.googletagmanager.com/ns.html?id=GTM-WS6V49" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <script>
      (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({"gtm.start":
      new Date().getTime(),event:"gtm.js"});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!="dataLayer"?"&l="+l:"";j.async=true;j.src=
      "//www.googletagmanager.com/gtm.js?id="+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,"script","dataLayer","GTM-WS6V49");
    </script>
    <!-- End Google Tag Manager -->

    <div id="preloader"></div>
    <div class="background-image grayscale"></div>

    <div class="container-full">
      <form id="formulario" role="form" action="comparador.php" method="get" onsubmit="return validacion()">
          <div class="row header">
            <div class="col-xs-12 hidden-sm hidden-md hidden-lg margen"></div>

            <div class="col-md-4">
              <div class="dropdown clearfix">
                <div class="input-group" id="scrollable-dropdown-menu">
                  <input name="profesion_uno" id="buscador" class="typeahead principal center-block form-control input-lg" type="text" data-tipo="profesiones" placeholder="Busca otra profesión y compara" value="'; $html .= $profesion . '" required>
                  <span class="input-group-btn" >
                    <button class="btn btn-default btn-submit" type="submit" style="background-color: rgba(255, 255, 255, 0.6);border-color: rgb(204, 204, 204);height: 50px;position: absolute;top: 0;"><strong>&gt;</strong></button>
                  </span>
                </div>
              </div>
            </div>

            <div class="col-md-4 hidden-sm hidden-xs text-center">
              <a href="index.html">
                <h6 class="sublead">Tu comparador de profesiones</h6>
                <img class="img-responsive" src="../images/logo.svg" height="60px"> 
              </a>
            </div>

            <div class="col-md-4">
              <div class="dropdown clearfix">
                <div class="input-group" id="scrollable-dropdown-menu">
                  <input name="profesion_dos" id="buscador_dos" class="typeahead secundaria center-block form-control input-lg" type="text" data-tipo="profesiones" placeholder="Busca otra profesión y compara" required autofocus>
                  <span class="input-group-btn">
                    <button class="btn btn-default btn-submit" type="submit" style="background-color: rgba(255, 255, 255, 0.6);border-color: rgb(204, 204, 204);height: 50px;position: absolute;top: 0;"><strong>&gt;</strong></button>
                  </span>
                </div>
              </div>
            </div>

          </div> 

          <div class="row body" style="margin-top:5px;height:120%;">
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_salarios" class="grafica"></div>
            </div>
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_capacidades" class="grafica"></div>
            </div>
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_empleabilidad" class="grafica"></div>
            </div>
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_formacion" class="grafica"></div>
            </div>
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_satisfaccion" class="grafica"></div>
            </div>
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container_info" class="grafica"></div>
            </div>
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
                  <img class="img-menu" src="../images/logo.svg" width="35px" height="auto">       
                  </a>
              </div>
              <div class="col-sm-3 col-sm-offset-6 col-xs-3 col-xs-offset-6">
            <button type="button" data-toggle="dropup" aria-expanded="false" class="btn-footer" id="btn-footer-xs" ><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span></button>
          </div>
            </div>
        <div class="col-md-2 col-md-offset-0 hidden-sm hidden-xs col-xs-6 col-xs-offset-3 text-center">
              <a href="index.html"> 
                  <p id="titulo" style="opacity:1;margin-top:-10px;">
                    <img class="image-container" src="../images/logo.svg">
                    <strong>que</strong>sera<strong>de</strong>mi
                  </p>
              </a>
            </div>
          <div class="col-md-10 col-sm-12 col-xs-12 text-center">
              <div class="col-md-2 col-md-offset-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
                <a href="colabora.php">Puedes colaborar</a>
                <span class="hidden-sm hidden-xs separador">|</span>
              </div>
              <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
                <a href="porquecolaborar.html">Por qué colaborar</a>
                <span class="hidden-sm hidden-xs separador">|</span>
              </div>
              <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
                <a href="quienessomos.html">Quiénes somos</a>
                <span class="hidden-sm hidden-xs separador">|</span>
              </div>
              <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
                <a href="mailto:info@queserademi.es?subject=Pregunta%20para%20queserademi&body=Hola,%0D%0A%0D%0AQuiero contactar con vosotros para..." target="_top">Qué nos sugieres</a>
              </div>
              <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu social">
                <ul class="share-buttons">
                  <li><a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.queserademi.es&t=Comparador%20de%20profesiones" target="_blank" title="Share on Facebook" onclick="window.open("https://www.facebook.com/sharer/sharer.php?u="" + encodeURIComponent(document.URL) + "&t="" + encodeURIComponent(document.URL)); return false;"><i class="fa fa-facebook-square fa-2x"></i></a></li>
                  <li><a href="https://plus.google.com/share?url=http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Share on Google+" onclick="window.open("https://plus.google.com/share?url=" + encodeURIComponent(document.URL)); return false;"><i class="fa fa-google-plus-square fa-2x"></i></a></li>
                  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url=http%3A%2F%2Fwww.queserademi.es&title=Comparador%20de%20profesiones&summary=&source=http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Share on LinkedIn" onclick="window.open("http://www.linkedin.com/shareArticle?mini=true&url=" + encodeURIComponent(document.URL) + "&title=" +  encodeURIComponent(document.title)); return false;"><i class="fa fa-linkedin-square fa-2x"></i></a></li>
                  <li><a href="mailto:?subject=Comparador%20de%20profesiones&body=:%20http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Email" onclick="window.open("mailto:?subject=" + encodeURIComponent(document.title) + "&body=" +  encodeURIComponent(document.URL)); return false;"><i class="fa fa-envelope-square fa-2x"></i></a></li>
                </ul>
              </div>
            </div>
            <div class="col-md-10 col-md-offset-2 col-sm-12 col-xs-12 terminos text-center">
              <div class="col-md-2 col-md-offset-6 col-sm-12 col-xs-12 hidden-xs mobile-menu">
                <a rel="license" href="http://ec.europa.eu/justice/data-protection/index_es.htm">Privacidad de datos</a>
                <span class="hidden-sm hidden-xs separador">|</span>
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
    '; 
    // puede que tenga que meter el script de highchart a pelo
    
$btn_colabora_s_1 = $btn_colabora_s_2 = 0;
$s_junior_min = $s_junior_max = $s_intermedio_min = $s_intermedio_max = $s_senior_min = $s_senior_max = 0;
$s_junior_min_dos = $s_junior_max_dos = $s_intermedio_min_dos = $s_intermedio_max_dos = $s_senior_min_dos = $s_senior_max_dos = 0;

function imprimirSeriesSal($fila, $btn, $btn_colabora) {
    if( !is_null($fila) && !$fila == 0 )
        return $fila;
    else
        $btn_colabora = $btn + 1;
}

foreach( $tablas['salarios'] as $n => $rango) {
    $$rango = imprimirSeriesSal($filas_salarios[0][$rango], $n, $btn_colabora_s_1);
    if( isset($profesion_dos) && !empty($profesion_dos) ){
        $rango_dos = $rango . '_dos';
        $$rango_dos = imprimirSeriesSal($filas_salarios_dos[0][$rango], $n, $btn_colabora_s_2);
    }
}


$script = 'var salarios = ['.
    '[0, '. $s_junior_min .','. $s_junior_max .'],'.
    '[2, '. $s_junior_min .','. $s_junior_max .'],'.
    '[5, '. $s_intermedio_min .','. $s_intermedio_max .'],'. 
    '[20, '. $s_senior_min .','. $s_senior_max .']'.
'], medias = ['.
    '[0,'. ($s_junior_min + $s_junior_max) / 2 .'],'.
    '[2,'. ($s_junior_min + $s_junior_max) / 2 .'],'.
    '[5,'. ($s_intermedio_min + $s_intermedio_max) / 2 .'],'. 
    '[20,'. ($s_senior_min + $s_senior_max) / 2 .']'.
'];';


$script .= "$('#container_salarios').highcharts({

    chart: {
        backgroundColor:'rgba(255, 255, 255, 0)',
        spacingBottom: 20,
        spacingTop: 20,
        spacingLeft: 20,
        spacingRight: 20,
        width: null,
        height: 380
    },

    title: {
        text: 'SALARIO',
        align: 'center'
    },

    legend: { 
        enable: false 
    },

    xAxis: {
        //categories: ['JUNIOR','INTERMEDIO','SENIOR']
        title: {
            text: 'EXPERIENCIA ' + '(años)'.toUpperCase()
        }
    },

    yAxis: {
        title: {
            text: 'SALARIO NETO (€/mes)'
        }
    },

    tooltip: {
        headerFormat: '<strong>{point.x} años de experiencia</strong><br>',
        //pointFormat: '{point.x} años de experiencia',
        crosshairs: true,
        shared: true,
        valueSuffix: ' €'
    },

    credits: {
        enabled: false
    },

    plotOptions: {
        arearange: {
            fillOpacity: 0.5
        }
    },

    series: [
        {
            name: '". $profesion ."',
            data: medias,
            zIndex: 1,
            marker: {
                fillColor: 'white',
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[0]
            }
        }, {
            name: 'Rango salarial',
            data: salarios,
            type: 'arearange',
            lineWidth: 0,
            linkedTo: '<?php echo $profesion; ?>',
            fillOpacity: 0.3,
            zIndex: 0
        }
    ]
});";

if( $btn_colabora_s_1 > 0 ) { 
    $script .= "var capa_aviso = '<div class=\"capa-aviso\">';
    capa_aviso += '<div class=\"cerrar-aviso\"><a href=\"#\"><img class=\"icon\" src=\"images/cross.svg\"></img></a></div>';
    capa_aviso += '<div class=\"col-md-10 col-md-offset-1\">';
    capa_aviso += '<h3>Aún no tenemos imformación suficiente!</h3>';

        capa_aviso += '<p class=\"text-center\">Ayúdanos a completar información sobre <strong>salario</strong> de la profesión<br>';
        capa_aviso += '<strong>". mb_strtoupper($profesion,"UTF-8") ."</strong></p>';
        capa_aviso += '<a href=\"colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);\">Colabora!</a>';

    capa_aviso += '</div></div>';

    $('#container_salarios').append(capa_aviso);";
} 
      /*$html .=*/  //include('../js/grafica_salarios.js');
      //$html .= include("js/grafica_capacidades.js");
      /*$html .= '"' .*/ //include('../js/grafica_empleabilidad.js'); 
      /*$html .= '"' .*/ //include('../js/grafica_formacion.js');
      //$script = include('js/grafica_satisfaccion.js');
      /*$html .= '"' .*/ //include('../js/grafica_info.js'); 
    $html .= $script.'
  </script>
</html>';
    echo $script;
    // guardar html
    fwrite($pagina_html, $html);
    fclose($pagina_html);

    } // end while
  } // end foreach

} catch( Exception $e ) {
  die('Error: '.$e->GetMessage());
}
?>