<?php 
//eliminar el limite de ejecucion
set_time_limit(0);

require('conexion.php');

try { 
  $tablas = array( 
    'salarios'      => array('s_princ_min', 's_princ_med', 's_princ_max', 's_junior_min', 's_junior_med', 's_junior_max', 's_intermedio_min', 's_intermedio_med', 's_intermedio_max', 's_senior_min', 's_senior_med', 's_senior_max'),
    'empleabilidad' => array('parados', 'contratados', 'mes', 'anyo'),
    'capacidades'   => array('c_analisis', 'c_comunicacion', 'c_equipo', 'c_forma_fisica', 'c_objetivos', 'c_persuasion'),
    'info'          => array('descripcion'),
    'satisfaccion'  => array('experiencia','grado_satisfaccion'),
    'formaciones'   => array('f_nombre_ppal', 'f_nombre_alt', 'f_descripcion', 'duracion_academica', 'duracion_real', 'acceso', 'nivel')
  );

  function consulta($id_profesion, $tabla, $tablas, $pdo ) {
    $consulta = "SELECT ";
    foreach ($tablas[$tabla] as $campo) {
      $consulta .= $campo . ",";
    }
    $consulta = substr($consulta, 0, -1);
    
    $tabla_ref = $tabla[0];

    if ($tabla == 'info')
      $where = "WHERE";
    else if ($tabla == 'formaciones')
      $where = "INNER JOIN profesiones_formaciones pf ON p.id = pf.id_profesion INNER JOIN formaciones f ON f.cod = pf.id_formacion WHERE";
    else
      $where = ", ".$tabla." ".$tabla_ref." WHERE p.id = ".$tabla_ref.".id_profesion AND";

    $consulta .= " FROM profesiones_test p ".$where." p.id = ".$id_profesion;
    echo $consulta . '<br>';
    $rs = $pdo->prepare($consulta);
    $rs->execute();
    $filas = $rs->fetchAll();
    return $filas;
  }

  // Primero, consulta de nombres principales y alternativos
  $consulta_nombres = "SELECT id_profesion, nombre_ppal, nombre_alt FROM profesiones_test p INNER JOIN nombres_alt n ON p.id = n.id_profesion;";
  $rs_nombres = $pdo->prepare($consulta_nombres);
  $rs_nombres->execute();
  $nombres = $rs_nombres->fetchAll();
  $nombres_usados = array();
  $nombres_usados_alt = array();

  //funciones para scripts

  function imprimirSeriesSal($filas, $exp) {
    $rangos = array('min', 'med', 'max');
    $seriesSal = array();
    foreach ($rangos as $rango) {
      $campo = 's_' . $exp . '_' . $rango;
      array_push($seriesSal, (is_null($filas[$campo]) || $filas[$campo] == 0) ? 0 : round($filas[$campo]));
    }
    return $seriesSal;
  }

  function createExcerpts($text, $length, $more_txt, $script_info) { 
    $text = preg_replace('/[\n\r]/','',$text);
    $text = str_replace('"','',$text);
    $content = substr( $text, 0 , $length ); 
    $excerpt = substr( $text,  $length , strlen($text) );
    $script_info .= $content . '<span class="excerpt"><span style="display:none;">' . $excerpt . '</span>' . '<strong class="more">' . $more_txt . '</strong></span>'; 
  }

  function imprimirSeriesCap($filas, $tablas) {
    $seriesCap = array();
    foreach ($tablas['capacidades'] as $campo) {
      //return (is_null($filas[$campo]) || $filas[$campo] == 0) ? "2," : round($filas[$campo]) . ",";
      array_push($seriesCap, (is_null($filas[$campo]) || $filas[$campo] == 0) ? "2" : round($filas[$campo]));
    }
    return $seriesCap;
  }

  function coefMin($parados) {
    $output = 1;
    $n = 15000;
    $m = 0.95;
    while ($n >= 1000) {
        if ($parados < $n)
            $output = $m;
        $n -= 1000;
        $m -= 0.05;
    }
    if ($parados < 100)
        $output = 0.1;
    return $output;
  }

  function empleabilidad($contratados, $parados) {
    return (!is_null($parados) && $parados > 0) ?  round(coefMin($parados) * round(100 - ($contratados * 100 / ($parados + $contratados)), 2), 2) : 0;
  }

  function mediaEmpleabilidad($pdo, $meses) {
    $medias = array();
    foreach ($meses as $n_mes => $mes) {
        $media = array();
        $anyo = ( $n_mes - 1 > count($meses)/2 - 1 ) ? '2015' : '2014';
        $consulta = "SELECT parados, contratados FROM empleabilidad WHERE mes LIKE '". $mes ."' AND anyo LIKE ". $anyo;
        $rs = $pdo->prepare($consulta);
        $rs->execute();
        $filas = $rs->fetchAll();
        foreach ($filas as $fila) {
            $media[] = empleabilidad($fila['contratados'], $fila['parados']);
        }
        $medias[] = round(array_sum($media) / count($media), 2);
    }
    return join(", ",$medias);
  }

  function imprimirSeriesEmp($filas) {
    $counter = 0;
    $counter_rect = 0;
    $no_duplicado = true;
    $memo = [];
    $seriesEmp = array();
    foreach ($filas as $fila) {
      $memo[$counter] = $fila;
      if (count($memo) > 1)
        $no_duplicado = ($memo[$counter - 1]['mes'] !== $memo[$counter]['mes']);
      if ($no_duplicado && $counter_rect < 7) {
        $counter_rect++;
        $emp = empleabilidad($fila['contratados'], $fila['parados']);
        array_push($seriesEmp, (is_null($emp) || $emp == 0) ? "0" : $emp); 
      }
      $counter++;
    }
    return $seriesEmp;
  }


  // bucle de todos los nombres
  foreach ($nombres as $nombre) {
    
    $repetir = true; // accede para cada iteracion del foreach
    $profesion = $nombre_ppal = $nombre_alt = $id_profesion = '';
    $nombre_ppal = $nombre['nombre_ppal']; // por defecto no se da valor al nombre ppal
    $nombre_alt = $nombre['nombre_alt'];
    $id_profesion = $nombre['id_profesion'];

    while($repetir) { // mientras haya un nombre ppal se repetira este bucle

      $repetir = false;  //niega la repeticion para que solo haya un bucle
      
      // coger el nombre ppal si la id aun no ha sido usada
      if ( !in_array($id_profesion, $nombres_usados, TRUE) ) { // solo una vez!!!
        $repetir = true; // repetimos while en este caso para buscar un nombre alternativo
        $profesion = $nombre_ppal; // en este caso $nombre sera el nombre_ppal en lugar del alternativo
        echo '<h3>Hay ppal</h3>';
      } else { // en le caso de que haya sido usado buscamos nombre alternativo
        echo '<p>buscando nombre alternativo...</p>';
        if (empty($nombre_alt) || is_null($nombre_alt) || $nombre_alt == 'test' || in_array($nombre_alt, $nombres_usados_alt, TRUE)) {
          $repetir = false; //romper el bucle si el nombre alternativo esta vacio, nulo o repetido
          echo '<h3>No hay alt</h3>';
          continue;        
        } else {
          $repetir = true; // repetimos while en este caso para buscar mas nombres alternativo
          $profesion = $nombre_alt; // profesion pasa a ser el nombre alternativo
          array_push($nombres_usados_alt, $profesion); // y lo incluimos en nombres alternativos usados
          echo '<h3>Hay alt</h3>';
        }
      }
      // incluir id_profesion en nombres usados
      array_push($nombres_usados, $id_profesion);

      foreach ($tablas as $tabla => $value) {
        $filas = 'filas_'.$tabla;
        $$filas = consulta($id_profesion, $tabla, $tablas, $pdo);
      }

      if (!empty($profesion)) {
        // darle url al html estatico
        // setlocale(LC_ALL, 'en_GB'); esta configuracion evitara tambien las ene espanola
        $profesion_noacentos  = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', mb_strtolower($profesion, 'UTF-8')); // eliminar acentos y transformar en minusculas
        $profesion_nosignos   = str_replace(array("'",'"',",",";","(",")","/","~","+"), '', $profesion_noacentos); // eliminar signos gramaticales
        $profesion_underscore = str_replace(' ', '-', $profesion_nosignos); // remplazar espacios en blanco por underscore
        $url_html = "profesiones/" . $profesion_underscore . ".html"; // agregar path y extension 
        // crear html estatico o reescribirlo si ya existe!!
        $pagina_html = fopen($url_html, "w+") or die("No se puede crear este documento");
        echo '<h1>pagina creada: '.$url_html.'</h1>';
      }
      
// comenzamos a generar el html como string
$html = '
<!DOCTYPE html>
<html>
  <head>
      <meta http-equiv="Content-Language" content="es">
      <meta charset="utf-8">
      <title>'; $html .= ucfirst(mb_strtolower($profesion, 'UTF-8')) . '</title>
      <meta name="description" content="'; $html .= ucfirst(mb_strtolower($profesion, 'UTF-8')) . '">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="apple-mobile-web-app-capable" content="yes" />
      <meta prefix="og: http://ogp.me/ns#" property="og:title" content="'; $html .= ucfirst(mb_strtolower($profesion, 'UTF-8')) . '" />
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
      <form id="formulario" role="form" action="../comparador.php" method="get">
          <div class="row header">
            <div class="col-xs-12 hidden-sm hidden-md hidden-lg margen"></div>

            <div class="col-md-4">
              <div class="dropdown clearfix">
                <div class="input-group" id="scrollable-dropdown-menu">
                  <input name="profesion" id="buscador" class="typeahead principal center-block form-control input-lg" type="text" data-tipo="profesiones" placeholder="Busca otra profesión y compara" value="'; $html .= ucfirst(mb_strtolower($profesion, 'UTF-8')) . '" required>
                  <span class="input-group-btn" >
                    <button class="btn btn-default btn-submit" type="submit" style="background-color: rgba(255, 255, 255, 0.6);border-color: rgb(204, 204, 204);height: 50px;position: absolute;top: 0;"><strong>&gt;</strong></button>
                  </span>
                </div>
              </div>
            </div>

            <div class="col-md-4 hidden-sm hidden-xs text-center">
              <a href="../index.html">
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
                <a href="../index.html"> 
                  <img class="img-menu" src="../images/logo.svg" width="35px" height="auto">       
                  </a>
              </div>
              <div class="col-sm-3 col-sm-offset-6 col-xs-3 col-xs-offset-6">
            <button type="button" data-toggle="dropup" aria-expanded="false" class="btn-footer" id="btn-footer-xs" ><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span></button>
          </div>
            </div>
        <div class="col-md-2 col-md-offset-0 hidden-sm hidden-xs col-xs-6 col-xs-offset-3 text-center">
              <a href="../index.html"> 
                  <p id="titulo" style="opacity:1;margin-top:-10px;">
                    <img class="image-container" src="../images/logo.svg">
                    <strong>que</strong>sera<strong>de</strong>mi
                  </p>
              </a>
            </div>
          <div class="col-md-10 col-sm-12 col-xs-12 text-center">
              <div class="col-md-2 col-md-offset-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
                <a href="../colabora.php">Puedes colaborar</a>
                <span class="hidden-sm hidden-xs separador">|</span>
              </div>
              <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
                <a href="../porquecolaborar.html">Por qué colaborar</a>
                <span class="hidden-sm hidden-xs separador">|</span>
              </div>
              <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
                <a href="../quienessomos.html">Quiénes somos</a>
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

/**SALARIOS**/ 

$btn_colabora_s_1 = 0;
$s_princ_min = $s_princ_med = $s_princ_max = $s_junior_min = $s_junior_med = $s_junior_max = $s_intermedio_min = $s_intermedio_med = $s_intermedio_max = $s_senior_min = $s_senior_med = $s_senior_max = 0;

$experiencias = array('princ', 'junior', 'intermedio', 'senior');
$rangos = array('min', 'med', 'max');

// busqueda de nulos en salarios
foreach ($experiencias as $exp) {
  foreach ($rangos as $rango) {
    $campo = 's_' . $exp . '_' . $rango;
    if (is_null($filas_salarios[0][$campo]) || $filas_salarios[0][$campo] == 0) {
      $btn_colabora_s_1++;
    }
  }
}

$script_salarios = '
var seriesSalPrinc      = ['. join(', ', imprimirSeriesSal($filas_salarios[0], $experiencias[0])) .'];
var seriesSalJunior     = ['. join(', ', imprimirSeriesSal($filas_salarios[0], $experiencias[1])) .'];
var seriesSalIntermedio = ['. join(', ', imprimirSeriesSal($filas_salarios[0], $experiencias[2])) .'];
var seriesSalSenior     = ['. join(', ', imprimirSeriesSal($filas_salarios[0], $experiencias[3])) .'];
';

$script_salarios .= 'var salarios = [
    [0,   seriesSalPrinc[0],      seriesSalPrinc[2]],
    [5,   seriesSalJunior[0],     seriesSalJunior[2]],
    [10,  seriesSalIntermedio[0], seriesSalIntermedio[2]],
    [15,  seriesSalSenior[0],     seriesSalSenior[2]]
], medias = [
    [0,   seriesSalPrinc[1]],
    [5,   seriesSalJunior[1]],
    [10,  seriesSalIntermedio[1]], 
    [15,  seriesSalSenior[1]]
];';

$script_salarios .= "$('#container_salarios').highcharts({
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
        text: 'SALARIO BRUTO ANUAL',
        align: 'center',
        style: { 
            'color': '#555',
            'fontSize': '14px',
            'fontWeight': 'bold'
        } 
    },
    subtitle: {
        text: '- € / año -'
    },
    legend: { 
        enable: false 
    },
    xAxis: {
        title: {
            text: 'EXPERIENCIA ' + '(años)'.toUpperCase()
        }
    },
    yAxis: {
        title: {
            text: 'SALARIO BRUTO ANUAL'
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
        },
        series: {
            allowPointSelect: true
        }
    },
    series: [
        {
            name: '". $profesion ."',
            data: medias,
            color: Highcharts.getOptions().colors[0],
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
            linkedTo: '". $profesion ."',
            color: Highcharts.getOptions().colors[0],
            fillOpacity: 0.3,
            zIndex: 0
        }
    ]
});";

