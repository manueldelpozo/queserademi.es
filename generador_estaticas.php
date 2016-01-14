<?php 
try { 
  require('conexion.php');

  $tablas = array( 
    'salarios'      => array('s_princ_min', 's_princ_med', 's_princ_max', 's_junior_min', 's_junior_med', 's_junior_max', 's_intermedio_min', 's_intermedio_med', 's_intermedio_max', 's_senior_min', 's_senior_med', 's_senior_max'),
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

  //funciones para scripts
  function imprimirSeriesSal($fila, $btn, $btn_colabora) {
    if( !is_null($fila) && !$fila == 0 )
        return $fila;
    else
        $btn_colabora = $btn + 1;
  }
  function createExcerpts($text, $length, $more_txt, $script_info) { 
    $text = preg_replace('/[\n\r]/','',$text);
    $text = str_replace('"','',$text);
    $content = substr( $text, 0 , $length ); 
    $excerpt = substr( $text,  $length , strlen($text) );
    $script_info .= $content . '<span class="excerpt"><span style="display:none;">' . $excerpt . '</span>' . '<strong class="more">' . $more_txt . '</strong></span>'; 
  }
  function imprimirSeriesCap($fila, $btn, $btn_colabora) {
    if( !is_null($fila) && !$fila == 0 )
        return $fila;
    else
        $btn_colabora = $btn + 1;
  }
  function empleabilidad($contratados, $parados) {
    return round( 100 - ( $contratados * 100 / ($parados + $contratados) ), 2 );
  }
  function imprimirSeriesEmp($filas, $btn_colabora) {
      $series = '';
      foreach ($filas as $fila) { 
          $emp = empleabilidad($fila['contratados'], $fila['parados']); 
          if( is_null($emp) || $emp == 0 ) {
              $btn_colabora++;
              $series .= "0,";
          } else {
              $series .= $emp.",";
          } 
      }
      return $series;
  }

  // bucle de todos los nombres
  foreach ($nombres as $nombre) {
    
    $repetir = true; // accede para cada iteracion del foreach
    $profesion = $nombre_ppal = $nombre_alt = $id_profesion = '';
    $nombre_ppal = $nombre['nombre_ppal']; // por defecto no se da valor al nombre ppal
    $nombre_alt = $nombre['nombre_alt'];
    $id_profesion = $nombre['id_profesion'];

    while($repetir) { // mientras haya un nombre ppal se repetira este bucle

      $repetir = false;  //niega la repeticion 
      $profesion = $nombre_alt;
      // coger el nombre ppal si el nombre aun no ha sido usado
      if ( !in_array($id_profesion, $nombres_usados, TRUE) ) { // solo una vez!!!
        //$nombre_ppal = $nombre['nombre_ppal']; 
        $repetir = true; // repetimos while en este caso
        $profesion = $nombre_ppal; // en este caso $nombre sera el nombre_ppal en lugar del alternativo
        echo '<h3>Hay ppal</h3>';
      } 
      // incluir profesion en nombres usados
      array_push($nombres_usados, $id_profesion);

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
      echo '<h1>pagina creada: '.$url_html.'</h1>';

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



foreach( $tablas['salarios'] as $n => $rango) {
    $$rango = imprimirSeriesSal($filas_salarios[0][$rango], $n, $btn_colabora_s_1);
}

$script_salarios = 'var salarios = ['.
    '[0, '. $s_princ_min .','. $s_princ_max .'],'.
    '[5, '. $s_junior_min .','. $s_junior_max .'],'.
    '[10, '. $s_intermedio_min .','. $s_intermedio_max .'],'. 
    '[15, '. $s_senior_min .','. $s_senior_max .']'.
'], medias = ['.
    '[0,'. $s_princ_med .'],'.
    '[5,'. $s_junior_med .'],'.
    '[10,'. $s_intermedio_med .'],'. 
    '[15,'. $s_senior_med .']'.
'];';


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
        text: 'SALARIO ANUAL (€/año)',
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
    colorBypoint: true,
    colors: [ '#cc0000', '#cc0000' ],
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
            linkedTo: '". $profesion ."',
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
$script_info = "";

$script_info .= "$('#container_info').html('<h4 style=\"margin:15px\">INFORMACIÓN</h4><div id=\"info\"></div>');";

if( isset( $profesion ) ) {  
    $script_info .= "$('#info').append('<h5 class=\"principal nombre\">". $profesion ."</h5>');";
    if( empty( $filas_info[0]['descripcion'] ) ) { 
        $script_info .= "$('#info').append('<p class=\"descripcion\" id=\"desc1\">Descripcion: Falta información! Ayúdanos a conseguirla.</p>');
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
    'Capacidad física'          => 'Destreza técnica, apariciencia física, etc.',
    'Cooperación'               => 'Empatía, sensibilidad, colaboración, trabajo en equipo, escucha.',
    'Consecución de objetivos'  => 'Orientado a objetivos, resultados, etc.',
    'Persuasión'                => 'Influencia, negociación, habilidades comerciales, etc.'
);

$btn_colabora_c_1 = 0;
$c_analisis = $c_comunicacion = $c_equipo = $c_forma_fisica = $c_objetivos = $c_persuasion = 0;



foreach( $tablas['capacidades'] as $n => $campo) {
    $$campo = imprimirSeriesCap($filas_capacidades[0][$campo], $n, $btn_colabora_c_1);
}

$script_capacidades = 'var capacidades = ['.$c_analisis .','. $c_comunicacion .','. $c_equipo .','. $c_forma_fisica .','. $c_objetivos .','. $c_persuasion .'];';

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
        align: 'center'
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
        gridLineColor: '#999999'
    },
    yAxis: {
        gridLineInterpolation: 'polygon',
        lineWidth: 0,
        min: 0,
        gridLineColor: '#999999'
    },
    exporting: {
            buttons: {
               anotherButton: {
                    text: '?? No entiendo!',
                    onclick: function () {
                        var capa_glosario = '<div class=\"capa-glosario\">';
                        capa_glosario += '<div class=\"cerrar-glosario\"><img class=\"icon\" src=\"../images/cross.svg\"></img></div>';
                        capa_glosario += '<div class=\"col-md-10 col-md-offset-1\">';
                       
                        capa_glosario += '<h3>No te preocupes, te lo aclaramos aquí</h3>';
                        capa_glosario += '<dl class=\"dl-horizontal\">';";
                        foreach ($descripciones as $nombre => $descripcion) { 
                            $script_capacidades .= "capa_glosario += '<dt>". $nombre .":</dt><dd>". $descripcion .":</dd>';";
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
    tooltip: {
        shared: true,
        formatter: function() {
            var descripciones = {
                Analisis:                   '". $descripciones['Análisis'] ."',
                Comunicacion:               '". $descripciones['Comunicación'] ."',
                Capacidad_fisica:           '". $descripciones['Capacidad física'] ."',
                Cooperacion:                '". $descripciones['Cooperación'] ."',
                Consecucion_de_objetivos:   '". $descripciones['Consecución de objetivos'] ."',
                Persuasion:                 '". $descripciones['Persuasión'] ."'
            };

            var Latinise={};Latinise.latin_map={'Á':'A','Ă':'A','Ắ':'A','Ặ':'A','Ằ':'A','Ẳ':'A','Ẵ':'A','Ǎ':'A','Â':'A','Ấ':'A','Ậ':'A','Ầ':'A','Ẩ':'A','Ẫ':'A','Ä':'A','Ǟ':'A','Ȧ':'A','Ǡ':'A','Ạ':'A','Ȁ':'A','À':'A','Ả':'A','Ȃ':'A','Ā':'A','Ą':'A','Å':'A','Ǻ':'A','Ḁ':'A','Ⱥ':'A','Ã':'A','Ꜳ':'AA','Æ':'AE','Ǽ':'AE','Ǣ':'AE','Ꜵ':'AO','Ꜷ':'AU','Ꜹ':'AV','Ꜻ':'AV','Ꜽ':'AY','Ḃ':'B','Ḅ':'B','Ɓ':'B','Ḇ':'B','Ƀ':'B','Ƃ':'B','Ć':'C','Č':'C','Ç':'C','Ḉ':'C','Ĉ':'C','Ċ':'C','Ƈ':'C','Ȼ':'C','Ď':'D','Ḑ':'D','Ḓ':'D','Ḋ':'D','Ḍ':'D','Ɗ':'D','Ḏ':'D','ǲ':'D','ǅ':'D','Đ':'D','Ƌ':'D','Ǳ':'DZ','Ǆ':'DZ','É':'E','Ĕ':'E','Ě':'E','Ȩ':'E','Ḝ':'E','Ê':'E','Ế':'E','Ệ':'E','Ề':'E','Ể':'E','Ễ':'E','Ḙ':'E','Ë':'E','Ė':'E','Ẹ':'E','Ȅ':'E','È':'E','Ẻ':'E','Ȇ':'E','Ē':'E','Ḗ':'E','Ḕ':'E','Ę':'E','Ɇ':'E','Ẽ':'E','Ḛ':'E','Ꝫ':'ET','Ḟ':'F','Ƒ':'F','Ǵ':'G','Ğ':'G','Ǧ':'G','Ģ':'G','Ĝ':'G','Ġ':'G','Ɠ':'G','Ḡ':'G','Ǥ':'G','Ḫ':'H','Ȟ':'H','Ḩ':'H','Ĥ':'H','Ⱨ':'H','Ḧ':'H','Ḣ':'H','Ḥ':'H','Ħ':'H','Í':'I','Ĭ':'I','Ǐ':'I','Î':'I','Ï':'I','Ḯ':'I','İ':'I','Ị':'I','Ȉ':'I','Ì':'I','Ỉ':'I','Ȋ':'I','Ī':'I','Į':'I','Ɨ':'I','Ĩ':'I','Ḭ':'I','Ꝺ':'D','Ꝼ':'F','Ᵹ':'G','Ꞃ':'R','Ꞅ':'S','Ꞇ':'T','Ꝭ':'IS','Ĵ':'J','Ɉ':'J','Ḱ':'K','Ǩ':'K','Ķ':'K','Ⱪ':'K','Ꝃ':'K','Ḳ':'K','Ƙ':'K','Ḵ':'K','Ꝁ':'K','Ꝅ':'K','Ĺ':'L','Ƚ':'L','Ľ':'L','Ļ':'L','Ḽ':'L','Ḷ':'L','Ḹ':'L','Ⱡ':'L','Ꝉ':'L','Ḻ':'L','Ŀ':'L','Ɫ':'L','ǈ':'L','Ł':'L','Ǉ':'LJ','Ḿ':'M','Ṁ':'M','Ṃ':'M','Ɱ':'M','Ń':'N','Ň':'N','Ņ':'N','Ṋ':'N','Ṅ':'N','Ṇ':'N','Ǹ':'N','Ɲ':'N','Ṉ':'N','Ƞ':'N','ǋ':'N','Ñ':'N','Ǌ':'NJ','Ó':'O','Ŏ':'O','Ǒ':'O','Ô':'O','Ố':'O','Ộ':'O','Ồ':'O','Ổ':'O','Ỗ':'O','Ö':'O','Ȫ':'O','Ȯ':'O','Ȱ':'O','Ọ':'O','Ő':'O','Ȍ':'O','Ò':'O','Ỏ':'O','Ơ':'O','Ớ':'O','Ợ':'O','Ờ':'O','Ở':'O','Ỡ':'O','Ȏ':'O','Ꝋ':'O','Ꝍ':'O','Ō':'O','Ṓ':'O','Ṑ':'O','Ɵ':'O','Ǫ':'O','Ǭ':'O','Ø':'O','Ǿ':'O','Õ':'O','Ṍ':'O','Ṏ':'O','Ȭ':'O','Ƣ':'OI','Ꝏ':'OO','Ɛ':'E','Ɔ':'O','Ȣ':'OU','Ṕ':'P','Ṗ':'P','Ꝓ':'P','Ƥ':'P','Ꝕ':'P','Ᵽ':'P','Ꝑ':'P','Ꝙ':'Q','Ꝗ':'Q','Ŕ':'R','Ř':'R','Ŗ':'R','Ṙ':'R','Ṛ':'R','Ṝ':'R','Ȑ':'R','Ȓ':'R','Ṟ':'R','Ɍ':'R','Ɽ':'R','Ꜿ':'C','Ǝ':'E','Ś':'S','Ṥ':'S','Š':'S','Ṧ':'S','Ş':'S','Ŝ':'S','Ș':'S','Ṡ':'S','Ṣ':'S','Ṩ':'S','Ť':'T','Ţ':'T','Ṱ':'T','Ț':'T','Ⱦ':'T','Ṫ':'T','Ṭ':'T','Ƭ':'T','Ṯ':'T','Ʈ':'T','Ŧ':'T','Ɐ':'A','Ꞁ':'L','Ɯ':'M','Ʌ':'V','Ꜩ':'TZ','Ú':'U','Ŭ':'U','Ǔ':'U','Û':'U','Ṷ':'U','Ü':'U','Ǘ':'U','Ǚ':'U','Ǜ':'U','Ǖ':'U','Ṳ':'U','Ụ':'U','Ű':'U','Ȕ':'U','Ù':'U','Ủ':'U','Ư':'U','Ứ':'U','Ự':'U','Ừ':'U','Ử':'U','Ữ':'U','Ȗ':'U','Ū':'U','Ṻ':'U','Ų':'U','Ů':'U','Ũ':'U','Ṹ':'U','Ṵ':'U','Ꝟ':'V','Ṿ':'V','Ʋ':'V','Ṽ':'V','Ꝡ':'VY','Ẃ':'W','Ŵ':'W','Ẅ':'W','Ẇ':'W','Ẉ':'W','Ẁ':'W','Ⱳ':'W','Ẍ':'X','Ẋ':'X','Ý':'Y','Ŷ':'Y','Ÿ':'Y','Ẏ':'Y','Ỵ':'Y','Ỳ':'Y','Ƴ':'Y','Ỷ':'Y','Ỿ':'Y','Ȳ':'Y','Ɏ':'Y','Ỹ':'Y','Ź':'Z','Ž':'Z','Ẑ':'Z','Ⱬ':'Z','Ż':'Z','Ẓ':'Z','Ȥ':'Z','Ẕ':'Z','Ƶ':'Z','Ĳ':'IJ','Œ':'OE','ᴀ':'A','ᴁ':'AE','ʙ':'B','ᴃ':'B','ᴄ':'C','ᴅ':'D','ᴇ':'E','ꜰ':'F','ɢ':'G','ʛ':'G','ʜ':'H','ɪ':'I','ʁ':'R','ᴊ':'J','ᴋ':'K','ʟ':'L','ᴌ':'L','ᴍ':'M','ɴ':'N','ᴏ':'O','ɶ':'OE','ᴐ':'O','ᴕ':'OU','ᴘ':'P','ʀ':'R','ᴎ':'N','ᴙ':'R','ꜱ':'S','ᴛ':'T','ⱻ':'E','ᴚ':'R','ᴜ':'U','ᴠ':'V','ᴡ':'W','ʏ':'Y','ᴢ':'Z','á':'a','ă':'a','ắ':'a','ặ':'a','ằ':'a','ẳ':'a','ẵ':'a','ǎ':'a','â':'a','ấ':'a','ậ':'a','ầ':'a','ẩ':'a','ẫ':'a','ä':'a','ǟ':'a','ȧ':'a','ǡ':'a','ạ':'a','ȁ':'a','à':'a','ả':'a','ȃ':'a','ā':'a','ą':'a','ᶏ':'a','ẚ':'a','å':'a','ǻ':'a','ḁ':'a','ⱥ':'a','ã':'a','ꜳ':'aa','æ':'ae','ǽ':'ae','ǣ':'ae','ꜵ':'ao','ꜷ':'au','ꜹ':'av','ꜻ':'av','ꜽ':'ay','ḃ':'b','ḅ':'b','ɓ':'b','ḇ':'b','ᵬ':'b','ᶀ':'b','ƀ':'b','ƃ':'b','ɵ':'o','ć':'c','č':'c','ç':'c','ḉ':'c','ĉ':'c','ɕ':'c','ċ':'c','ƈ':'c','ȼ':'c','ď':'d','ḑ':'d','ḓ':'d','ȡ':'d','ḋ':'d','ḍ':'d','ɗ':'d','ᶑ':'d','ḏ':'d','ᵭ':'d','ᶁ':'d','đ':'d','ɖ':'d','ƌ':'d','ı':'i','ȷ':'j','ɟ':'j','ʄ':'j','ǳ':'dz','ǆ':'dz','é':'e','ĕ':'e','ě':'e','ȩ':'e','ḝ':'e','ê':'e','ế':'e','ệ':'e','ề':'e','ể':'e','ễ':'e','ḙ':'e','ë':'e','ė':'e','ẹ':'e','ȅ':'e','è':'e','ẻ':'e','ȇ':'e','ē':'e','ḗ':'e','ḕ':'e','ⱸ':'e','ę':'e','ᶒ':'e','ɇ':'e','ẽ':'e','ḛ':'e','ꝫ':'et','ḟ':'f','ƒ':'f','ᵮ':'f','ᶂ':'f','ǵ':'g','ğ':'g','ǧ':'g','ģ':'g','ĝ':'g','ġ':'g','ɠ':'g','ḡ':'g','ᶃ':'g','ǥ':'g','ḫ':'h','ȟ':'h','ḩ':'h','ĥ':'h','ⱨ':'h','ḧ':'h','ḣ':'h','ḥ':'h','ɦ':'h','ẖ':'h','ħ':'h','ƕ':'hv','í':'i','ĭ':'i','ǐ':'i','î':'i','ï':'i','ḯ':'i','ị':'i','ȉ':'i','ì':'i','ỉ':'i','ȋ':'i','ī':'i','į':'i','ᶖ':'i','ɨ':'i','ĩ':'i','ḭ':'i','ꝺ':'d','ꝼ':'f','ᵹ':'g','ꞃ':'r','ꞅ':'s','ꞇ':'t','ꝭ':'is','ǰ':'j','ĵ':'j','ʝ':'j','ɉ':'j','ḱ':'k','ǩ':'k','ķ':'k','ⱪ':'k','ꝃ':'k','ḳ':'k','ƙ':'k','ḵ':'k','ᶄ':'k','ꝁ':'k','ꝅ':'k','ĺ':'l','ƚ':'l','ɬ':'l','ľ':'l','ļ':'l','ḽ':'l','ȴ':'l','ḷ':'l','ḹ':'l','ⱡ':'l','ꝉ':'l','ḻ':'l','ŀ':'l','ɫ':'l','ᶅ':'l','ɭ':'l','ł':'l','ǉ':'lj','ſ':'s','ẜ':'s','ẛ':'s','ẝ':'s','ḿ':'m','ṁ':'m','ṃ':'m','ɱ':'m','ᵯ':'m','ᶆ':'m','ń':'n','ň':'n','ņ':'n','ṋ':'n','ȵ':'n','ṅ':'n','ṇ':'n','ǹ':'n','ɲ':'n','ṉ':'n','ƞ':'n','ᵰ':'n','ᶇ':'n','ɳ':'n','ñ':'n','ǌ':'nj','ó':'o','ŏ':'o','ǒ':'o','ô':'o','ố':'o','ộ':'o','ồ':'o','ổ':'o','ỗ':'o','ö':'o','ȫ':'o','ȯ':'o','ȱ':'o','ọ':'o','ő':'o','ȍ':'o','ò':'o','ỏ':'o','ơ':'o','ớ':'o','ợ':'o','ờ':'o','ở':'o','ỡ':'o','ȏ':'o','ꝋ':'o','ꝍ':'o','ⱺ':'o','ō':'o','ṓ':'o','ṑ':'o','ǫ':'o','ǭ':'o','ø':'o','ǿ':'o','õ':'o','ṍ':'o','ṏ':'o','ȭ':'o','ƣ':'oi','ꝏ':'oo','ɛ':'e','ᶓ':'e','ɔ':'o','ᶗ':'o','ȣ':'ou','ṕ':'p','ṗ':'p','ꝓ':'p','ƥ':'p','ᵱ':'p','ᶈ':'p','ꝕ':'p','ᵽ':'p','ꝑ':'p','ꝙ':'q','ʠ':'q','ɋ':'q','ꝗ':'q','ŕ':'r','ř':'r','ŗ':'r','ṙ':'r','ṛ':'r','ṝ':'r','ȑ':'r','ɾ':'r','ᵳ':'r','ȓ':'r','ṟ':'r','ɼ':'r','ᵲ':'r','ᶉ':'r','ɍ':'r','ɽ':'r','ↄ':'c','ꜿ':'c','ɘ':'e','ɿ':'r','ś':'s','ṥ':'s','š':'s','ṧ':'s','ş':'s','ŝ':'s','ș':'s','ṡ':'s','ṣ':'s','ṩ':'s','ʂ':'s','ᵴ':'s','ᶊ':'s','ȿ':'s','ɡ':'g','ᴑ':'o','ᴓ':'o','ᴝ':'u','ť':'t','ţ':'t','ṱ':'t','ț':'t','ȶ':'t','ẗ':'t','ⱦ':'t','ṫ':'t','ṭ':'t','ƭ':'t','ṯ':'t','ᵵ':'t','ƫ':'t','ʈ':'t','ŧ':'t','ᵺ':'th','ɐ':'a','ᴂ':'ae','ǝ':'e','ᵷ':'g','ɥ':'h','ʮ':'h','ʯ':'h','ᴉ':'i','ʞ':'k','ꞁ':'l','ɯ':'m','ɰ':'m','ᴔ':'oe','ɹ':'r','ɻ':'r','ɺ':'r','ⱹ':'r','ʇ':'t','ʌ':'v','ʍ':'w','ʎ':'y','ꜩ':'tz','ú':'u','ŭ':'u','ǔ':'u','û':'u','ṷ':'u','ü':'u','ǘ':'u','ǚ':'u','ǜ':'u','ǖ':'u','ṳ':'u','ụ':'u','ű':'u','ȕ':'u','ù':'u','ủ':'u','ư':'u','ứ':'u','ự':'u','ừ':'u','ử':'u','ữ':'u','ȗ':'u','ū':'u','ṻ':'u','ų':'u','ᶙ':'u','ů':'u','ũ':'u','ṹ':'u','ṵ':'u','ᵫ':'ue','ꝸ':'um','ⱴ':'v','ꝟ':'v','ṿ':'v','ʋ':'v','ᶌ':'v','ⱱ':'v','ṽ':'v','ꝡ':'vy','ẃ':'w','ŵ':'w','ẅ':'w','ẇ':'w','ẉ':'w','ẁ':'w','ⱳ':'w','ẘ':'w','ẍ':'x','ẋ':'x','ᶍ':'x','ý':'y','ŷ':'y','ÿ':'y','ẏ':'y','ỵ':'y','ỳ':'y','ƴ':'y','ỷ':'y','ỿ':'y','ȳ':'y','ẙ':'y','ɏ':'y','ỹ':'y','ź':'z','ž':'z','ẑ':'z','ʑ':'z','ⱬ':'z','ż':'z','ẓ':'z','ȥ':'z','ẕ':'z','ᵶ':'z','ᶎ':'z','ʐ':'z','ƶ':'z','ɀ':'z','ﬀ':'ff','ﬃ':'ffi','ﬄ':'ffl','ﬁ':'fi','ﬂ':'fl','ĳ':'ij','œ':'oe','ﬆ':'st','ₐ':'a','ₑ':'e','ᵢ':'i','ⱼ':'j','ₒ':'o','ᵣ':'r','ᵤ':'u','ᵥ':'v','ₓ':'x'};
            String.prototype.latinise=function(){return this.replace(/[^A-Za-z0-9\[\] ]/g,function(a){return Latinise.latin_map[a]||a})};
            String.prototype.latinize=String.prototype.latinise;
            String.prototype.isLatin=function(){return this==this.latinise()}
            
            return '<strong>'+ descripciones[this.x.replace(/ /g,'_').latinize()] +'</strong><br/>'+
            '<span style=\"color:'+this.points[0].series.color+'\">'+this.points[0].series.name+': <strong>'+this.points[0].y+'</strong><br/>';
        },
        headerFormat: '<strong>{point.key}</strong><br>'      
    },
    credits: {
         enabled: false
    },

    series: [{  
        name: '". mb_strtoupper($profesion,"UTF-8") ."',
        data: capacidades,
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

$script_empleabilidad = "$('#container_empleabilidad').highcharts({
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
        text: 'PARO (dificultad de conseguir trabajo) ',
        align: 'center'
    },
    legend: { enable: false },
    xAxis: {
        categories: [";
        
        $meses = ['enero','abril','julio','octubre'];
        $meses = array_merge($meses,$meses); // concatenar meses
        foreach ($meses as $n_mes => $mes) { 
            $year = ( $n_mes > count($meses)/2 - 1 )?'2015':'2014';
            $script_empleabilidad .= "'".ucfirst($mes)." ".$year."',";
        }
        
        $script_empleabilidad .= "]
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
        pointFormat: '<span style=\"color:{series.color}\">\u25CF</span> {series.name}: {point.y} / {point.stackTotal}'
    },
    credits: {
        enabled: false
    }, 
    series: [{
        name: '". mb_strtoupper($profesion,"UTF-8" ) ."',
        data: [ ". imprimirSeriesEmp($filas_empleabilidad, $btn_colabora_e_1) ." ],
        stack: '". $profesion ."'
  }]
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

/** SATISFACCION **/
$btn_colabora_sat_1 = 0;
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

  // incluir scripts y cerrar html 

    $html .= $script_salarios . $script_info . $script_capacidades . $script_empleabilidad . $script_formacion . $script_satisfaccion .'
  </script>
</html>';
    
    // guardar html
    fwrite($pagina_html, $html);
    fclose($pagina_html);

    } // end while
  } // end foreach

} catch( Exception $e ) {
  die('Error: '.$e->GetMessage());
}
?>