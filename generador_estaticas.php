<?php 
//eliminar el limite de ejecucion
set_time_limit(0);

require('conexion.php');

// Include Composer autoloader if not already done.
include 'vendor/autoload.php';

use \ForceUTF8\Encoding;

try { 
  $tablas = array( 
    'salarios'      => array('s_princ_min', 's_princ_med', 's_princ_max', 's_junior_min', 's_junior_med', 's_junior_max', 's_intermedio_min', 's_intermedio_med', 's_intermedio_max', 's_senior_min', 's_senior_med', 's_senior_max'),
    'empleabilidad' => array('parados', 'contratados', 'mes', 'anyo'),
    'capacidades'   => array('c_analisis', 'c_comunicacion', 'c_equipo', 'c_forma_fisica', 'c_objetivos', 'c_persuasion'),
    'info'          => array('descripcion'),
    //'satisfaccion'  => array('experiencia','grado_satisfaccion'),
    'formaciones'   => array('id', 'f_nombre_ppal','f_descripcion','duracion_academica','duracion_real')
  );

  function consulta($id_profesion, $tabla, $tablas, $pdo ) {
    $consulta = "SELECT ";
    $tabla_ref = ($tabla == 'info') ? 'p' : $tabla[0];

    foreach ($tablas[$tabla] as $campo) {
      $consulta .= $tabla_ref . '.' . $campo . ', ';
    }
    $consulta = substr($consulta, 0, -2);

    if ($tabla == 'info')
      $where = "WHERE";
    else if ($tabla == 'formaciones')
      $where = "INNER JOIN profesiones_formaciones pf ON p.id = pf.id_profesion INNER JOIN formaciones f ON f.cod = pf.id_formacion WHERE";
    else
      $where = ", ".$tabla." ".$tabla_ref." WHERE p.id = ".$tabla_ref.".id_profesion AND";

    $consulta .= " FROM profesiones p ".$where." p.id = ".$id_profesion;
    echo $consulta . '<br>';
    $rs = $pdo->prepare($consulta);
    $rs->execute();
    $filas = $rs->fetchAll();
    return $filas;
  }

  // Primero, consulta de nombres principales y alternativos
  $consulta_nombres = "SELECT id_profesion, nombre_ppal, nombre_alt FROM profesiones p INNER JOIN nombres_alt n ON p.id = n.id_profesion;";
  $rs_nombres = $pdo->prepare($consulta_nombres);
  $rs_nombres->execute();
  $nombres = $rs_nombres->fetchAll();
  $nombres_usados = array();
  $nombres_usados_alt = array();
  $count = 0;

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

  function createExcerpts($text, $length, $more_txt) { 
    $text = preg_replace('/[\n\r]/', '', $text);
    $text = str_replace('"','',$text);
    // primer letra en mayuscula forzando el UTF8
    $text = Encoding::toUTF8(ucfirst($text));
    // dividir el texto en dos
    $split_text = explode(' ', $text, $length);
    $excerpt = array_pop($split_text);
    $content = join(' ', $split_text);
    return $content . '<span class="excerpt"><span style="display:none;">' . $excerpt . '</span>' . '<strong class="more">' . $more_txt . '</strong></span>'; 
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

  function mediaEmpleabilidad($pdo, $meses, $anyos) {
    $medias = array();
    foreach ($meses as $n_mes => $mes) {
        $media = array();
        $anyo = $anyos[ceil(($n_mes + 1) / (count($meses) / count($anyos))) - 1];
        $consulta = "SELECT parados, contratados FROM empleabilidad WHERE mes LIKE '". $mes ."' AND anyo LIKE ". $anyo;
        $rs = $pdo->prepare($consulta);
        $rs->execute();
        $filas = $rs->fetchAll();
        foreach ($filas as $fila) {
          $media[] = empleabilidad($fila['contratados'], $fila['parados']);
        }
        if (count($media) > 0)
          $medias[] = round(array_sum($media) / count($media), 2);
    }
    return $medias;
  }

  function imprimirSeriesEmp($filas, $n_meses) {
    $counter = 0;
    $counter_rect = 0;
    $no_duplicado = true;
    $memo = [];
    $seriesEmp = array();
    foreach ($filas as $fila) {
      $memo[$counter] = $fila;
      if (count($memo) > 1)
        $no_duplicado = ($memo[$counter - 1]['mes'] !== $memo[$counter]['mes']);
      if ($no_duplicado && $counter_rect < $n_meses) {
        $counter_rect++;
        $emp = empleabilidad($fila['contratados'], $fila['parados']);
        array_push($seriesEmp, (is_null($emp) || $emp == 0) ? "0" : $emp); 
      }
      $counter++;
    }
    return $seriesEmp;
  }

  /** FORMACION **/
  function consultarFormacionesAnteriores($id_formacion, $campos_formacion, $pdo, $arbol_formaciones) {
      try {
          $consulta_formaciones = 'SELECT id_formacion_ant FROM formaciones_formacion_ant WHERE id_formacion = '. $id_formacion . ';';
          $rs = $pdo->prepare($consulta_formaciones);
          $rs->execute();
          $cod_formaciones_ant = $rs->fetchAll();
          $info_formaciones = array();
      
          foreach ($cod_formaciones_ant as $cod_formacion_ant) {
              $consulta_formacion_info = 'SELECT ' . join(', ', $campos_formacion) . ' FROM formaciones WHERE cod LIKE ' . $cod_formacion_ant[0] . ';';

              $rs = $pdo->prepare($consulta_formacion_info);
              $rs->execute();
              $info_formacion = $rs->fetchAll();
              $info_formaciones[] = $info_formacion;
          }
      } catch(PDOException $Exception) {
          echo "<p>Error en la consulta.<p>\n" . $Exception;
          exit;
      } 

      $formacion_ant = current($info_formaciones)[0];

      if (!empty($formacion_ant)) {
          $arbol_formaciones[] = $formacion_ant;
          return consultarFormacionesAnteriores($formacion_ant['id'], $campos_formacion, $pdo, $arbol_formaciones);
      } else {
          return $arbol_formaciones; 
      }
  }

  function getTotalAnyosEstudios($formaciones, $tipoDuracion) {
      $total = 0;
      foreach ($formaciones as $formacion) {
          $total += $formacion[$tipoDuracion];
      }

      return $total;
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
        $count++;
        // darle url al html estatico
        $profesion_nosignos = getNombreLimpio($profesion);
        $profesion_dashed = str_replace(' ', '-', $profesion_nosignos); // remplazar espacios en blanco por underscore
        $url_html = "profesiones/" . $profesion_dashed . ".html"; // agregar path y extension 
        // generar carpeta si no existe
        /*if (!file_exists("profesiones/" . $profesion_dashed)) {
          mkdir("profesiones/" . $profesion_dashed, 0777, true);
        }*/ 
        // crear html estatico o reescribirlo si ya existe!!
        $pagina_html = fopen($url_html, "w+") or die("No se puede crear este documento");
        echo '<h1><strong>'.$count.'</strong> pagina creada: '.$url_html.'</h1>';
      }
      
// comenzamos a generar el html como string
$html = '
<!DOCTYPE html>
<html>
  <head>
      <title>queserademi.com | '; $html .= ucfirst(mb_strtolower($profesion, 'UTF-8')) . '</title>
      <meta name="description" content="'; $html .= ucfirst(mb_strtolower($profesion, 'UTF-8')) . '">
      <!--Compatibilidad y móvil-->
      <meta http-equiv="Content-Language" content="es">
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="robots" content="noodp">
      <meta name="viewport" content="width=device-width, initial-scale = 1.0">
      <meta name="apple-mobile-web-app-capable" content="yes">
      <meta name="theme-color" content="#d5001e">
      <!--OGs-->
      <link rel="canonical" href="http://queserademi.com/'; $html .= $url_html . '">
      <meta property="og:locale" content="es_ES">
      <meta property="og:type" content="website">
      <meta property="og:title" content="'; $html .= ucfirst(mb_strtolower($profesion, 'UTF-8')) . ' | queserademi">
      <meta property="og:url" content="http://queserademi.com/'; $html .= $url_html . '">
      <meta property="og:site_name" content="queserademi">
      <meta property="og:image" content="http://queserademi.com/images/logo.png">
      <!--Links css-->
      <link rel="icon" type="image/x-icon" href="../images/logo.png">
      <link rel="stylesheet" href="../css/bootstrap.min.css">
      <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css">
      <link rel="stylesheet" href="../css/style.css">
      <link rel="stylesheet" href="../css/style-comparador.css">
    <!-- Google Tag Manager -->
    <script>
      (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({\'gtm.start\':
      new Date().getTime(),event:\'gtm.js\'});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!=\'dataLayer\'?\'&l=\'+l:\'\';j.async=true;j.src=
      \'https://www.googletagmanager.com/gtm.js?id=\'+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,\'script\',\'dataLayer\',\'GTM-KSJZX5B\');
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

    <div id="preloader"></div>
    <div class="background-image grayscale"></div>

    <div class="container-full">
      <form id="formulario" role="form" action="../comparador.php">
          <div class="row header ux-mobile-header">

            <div class="col-md-4 ux-mobile-input-container">
              <div class="dropdown clearfix">
                <div class="input-group" id="scrollable-dropdown-menu">
                  <input name="profesion" id="buscador" class="typeahead principal center-block form-control input-lg" type="text" data-tipo="profesiones" placeholder="Busca otra profesión y compara" autofocus required value="'; $html .= ucfirst(mb_strtolower($profesion, 'UTF-8')) . '" spellcheck="true" autocomplete="off">
                </div>
              </div>
            </div>

            <div class="col-md-4 hidden-sm hidden-xs text-center">
              <a href="http://queserademi.com">
                <h6 class="sublead">Tu comparador de profesiones</h6>
                <img class="img-responsive" src="../images/logo.svg" height="60px"> 
              </a>
            </div>

            <div class="col-md-4 ux-mobile-input-container">
              <div id="btnAddComparador">
                <span><i class="fa fa-plus-circle" aria-hidden="true"></i></span>
                <strong>Compara con otra profesión</strong>
              </div>
              <div class="dropdown clearfix" hidden>
                <div class="input-group" id="scrollable-dropdown-menu">
                  <input name="profesion_dos" id="buscador_dos" class="typeahead secundaria center-block form-control input-lg" type="text" data-tipo="profesiones" placeholder="Busca otra profesión y compara" required autofocus spellcheck="true" autocomplete="off" >
                </div>
              </div>
            </div>

          </div> 

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
    </div>

    <footer>
      <div class="row">
        <div class="col-lg-12 col-md-12 hidden-sm hidden-xs text-center">
          <button type="button" data-toggle="dropup" aria-expanded="false" class="btn-footer" id="btn-footer-md" ><span class="caret flecha"></span></button>
            </div>
            <div class="hidden-lg hidden-md col-sm-12 col-xs-12">
              <div class="col-sm-3 col-xs-3 text-center">
                <a href="http://queserademi.com"> 
                  <img class="img-menu" src="../images/logo.svg" width="35px" height="auto">       
                  </a>
              </div>
              <div class="col-sm-3 col-sm-offset-6 col-xs-3 col-xs-offset-6">
            <button type="button" data-toggle="dropup" aria-expanded="false" class="btn-footer" id="btn-footer-xs" ><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span></button>
          </div>
            </div>
        <div class="col-md-2 col-md-offset-0 hidden-sm hidden-xs col-xs-6 col-xs-offset-3 text-center">
              <a href="http://queserademi.com"> 
                  <p id="titulo" style="opacity:1;margin-top:-10px;">
                    <img class="image-container" src="../images/logo.svg">
                    <strong>que</strong>sera<strong>de</strong>mi
                  </p>
              </a>
            </div>
          <div class="col-md-10 col-sm-12 col-xs-12 text-center">
              <div class="col-md-2 col-md-offset-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
                  <a href="../colabora.php">cómo colaborar</a>
                  <span class="hidden-sm hidden-xs separador">|</span>
              </div>
              <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
                  <a href="../porquecolaborar.html">por qué colaborar</a>
                  <span class="hidden-sm hidden-xs separador">|</span>
              </div>
              <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
                  <a href="../quienessomos.html">quiénes somos</a>
                  <span class="hidden-sm hidden-xs separador">|</span>
              </div>
              <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
                  <a href="../noticias/">qué noticias</a>
              </div>
              <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu social">
                <ul class="share-buttons">
                  <li><a href="https://www.facebook.com/queserademicom" target="_blank" title="Share on Facebook" onclick="window.open("https://www.facebook.com/queserademicom"); return false;"><i class="fa fa-facebook-square fa-2x"></i></a></li>
                  <li><a href="mailto:?subject=Comparador%20de%20profesiones&body=:%20http%3A%2F%2Fwww.queserademi.com" target="_blank" title="Email" onclick="window.open("mailto:?subject=" + encodeURIComponent(document.title) + "&body=" +  encodeURIComponent(document.URL)); return false;"><i class="fa fa-envelope-square fa-2x"></i></a></li>
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

  </body>
  <!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script type="text/javascript" src="../js/jquery-2.1.3.js"></script>
  <script type="text/javascript" src="../js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../js/typeahead.0.9.3.min.js"></script>
  <script type="text/javascript" src="../js/highcharts.js"></script>
  <script type="text/javascript" src="../js/highcharts-more.js"></script>
  <script type="text/javascript" src="../js/modules/exporting.js"></script>
  <script type="text/javascript" src="../js/scripts.js" defer></script>
  <script type="text/javascript" src="../js/scripts-combobox.js"></script> 
  <script type="text/javascript" src="../js/graficas.js"></script>
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

$script_salarios .= "
var chartSalarios = {
    chart: {
        backgroundColor:'rgba(255, 255, 255, 0)',
        spacingBottom: 20,
        spacingTop: 20,
        spacingLeft: 20,
        spacingRight: 20,
        width: null,
        height: 380,
        events: {
            load: function(){
                this.myTooltip = new Highcharts.Tooltip(this, this.options.tooltip);                    
            }
        }
    },
    exporting: {
        chartOptions: {
            chart: {
                events: {
                  load: function(event) {                
                    this.renderer.image('http://queserademi.com/images/logo.png', 15, 15, 30, 30).add();
                  }
                } 
            }
        },
        buttons: {
            contextButton: {
                menuItems: [{
                    text: '<a><i class=\"fa fa-facebook-square fa-2x\" style=\"padding:5px\"></i>Compartir en Facebook</a>',
                      onclick: function(event) {
                          if (event.target.href === '') {
                              getUrlShare('facebook', this, event.target);    
                          }
                      }
                    },{
                      text: '<a><i class=\"fa fa-linkedin-square fa-2x\" style=\"padding:5px\"></i>Compartir en LinkedIn</a>'
                },{
                    separator: true
                },{
                    text: '<a href=\"#\"><i class=\"glyphicon glyphicon-download-alt\" style=\"padding:5px\"></i>Descargar JPEG</a>',
                    onclick: function() {
                        this.exportChart({
                            type: 'image/jpeg'
                        });
                    }
                }]
            }
        }
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
        enable: false ,
        itemStyle: {
            width: '300%'
        },
        title: {
            text: '<span>(Click para ver información)</span>',
            style: {
                fontStyle: 'italic',
                fontSize: '9px',
                color: '#888'
            }
        }  
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
        headerFormat: '<strong style=\"font-size:16px\">{point.x} años de experiencia</strong><br>',
        valueSuffix: ' €',
        style: {
            display: 'block', 
            width: '300px',
            whiteSpace: 'normal' 
        },
        enabled: false
    },
    credits: {
        enabled: false
    },
    plotOptions: {
        arearange: {
            fillOpacity: 0.5
        },
        series: {
            cursor: 'pointer',
            allowPointSelect: true,
            stickyTracking: false,
            events: {
                click: function(evt) {
                    this.chart.myTooltip.refresh(evt.point, evt);
                },
                mouseOut: function() {
                    this.chart.myTooltip.hide();
                },
                legendItemClick: function() {
                    return false; 
                }                        
            } 
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
};";

$script_salarios .= "$('#container_salarios').highcharts(chartSalarios);";

if( $btn_colabora_s_1 > 0 ) { 
    $script_salarios .= "var capa_aviso = '<div class=\"capa-aviso\">';
    capa_aviso += '<div class=\"cerrar-aviso\"><a href=\"#\"><img class=\"icon\" src=\"../images/cross.svg\"></img></a></div>';
    capa_aviso += '<div class=\"col-md-10 col-md-offset-1\">';
    capa_aviso += '<h3>Aún no tenemos imformación suficiente!</h3>';

        capa_aviso += '<p class=\"text-center\">Ayúdanos a completar información sobre <strong>salario</strong> de la profesión<br>';
        capa_aviso += '<strong>". mb_strtoupper($profesion,"UTF-8") ."</strong></p>';
        capa_aviso += '<a href=\"../colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: #d5001e; color: #d5001e;\">Colabora!</a>';

    capa_aviso += '</div></div>';

    $('#container_salarios').append(capa_aviso);";
} 

/** INFO **/

$script_info = "$('#container_info').html('<h5 style=\"margin:15px; font-weight: bold;\">INFORMACIÓN</h5><div id=\"info\"></div>');";

if( isset( $profesion ) ) {  
    $script_info .= "$('#info').append('<h4 class=\"principal nombre\">". mb_strtoupper($profesion,"UTF-8" ) ."</h4>');";
    if( empty( $filas_info[0]['descripcion'] ) ) { 
        $script_info .= "$('#info').append('<p class=\"descripcion\" id=\"desc1\">Falta información! Ayúdanos a conseguirla.</p>');
        $('#info').append('<div class=\"col-md-8 col-md-offset-2\"><a href=\"../colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: #d5001e; color: #d5001e;\">Colabora!</a></div>');";
    } else { 
        $script_info .= "$('#info').append('<p class=\"descripcion\">". createExcerpts($filas_info[0]["descripcion"], 150, " [ + ]") . "</p>');";
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
    'Logro de objetivos'        => 'Orientado a objetivos, resultados, etc.',
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
        height: 380,
        events: {
            load: function(){
                this.myTooltip = new Highcharts.Tooltip(this, this.options.tooltip);                    
            }
        } 
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
    legend: { 
        enable: false,
        itemStyle: {
            width: '300%'
        },
        title: {
            text: '<span>(Click para ver información)</span>',
            style: {
                fontStyle: 'italic',
                fontSize: '9px',
                color: '#888'
            }
        } 
    },
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
        headerFormat: '<strong style=\"font-size:17px\">{point.key}</strong><br>',
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
            '<span style=\"color:'+this.points[0].series.color+'\">'+this.points[0].series.name+': </span>(<strong>'+this.points[0].y+'</strong>/5)<br/>';
        },
        style: {
            display: 'block', 
            width: '300px',
            whiteSpace: 'normal' 
        }     
    },
    exporting: {
        buttons: {
           anotherButton: {
                text: '???',
                y: 28,
                x: 0,
                width: 24,
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
            },
            contextButton: {
                menuItems: [{
                    text: '<a><i class=\"fa fa-facebook-square fa-2x\" style=\"padding:5px\"></i>Compartir en Facebook</a>',
                      onclick: function(event) {
                          if (event.target.href === '') {
                              getUrlShare('facebook', this, event.target);    
                          }
                      }
                    },{
                      text: '<a><i class=\"fa fa-linkedin-square fa-2x\" style=\"padding:5px\"></i>Compartir en LinkedIn</a>'
                },{
                    separator: true
                },{
                    text: '<a href=\"#\"><i class=\"glyphicon glyphicon-download-alt\" style=\"padding:5px\"></i>Descargar JPEG</a>',
                    onclick: function() {
                        this.exportChart({
                            type: 'image/jpeg'
                        });
                    }
                }]
            }
        },
        chartOptions: {
            chart: {
                events: {
                  load: function(event) {                
                    this.renderer.image('http://queserademi.com/images/logo.png', 15, 15, 30, 30).add();
                  }
                } 
            }
        }
    },
    credits: {
         enabled: false
    },
    plotOptions: {
        series: {
            cursor: 'pointer',
            stickyTracking: false,
            events: {
                click: function(evt) {
                    this.chart.myTooltip.refresh(evt.point, evt);
                },
                mouseOut: function() {
                    this.chart.myTooltip.hide();
                },
                legendItemClick: function() {
                    return false; 
                }                        
            }          
        }
    },
    series: [{  
        name: '". $profesion ."',
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
        capa_aviso += '<a href=\"../colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: #d5001e; color: #d5001e;\">Colabora!</a>';

    capa_aviso += '</div>';
    capa_aviso += '</div>';

    $('#container_capacidades').append(capa_aviso);";
}

/** EMPLEABILIDAD **/

$btn_colabora_e_1 = 0;
$meses = ['enero', 'abril', 'julio', 'octubre'];
$meses = array_merge($meses, $meses, $meses, $meses); // concatenar meses 
$anyos = ['2014', '2015', '2016', '2017'];
//array_pop($meses); // y eliminar el ultimo elemento

// busqueda de nulos en empleabilidad
foreach ($filas_empleabilidad as $fila_empleabilidad) { 
  $empleabilidad = empleabilidad($fila_empleabilidad['contratados'], $fila_empleabilidad['parados']); 
  if(is_null($empleabilidad))
    $btn_colabora_e_1++;
}

$script_empleabilidad = "
var seriesEmp = [". join(", ", imprimirSeriesEmp($filas_empleabilidad, count($meses))) . "];
var seriesEmpMedia = [". join(", ", mediaEmpleabilidad($pdo, $meses, $anyos)) . "];";

$media_min = min(mediaEmpleabilidad($pdo, $meses, $anyos));
$media_max = max(mediaEmpleabilidad($pdo, $meses, $anyos));

$script_empleabilidad .= "
var chartEmpleabilidad = {
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
        height: 380,
        events: {
            load: function(){
                this.myTooltip = new Highcharts.Tooltip(this, this.options.tooltip);                    
            }
        }
    },
    exporting: {
      chartOptions: {
        chart: {
            events: {
              load: function(event) {                
                this.renderer.image('http://queserademi.com/images/logo.png', 15, 15, 30, 30).add();
              }
            } 
        }
      },
      buttons: {
          contextButton: {
              menuItems: [{
                  text: '<a><i class=\"fa fa-facebook-square fa-2x\" style=\"padding:5px\"></i>Compartir en Facebook</a>',
                  onclick: function(event) {
                      if (event.target.href === '') {
                          getUrlShare('facebook', this, event.target);    
                      }
                  }
              },{
                  text: '<a><i class=\"fa fa-linkedin-square fa-2x\" style=\"padding:5px\"></i>Compartir en LinkedIn</a>'
              },{
                  separator: true
              },{
                  text: '<a href=\"#\"><i class=\"glyphicon glyphicon-download-alt\" style=\"padding:5px\"></i>Descargar JPEG</a>',
                  onclick: function() {
                      this.exportChart({
                          type: 'image/jpeg'
                      });
                  }
              }]
          }
      }
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
    legend: { 
        enable: false,
        itemStyle: {
            width: '300%'
        },
        title: {
            text: '<span>(Click para ver información)</span>',
            style: {
                fontStyle: 'italic',
                fontSize: '9px',
                color: '#888'
            }
        } 
    },
    xAxis: {
        categories: [ ";
          foreach ($meses as $n_mes => $mes) { 
            $anyo = $anyos[ceil(($n_mes + 1) / (count($meses) / count($anyos))) - 1];
            $script_empleabilidad .= "'".ucfirst($mes)." ".$anyo."'";
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
        },
        plotBands: [
            { // Paro alto
                from: ". $media_max .",
                to: 100,
                color: 'rgba(0, 0, 0, 0.3)',
                label: {
                    rotation: 90,
                    verticalAlign: 'top',
                    x: 2,
                    text: 'ALTO',
                    style: {
                        color: '#DDDDDD',
                        fontSize:'12px'
                    }
                }
            }, { // Paro medio
                from: ". $media_min .",
                to: ". $media_max .",
                color: 'rgba(0, 0, 0, 0.2)'
            }, { // Paro bajo
                from: 0,
                to: ". $media_min .",
                color: 'rgba(0, 0, 0, 0.1)',
                label: {
                    rotation: 90,
                    verticalAlign: 'top',
                    x: 2,
                    text: 'BAJO',
                    style: {
                        color: '#AAA',
                        fontSize:'12px'
                    }
                }
            }
        ]
    },
    tooltip: {
        headerFormat: '<strong style=\"font-size:16px\">{point.key}</strong><br><br>',
        pointFormat: '<span style=\"color:{series.color}\">{series.name}: </span><strong>{point.y}</strong>',
        valueSuffix: ' %',
        style: {
            display: 'block', 
            width: '300px',
            whiteSpace: 'normal' 
        },
        enabled: false
    },
    credits: {
        enabled: false
    },
    plotOptions: {
        series: {
            cursor: 'pointer',
            stickyTracking: false,
            events: {
                click: function(evt) {
                    this.chart.myTooltip.refresh(evt.point, evt);
                },
                mouseOut: function() {
                    this.chart.myTooltip.hide();
                },
                legendItemClick: function() {
                    return false; 
                }                       
            }          
        }
    }, 
    series: [
      {
        name: '". $profesion ."',
        data: seriesEmp,
        stack: '". $profesion ."'
      },{
        name: 'Media de paro de todas las profesiones',
        type: 'spline',
        data: seriesEmpMedia,
        stack: 'Media de paro',
        color: 'rgba(0, 0, 0, 0.2)',
        dashStyle: 'shortdot',
        marker: {
            fillColor: 'transparent',
            lineWidth: 1,
            lineColor: 'rgba(0, 0, 0, 0.2)',
        }
      }
    ]
};";

$script_empleabilidad .= "$('#container_empleabilidad').highcharts(chartEmpleabilidad);";

if( $btn_colabora_e_1 > 0 ) { 
    $script_empleabilidad .= "var capa_aviso = '<div class=\"capa-aviso\">';
    capa_aviso += '<div class=\"cerrar-aviso\"><a href=\"#\"><img class=\"icon\" src=\"../images/cross.svg\"></img></a></div>';
    capa_aviso += '<div class=\"col-md-10 col-md-offset-1\">';
    capa_aviso += '<h3>Aún no tenemos imformación suficiente!</h3>';

        capa_aviso += '<p class=\"text-center\">Ayúdanos a completar información sobre <strong>desempleo</strong> de la profesión<br>';
        capa_aviso += '<strong>". mb_strtoupper($profesion,"UTF-8") ."</strong></p>';
        capa_aviso += '<a href=\"../colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: #d5001e; color: #d5001e;\">Colabora!</a>';

    capa_aviso += '</div>';
    capa_aviso += '</div>';

    $('#container_empleabilidad').append(capa_aviso);";
} 

/** NOTICIAS **/

$script_noticias = "

$('#container_noticias').html('<h5 style=\"margin: 15px; font-weight: bold;\">BLOG QUESERADEMI</h5><div id=\"noticiasContainer\"></div>');

var loaded = false;

function showNews() {
  if(loaded) return;

  $.ajax({
      url: 'http://queserademi.com/noticias/wp-json/wp/v2/posts',
      method: 'GET',
      success: function(result) {
          console.log(result);
          var title, content, src, i, posts = '';
          for (i = 0; i < result.length; i++) {
              title = result[i].title.rendered;
              content = result[i].excerpt.rendered;
              src = result[i].link;
              posts += '<a href=\"' + src + '\" class=\"list-group-item list-group-item-action\"><h4><strong>' + title + '</strong></h4><p>' + content + '</p></a>';
          }
          $('#noticiasContainer').append('<div class=\"list-group\">' + posts + '</div>');
      },
      error: function(xhr, textStatus, errorThrown) {
          console.log(xhr, textStatus, errorThrown);
          $('#noticiasContainer').append('<h2>No hay noticias!</h2>');
      }
  });

  loaded = true;
}

showNews();

";

/** FORMACION **/

$btn_colabora_f_1 = false; 
$arbol_formaciones = array();

  if (isset($profesion) && !empty($filas_formaciones)) {
    $ultima_formaciones = end($filas_formaciones);

    $arbol_formaciones[] = $ultima_formaciones;
    $arbol_formaciones = consultarFormacionesAnteriores($ultima_formaciones['id'], $tablas['formaciones'], $pdo, $arbol_formaciones);
  }

  $series = array();
  if( isset($profesion) && !empty($filas_formaciones) ) {
      foreach ($arbol_formaciones as $formac) {
          if ($formac['duracion_academica']) {
             $serie = '{';
              $serie .= "name: '" . $formac['f_nombre_ppal'] . "', ";
              $serie .= 'data: [' . $formac['duracion_academica'] . ', 0]';
              $serie .= '}';
              $series[] = $serie; 
          }
      }
  } else { 
    $btn_colabora_f_1 = true; 
  }

  $script_formacion = "
  var chartFormacion = {
      chart: {
          type: 'bar',
          backgroundColor:'rgba(255, 255, 255, 0)',
          spacingBottom: 20,
          spacingTop: 20,
          spacingLeft: 20,
          spacingRight: 20,
          width: null,
          height: 380,
          events: {
              load: function(){
                  this.myTooltip = new Highcharts.Tooltip(this, this.options.tooltip);                    
              }
          }
      },
      title: {
          text: 'FORMACIÓN',
          align: 'center',
          style: { 
              'color': '#555',
              'fontSize': '14px',
              'fontWeight': 'bold'
          }
      },
      xAxis: {
          categories: ['" . mb_strtoupper($profesion, 'UTF-8') . "<br><strong>[" . getTotalAnyosEstudios($arbol_formaciones, 'duracion_academica') . " años]</strong>']
      },
      yAxis: {
          min: 0,
          title: {
              text: 'Duración de estudios (años)'
          }
      },
      legend: {
          reversed: false,
          enabled: false
      },
      tooltip: {
          headerFormat: '',
          pointFormat: '<span>{series.name}</span><br><strong>Duración (años) &gt;&gt; {point.y}</strong>',
          style: {
              display: 'block', 
              width: '300px',
              whiteSpace: 'normal' 
          },
          enabled: false
      },
      credits: {
          enabled: false
      },
      colorBypoint: true,
      colors: [ '#160000', '#210011', '#2c0017', '#420022', '#58002e', '#751c4a', '#975577', '#ba8da4', '#dcc6d1', '#ede2e8'],
      plotOptions: {
          series: {
              cursor: 'pointer',
              stacking: 'normal',
              stickyTracking: false,
              events: {
                  click: function(evt) {
                      this.chart.myTooltip.refresh(evt.point, evt);
                  },
                  mouseOut: function() {
                      this.chart.myTooltip.hide();
                  }                       
              } 
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
                  text: 'Dónde estudiar?',
                  onclick: function () {
                      alert('Dónde estudiar? En desarrollo... Disculpe las molestias');
                  }
              },
              contextButton: {
                  menuItems: [{
                      text: '<a><i class=\"fa fa-facebook-square fa-2x\" style=\"padding:5px\"></i>Compartir en Facebook</a>',
                      onclick: function(event) {
                          if (event.target.href === '') {
                              getUrlShare('facebook', this, event.target);    
                          }
                      }
                  },{
                      text: '<a><i class=\"fa fa-linkedin-square fa-2x\" style=\"padding:5px\"></i>Compartir en LinkedIn</a>'
                  },{
                      separator: true
                  },{
                      text: '<a href=\"#\"><i class=\"glyphicon glyphicon-download-alt\" style=\"padding:5px\"></i>Descargar JPEG</a>',
                      onclick: function() {
                          this.exportChart({
                              type: 'image/jpeg'
                          });
                      }
                  }]
              }
          },
          chartOptions: {
              chart: {
                  events: {
                    load: function(event) {                
                      this.renderer.image('http://queserademi.com/images/logo.png', 15, 15, 30, 30).add();
                    }
                  } 
              }
          }
      },
      series: [" . join($series, ',') . "]
  };

  $('#container_formacion').highcharts(chartFormacion);";


if($btn_colabora_f_1) {
    $script_formacion .= "
    var capa_aviso = '<div class=\"capa-aviso\">';
    capa_aviso += '<div class=\"cerrar-aviso\"><a href=\"#\"><img class=\"icon\" src=\"../images/cross.svg\"></img></a></div>';
    capa_aviso += '<div class=\"col-md-10 col-md-offset-1\">';
    capa_aviso += '<h3>Aún no tenemos imformación suficiente!</h3>';

        capa_aviso += '<p class=\"text-center\">Ayúdanos a completar información sobre <strong>formación</strong> de la profesión<br>';
        capa_aviso += '<strong>" . mb_strtoupper($profesion,"UTF-8") . "</strong></p>';
        capa_aviso += '<a href=\"../colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: #d5001e; color: #d5001e;\">Colabora!</a>';

    capa_aviso += '</div>';
    capa_aviso += '</div>';

    // debe aparecer despues de 1 segundo
    $('#container_formacion').append(capa_aviso);";
}

/*

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
        height: 380,
        events: {
            load: function(){
                this.myTooltip = new Highcharts.Tooltip(this, this.options.tooltip);                    
            }
        }
    },
    exporting: {
        chartOptions: {
            chart: {
                events: {
                  load: function(event) {                
                    this.renderer.image('http://queserademi.com/images/logo.png', 15, 15, 30, 30).add();
                  }
                } 
            }
        },
        buttons: {
            contextButton: {
                menuItems: [{
                    text: '<a><i class=\"fa fa-facebook-square fa-2x\" style=\"padding:5px\"></i>Compartir en Facebook</a>',
                      onclick: function(event) {
                          if (event.target.href === '') {
                              getUrlShare('facebook', this, event.target);    
                          }
                      }
                    },{
                      text: '<a><i class=\"fa fa-linkedin-square fa-2x\" style=\"padding:5px\"></i>Compartir en LinkedIn</a>'
                },{
                    separator: true
                },{
                    text: '<a href=\"#\"><i class=\"glyphicon glyphicon-download-alt\" style=\"padding:5px\"></i>Descargar JPEG</a>',
                    onclick: function() {
                        this.exportChart({
                            type: 'image/jpeg'
                        });
                    }
                }]
            }
        }
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
    tooltip: {
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
        },
        series: {
            stickyTracking: false,
            events: {
                click: function(evt) {
                    this.chart.myTooltip.refresh(evt.point, evt);
                },
                mouseOut: function() {
                    this.chart.myTooltip.hide();
                },
                legendItemClick: function() {
                    return false; 
                }                        
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
        capa_aviso += '<a href=\"../colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: #d5001e; color: #d5001e;\">Colabora!</a>';

    capa_aviso += '</div>';
    capa_aviso += '</div>';

    $('#container_satisfaccion').append(capa_aviso);";
  }
*/
  // incluir scripts y cerrar html 

    $html .= $script_salarios . $script_info . $script_capacidades . $script_empleabilidad . $script_noticias . $script_formacion; 
    //$html .= $script_satisfaccion;
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