if( $btn_colabora_s_1 > 0 ) { 
    $script_salarios .= "var capa_aviso = '<div class=\"capa-aviso\">';
    capa_aviso += '<div class=\"cerrar-aviso\"><a href=\"#\"><img class=\"icon\" src=\"../images/cross.svg\"></img></a></div>';
    capa_aviso += '<div class=\"col-md-10 col-md-offset-1\">';
    capa_aviso += '<h3>Aún no tenemos imformación suficiente!</h3>';

        capa_aviso += '<p class=\"text-center\">Ayúdanos a completar información sobre <strong>salario</strong> de la profesión<br>';
        capa_aviso += '<strong>". mb_strtoupper($profesion,"UTF-8") ."</strong></p>';
        capa_aviso += '<a href=\"../colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);\">Colabora!</a>';

    capa_aviso += '</div></div>';

    $('#container_salarios').append(capa_aviso);";
} 

/** INFO **/

$script_info = "$('#container_info').html('<h4 style=\"margin:15px\">INFORMACIÓN</h4><div id=\"info\"></div>');";

if( isset( $profesion ) ) {  
    $script_info .= "$('#info').append('<h4 class=\"principal nombre\">". $profesion ."</h4>');";
    if( empty( $filas_info[0]['descripcion'] ) ) { 
        $script_info .= "$('#info').append('<p class=\"descripcion\" id=\"desc1\">Falta información! Ayúdanos a conseguirla.</p>');
        $('#info').append('<div class=\"col-md-8 col-md-offset-2\"><a href=\"../colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);\">Colabora!</a></div>');";
    } else { 
        $script_info .= "$('#info').append('<p class=\"descripcion\">". createExcerpts($filas_info[0]["descripcion"] , 150 , " [ + ]", $script_info) ."</p>');";
    } 
} 

$script_info .= "// Excerpt
$('.more').click( function() {
    $(this).prev().fadeToggle();
    var text = $(this).text();
    $(this).text(text == ' [ + ]' ? ' [ - ]' : ' [ + ]');
});";

/**CAPACIDADES**/

$descripciones = array(
    'Análisis'                  => 'Razonamiento lógico, toma de decisiones, organización, gestión, etc.',
    'Comunicación'              => 'Comunicación, hablar en público, escucha activa.',
    'Destreza y físico'         => 'Destreza técnica, apariciencia física, etc.',
    'Cooperación'               => 'Empatía, sensibilidad, colaboración, trabajo en equipo, escucha.',
    'Logro de objetivos'  => 'Orientado a objetivos, resultados, etc.',
    'Persuasión'                => 'Influencia, negociación, habilidades comerciales, etc.'
);
$iconos = array(
    'Análisis'                  => 'line-chart',
    'Comunicación'              => 'comments-o',
    'Destreza y físico'         => 'wrench',
    'Cooperación'               => 'users',
    'Logro de objetivos'        => 'trophy',
    'Persuasión'                => 'briefcase'
);

$btn_colabora_c_1 = 0;
$c_analisis = $c_comunicacion = $c_equipo = $c_forma_fisica = $c_objetivos = $c_persuasion = 0;

// busqueda de nulos en capacidades
foreach ($filas_capacidades as $fila_capacidad) { 
  if( is_null($fila_capacidad) || $fila_capacidad == 0 )
    $btn_colabora_c_1++;
}

$script_capacidades = 'var seriesCap = ['. join(', ', imprimirSeriesCap($filas_capacidades[0], $tablas)) .'];';

$script_capacidades .= "$('#container_capacidades').highcharts({
    chart: {
        polar: true,
        type: 'line',
        backgroundColor:'rgba(255, 255, 255, 0)',
        // Edit chart size
        spacingBottom: 20,
        spacingTop: 20,
        spacingLeft: 20,
        spacingRight: 20,
        width: null,
        height: 380
    },
    title: {
        text: 'CUALIDADES PROFESIONALES',
        align: 'center',
        style: { 
            'color': '#555',
            'fontSize': '14px',
            'fontWeight': 'bold'
        }
    },
    legend: { enable: false },
    pane: {
        size: '80%'
    },
    xAxis: {
        categories: [";
        foreach($descripciones as $nombre => $descripcion) { 
            $script_capacidades .= '"'.$nombre.'",'; 
        } 
        $script_capacidades .= "],
        tickmarkPlacement: 'on',
        lineWidth: 0,
        gridLineColor: '#999999',
        labels: {
            style: {
                fontSize: '12px',
                zIndex: '-1'
            },
            useHTML: true,
            formatter: function () {
                return [
                ";
                $numItems = count($iconos);
                $i = 0;
                foreach($iconos as $nombre => $icono) { 
                    $script_capacidades .= "'<i class=\"fa fa-".$icono." fa-lg\"></i>'";
                    if(++$i !== $numItems)
                        $script_capacidades .= "+";
                } 
                $script_capacidades .= "
                ];
            }
        }
    },
    yAxis: {
        gridLineInterpolation: 'polygon',
        lineWidth: 0,
        min: 0,
        minorTickInterval: 'auto',
        gridLineColor: '#999999',
        labels: {
            style: {
                fontSize: '0px'
            }
        }
    },
    tooltip: {
        shared: true,
        formatter: function() {
            var descripciones = {
                Analisis:                   '". $descripciones['Análisis'] ."',
                Comunicacion:               '". $descripciones['Comunicación'] ."',
                Destreza_y_fisico:          '". $descripciones['Destreza y físico'] ."',
                Cooperacion:                '". $descripciones['Cooperación'] ."',
                Logro_de_objetivos:         '". $descripciones['Logro de objetivos'] ."',
                Persuasion:                 '". $descripciones['Persuasión'] ."'
            };
            
            return '<strong style=\"font-size:17px;color:rgb(0,0,0);\">'+ this.x +'</strong><br/>'+'<span>'+ descripciones[this.x.replace(/ /g,'_').latinize()] +'</span><br/>'+
            '<span style=\"color:'+this.points[0].series.color+'\">'+this.points[0].series.name+': <strong>'+this.points[0].y+'</strong><br/>';
        },
        headerFormat: '<strong>{point.key}</strong><br>'      
    },
    exporting: {
        buttons: {
           anotherButton: {
                text: '???',
                onclick: function () {
                    var capa_glosario = '<div class=\"capa-glosario\">';
                    capa_glosario += '<div class=\"cerrar-glosario\"><img class=\"icon\" src=\"../images/cross.svg\"></img></div>';
                    capa_glosario += '<div class=\"col-md-10 col-md-offset-1\">';
                   
                    capa_glosario += '<h3>No te preocupes, te lo aclaramos aquí</h3><br>';
                    capa_glosario += '<dl class=\"dl-horizontal\">';";
                    foreach ($descripciones as $nombre => $descripcion) { 
                      $script_capacidades .= "capa_glosario += '<dt>';
                      capa_glosario += '".$nombre.":';
                      capa_glosario += '&nbsp;<i class=\"fa fa-".$iconos[$nombre]." fa-lg\"></i><br>';
                      capa_glosario += '</dt><dd>&gt;&gt;".$descripcion."</dd>';";
                    }
                    $script_capacidades .= "capa_glosario += '</dl>';

                    capa_glosario += '</div>';
                    capa_glosario += '</div>';

                    $('#container_capacidades').append(capa_glosario);

                    // cerrar glosario
                    $('.cerrar-glosario').click( function() {
                        $(this).parent().remove();
                    });
                }
            }
        }
    },
    
    credits: {
         enabled: false
    },

    series: [{  
        name: '". mb_strtoupper($profesion,"UTF-8") ."',
        data: seriesCap,
        stack: '". $profesion ."'
    }]
});";

if( $btn_colabora_c_1 > 0 ) { 
    
    $script_capacidades .= "var capa_aviso = '<div class=\"capa-aviso\">';
    capa_aviso += '<div class=\"cerrar-aviso\"><a href=\"#\"><img class=\"icon\" src=\"../images/cross.svg\"></img></a></div>';
    capa_aviso += '<div class=\"col-md-10 col-md-offset-1\">';
    capa_aviso += '<h3>Aún no tenemos imformación suficiente!</h3>';

        capa_aviso += '<p class=\"text-center\">Ayúdanos a completar información sobre <strong>cualidades profesionales</strong> de la profesión<br>';
        capa_aviso += '<strong>". mb_strtoupper($profesion,"UTF-8") ."</strong></p>';
        capa_aviso += '<a href=\"../colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);\">Colabora!</a>';

    capa_aviso += '</div>';
    capa_aviso += '</div>';

    $('#container_capacidades').append(capa_aviso);";
}

/** EMPLEABILIDAD **/

$btn_colabora_e_1 = 0;
$meses = ['enero','abril','julio','octubre'];
$meses = array_merge($meses,$meses); // concatenar meses 
//array_pop($meses); // y eliminar el ultimo elemento

// busqueda de nulos en empleabilidad
foreach ($filas_empleabilidad as $fila_empleabilidad) { 
  $empleabilidad = empleabilidad($fila_empleabilidad['contratados'], $fila_empleabilidad['parados']); 
  if( is_null($empleabilidad) || $empleabilidad == 0 )
    $btn_colabora_e_1++;
}

$script_empleabilidad = "
var seriesEmp = [". join(", ", imprimirSeriesEmp($filas_empleabilidad)) . "];
var seriesEmpMedia = [". mediaEmpleabilidad($pdo, $meses) . "];";

$script_empleabilidad .= "$('#container_empleabilidad').highcharts({
    chart: {
        type: 'column',
        marginTop: 80,
        marginRight: 40,
        backgroundColor:'rgba(255, 255, 255, 0)',
        // Edit chart size
        spacingBottom: 20,
        spacingTop: 20,
        spacingLeft: 20,
        spacingRight: 20,
        width: null,
        height: 380
    },
    title: {
        text: 'PARO',
        align: 'center',
        style: { 
            'color': '#555',
            'fontSize': '14px',
            'fontWeight': 'bold'
        }
    },
    subtitle: {
        text: '- DIFICULTAD DE CONSEGUIR TRABAJO -'
    },
    legend: { enable: false },
    xAxis: {
        categories: [ ";
          foreach ($meses as $n_mes => $mes) { 
            $year = ( $n_mes - 1 > count($meses)/2 - 1 ) ? '2015' : '2014';
            $script_empleabilidad .= "'".ucfirst($mes)." ".$year."'";
            if ($n_mes + 1 < count($meses))
              $script_empleabilidad .= ',';
          }
        $script_empleabilidad .= " ]
    },
    yAxis: {
        allowDecimals: true,
        min: 0,
        title: {
            text: 'Dificultad de conseguir trabajo %'
        }
    },
    tooltip: {
        headerFormat: '<b>{point.key}</b><br>',
        pointFormat: '<span style=\"color:{series.color}\">\u25CF</span> {series.name}: {point.y}'
    },
    credits: {
        enabled: false
    }, 
    series: [
      {
        name: '". mb_strtoupper($profesion,"UTF-8" ) ."',
        data: seriesEmp,
        stack: '". $profesion ."'
      },{
        name: 'Media de paro de todas las profesiones',
        type: 'spline',
        data: seriesEmpMedia,
        stack: 'Media de paro',
        color: 'rgb(0, 0, 0)',
        dashStyle: 'shortdot',
        marker: {
            fillColor: 'white',
            lineWidth: 2,
            lineColor: 'rgb(0, 0, 0)',
        }
      }
    ]
});";

if( $btn_colabora_e_1 > 0 ) { 
    $script_empleabilidad .= "var capa_aviso = '<div class=\"capa-aviso\">';
    capa_aviso += '<div class=\"cerrar-aviso\"><a href=\"#\"><img class=\"icon\" src=\"../images/cross.svg\"></img></a></div>';
    capa_aviso += '<div class=\"col-md-10 col-md-offset-1\">';
    capa_aviso += '<h3>Aún no tenemos imformación suficiente!</h3>';

        capa_aviso += '<p class=\"text-center\">Ayúdanos a completar información sobre <strong>desempleo</strong> de la profesión<br>';
        capa_aviso += '<strong>". mb_strtoupper($profesion,"UTF-8") ."</strong></p>';
        capa_aviso += '<a href=\"../colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);\">Colabora!</a>';

    capa_aviso += '</div>';
    capa_aviso += '</div>';

    $('#container_empleabilidad').append(capa_aviso);";
} 

/** FORMACION **/
/*
$i = 0;

$formacion          = $filas_formaciones[$i]['f_nombre_ppal'];
$duracion           = $filas_formaciones[$i]['duracion_academica'];
$duracion_real      = $filas_formaciones[$i]['duracion_real'];
$nivel              = $filas_formaciones[$i]['nivel'];

$script_formacion = "$('#container_formacion').highcharts({
        chart: {
            type: 'bar',
            backgroundColor:'rgba(255, 255, 255, 0)',
            spacingBottom: 20,
            spacingTop: 20,
            spacingLeft: 20,
            spacingRight: 20,
            width: null,
            height: 380
        },
        title: {
            text: 'FORMACION'
        },
        xAxis: {
            categories: [
            '". mb_strtoupper($profesion, "UTF-8") ."<br><strong>". $formacion ." &gt;&gt;</strong>'
            , '(Duracion real estimada) <br><strong>". $formacion ." &gt;&gt;</strong>'
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Duracion de estudios (años)'
            }
        },
        legend: {
            reversed: true
        },
        credits: {
             enabled: false
        },
        colorBypoint: true,
        colors: [ '#ede2e8', '#dcc6d1', '#ba8da4', '#975577', '#751c4a', '#58002e', '#420022', '#2c0017', '#210011', '#160000' ],
        plotOptions: {
            series: {
                stacking: 'normal',
            },
            scatter: {
                tooltip: {
                    pointFormat: '{point.x} años de estudios'
                }
            }
        },
        exporting: {
            buttons: {
               anotherButton: {
                    text: 'Donde estudiar?',
                    onclick: function () {
                        alert('Donde estudiar? En desarrollo... Disculpe las molestias');
                    }
                }
            }
        },";
       
        $btn_colabora_f_1 = 0; 

        $formacion          = $filas_formaciones[$i]['f_nombre_ppal'];
        $duracion           = $filas_formaciones[$i]['duracion_academica'];
        $duracion_real      = $filas_formaciones[$i]['duracion_real'];
        $nivel              = $filas_formaciones[$i]['nivel'];
     
        $doctorado = $master = $universidad = $fp_superior = false;
        
        $script_formacion .= "series: [";         
            if( $duracion > 16 && $nivel == 11 ) {
              $script_formacion .= "{
                  name: 'Doctorado',
                  data: [";
                  if( isset($duracion) && $duracion > 16 ) { 
                    $doctorado = true; 
                    if($duracion > 18) {
                      $script_formacion .= ($duracion - 18);
                    } else {
                      $script_formacion .= 2;
                    } 
                    $script_formacion .= ", 0";
                  } else { 
                    $btn_colabora_f_1 = 9; 
                    $script_formacion .= '0, 0';
                  } 
                  $script_formacion .= "]
              },";
            } 
            if( $duracion > 16 && $nivel == 10 ) { 
              $script_formacion .= "{
                  name: 'Master',
                  data: [";
                  if( isset($duracion) && $duracion > 16 ) { 
                    $master = true; 
                    if($duracion < 19) {
                      $script_formacion .= ($duracion - 16);
                    } else {
                      $script_formacion .= 2;
                    }
                  $script_formacion .= ", 0";
                  } else { 
                    $btn_colabora_f_1 = 8; 
                    $script_formacion .= '0, 0';
                  } 
                  $script_formacion .= "]
              },"; 
            } 
            if( $nivel == 9 ) { 
              $script_formacion .= "{
                  name: 'Oposiciones',
                  data: [";
                  if( isset($duracion) && $duracion > 12 ) { 
                    if($duracion > 16) {
                      $script_formacion .= ($duracion - 16);
                    } else if($duracion < 17) {
                      $script_formacion .= ($duracion - 12);
                    } else {
                      $script_formacion .= 2;
                    } 
                  $script_formacion .= ", 0";
                  } else { 
                    $btn_colabora_f_1 = 7; 
                    $script_formacion .= '0, 0';
                  } 
                
                  $script_formacion .= "]
              },";
            } 
            if( ($duracion > 12 || $nivel == 8) || $master || $doctorado ) { 
              $script_formacion .= "{
                  name: 'Grado Universitario',
                  data: [";
                  if( isset($duracion) && $duracion > 12 ) { 
                    $universidad = true; 
                    if($duracion < 17) {
                      $script_formacion .= ($duracion - 12);
                    } else {
                      $script_formacion .= 4;
                    } 
                    $script_formacion .= ", 0";
                  } else { 
                    $btn_colabora_f_1 = 6; 
                    $script_formacion .= '0, 0';
                  }
            
                  $script_formacion .= "]
              },"; 
            } 
            if( $duracion > 12 && $nivel == 7 ) { 
              $script_formacion .= "{
                  name: 'F.P. Superior',
                  data: [";
                  if( isset($duracion) && $duracion > 12 ) { 
                    $fp_superior = true; 
                    if($duracion < 15) {
                      $script_formacion .= ($duracion - 12);
                    } else {
                      $script_formacion .= 2;
                    } 
                    $script_formacion .= ", 0";
                  } else { 
                    $btn_colabora_f_1 = 5; 
                    $script_formacion .= '0, 0';
                  } 
                
                  $script_formacion .= "]
              },"; 
            } 
            if( ($duracion > 10 && $nivel == 6) || $universidad || $fp_superior ) { 
              $script_formacion .= "{
                  name: 'Bachillerato',
                  data: [";
                  if( isset($duracion) && $duracion > 10 ) {
                    if($duracion < 13) {
                      $script_formacion .= ($duracion - 10);
                    } 
                    else {
                      $script_formacion .= 2;
                    } 
                    $script_formacion .= ", 0";
                  } else { 
                    $btn_colabora_f_1 = 4; 
                    $script_formacion .= '0, 0';
                  } 
                  $script_formacion .= "]
              },";
            } 
            if( $duracion > 10 && $nivel == 5 ) { 
              $script_formacion .= "{
                  name: 'F.P. Medio',
                  data: [";
                  if( isset($duracion) && $duracion > 10 ) { 
                    if($duracion < 13) {
                      $script_formacion .= ($duracion - 10);
                    } else {
                      $script_formacion .= 2;
                    } 
                    $script_formacion .= ", 0";
                  } else { 
                    $btn_colabora_f_1 = 3; 
                    $script_formacion .= '0, 0';
                  } 
                  $script_formacion .= "]
              },";
            } 
            if( $duracion > 6 ) { 
              $script_formacion .= "{
                  name: 'E.S.O.',
                  data: [";
                  if( isset($duracion) && $duracion > 6 ) { 
                    if($duracion < 11) {
                      $script_formacion .= ($duracion - 6);
                    } else {
                      $script_formacion .= 4;
                    } 
                    $script_formacion .= ", 0";
                  } else { 
                    $btn_colabora_f_1 = 2; 
                    $script_formacion .= '0, 0';
                  } 
                  $script_formacion .= "]
              },";
            } 
            if( $duracion > 0 ) { 
              $script_formacion .= "{
                  name: 'Primaria',
                  data: [";
                  if( isset($duracion) && $duracion > 0 ) { 
                    if($duracion < 7) {
                      $script_formacion .= $duracion;
                    } else {
                      $script_formacion .= 6;
                    } 
                    $script_formacion .= ", 0"; 
                  } else { 
                    $btn_colabora_f_1 = 1; 
                    $script_formacion .= '0, 0';
                  } 
                  $script_formacion .= "]
              },"; 
            } 
            if( isset($duracion_real) ) { 
              $script_formacion .= "{
                  name: 'Duracion real estimada',
                  data: [";
                  if( isset($duracion_real) && $duracion_real > 0 ) { 
                    $script_formacion .= "0, ".$duracion_real;
                  } else { 
                    $btn_colabora_f_1 = 10;  
                    $script_formacion .= '0, 0';
                  } 
                  $script_formacion .= "]
              }";
            } 
        $script_formacion .= "]
    });";

if( $btn_colabora_f_1 > 0 ) { 
    $script_formacion .= "var capa_aviso = '<div class=\"capa-aviso\">';
    capa_aviso += '<div class=\"cerrar-aviso\"><a href=\"#\"><img class=\"icon\" src=\"../images/cross.svg\"></img></a></div>';
    capa_aviso += '<div class=\"col-md-10 col-md-offset-1\">';
    capa_aviso += '<h3>Aún no tenemos imformación suficiente!</h3>';

        capa_aviso += '<p class=\"text-center\">Ayúdanos a completar información sobre <strong>formacion</strong> de la profesión<br>';
        capa_aviso += '<strong>". mb_strtoupper($profesion,"UTF-8") ."</strong></p>';
        capa_aviso += '<a href=\"../colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);\">Colabora!</a>';

    capa_aviso += '</div>';
    capa_aviso += '</div>';

    $('#container_formacion').append(capa_aviso);";
} 
*/
/** SATISFACCION **/
/*$btn_colabora_sat_1 = 0;
$script_satisfaccion = "$('#container_satisfaccion').highcharts({
    chart: {
        type: 'scatter',
        zoomType: 'xy',
        backgroundColor:'rgba(255, 255, 255, 0)',
        // Edit chart size
        spacingBottom: 20,
        spacingTop: 20,
        spacingLeft: 20,
        spacingRight: 20,
        width: null,
        height: 380
    },
    title: {
        text: 'GRADO DE SATISFACCIÓN'
    },
    xAxis: {
        title: {
            text: 'EXPERIENCIA ' + '(años)'.toUpperCase()
        }
    },
    yAxis: {
        title: {
            text: 'SATISFACCIÓN'
        }
    },
    legend: { enable: false },
    credits: {
        enabled: false
    },
    plotOptions: {
        scatter: {
            marker: {
                radius: 5,
                states: {
                    hover: {
                        enabled: true,
                        lineColor: 'rgb(100,100,100)'
                    }
                }
            },
            states: {
                hover: {
                    marker: {
                        enabled: false
                    }
                }
            },
            tooltip: {
                headerFormat: '<b>{series.name}</b><br>',
                pointFormat: '{point.x} años de experiencia'
            }
        }
    },
    series: [{
        name: '". mb_strtoupper($profesion,"UTF-8" ) ."',
        data: [";
        foreach ($filas_satisfaccion as $fila_sat) { 
            $script_satisfaccion .= "["; 
            if( is_null($fila_sat['experiencia']) || $fila_sat['experiencia'] == 0 ) {
              $script_satisfaccion .=  0;
              $btn_colabora_sat_1+=1;
            } else {
              $script_satisfaccion .=  $fila_sat['experiencia'];
            } 
            $script_satisfaccion .= ",";
            if( is_null($fila_sat['grado_satisfaccion']) || $fila_sat['grado_satisfaccion'] == 0 ) {
              $script_satisfaccion .=  0;
              $btn_colabora_sat_1+=1;
            } else {
              $script_satisfaccion .=  $fila_sat['grado_satisfaccion'];
            } 
            $script_satisfaccion .= "],";
        } 
        $script_satisfaccion .= "],
        stack: '". $profesion ."'
    }]
});";

if( $btn_colabora_sat_1 > 0 ) { 
    $script_satisfaccion .= "var capa_aviso = '<div class=\"capa-aviso\">';
    capa_aviso += '<div class=\"cerrar-aviso\"><a href=\"#\"><img class=\"icon\" src=\"../images/cross.svg\"></img></a></div>';
    capa_aviso += '<div class=\"col-md-10 col-md-offset-1\">';
    capa_aviso += '<h3>Aún no tenemos imformación suficiente!</h3>';

        capa_aviso += '<p class=\"text-center\">Ayúdanos a completar información sobre <strong>satisfaccion</strong> de la profesión<br>';
        capa_aviso += '<strong>". mb_strtoupper($profesion,"UTF-8") ."</strong></p>';
        capa_aviso += '<a href=\"../colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);\">Colabora!</a>';

    capa_aviso += '</div>';
    capa_aviso += '</div>';

    $('#container_satisfaccion').append(capa_aviso);";
  }
*/
  // incluir scripts y cerrar html 

    $html .= $script_salarios . $script_info . $script_capacidades . $script_empleabilidad; 
    //$html .= $script_formacion . $script_satisfaccion;
    $html .= '
  </script>
</html>';
    
    // guardar html
    fwrite($pagina_html, $html);
    fclose($pagina_html);

    } // end while
    //TEST//
    //break;  
  } // end foreach

} catch( Exception $e ) {
  die('Error: '.$e->GetMessage());
}
?>