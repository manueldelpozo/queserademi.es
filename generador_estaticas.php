<!DOCTYPE html>
<html>
  <head>
    <title>Generador de estaticas | queserademi.com</title>
    <style type="text/css">

      .load-bar {
        position: fixed; 
        top: 0; 
        left: 0; 
        height: 30px; 
      }

      .load-bar-bg {
        background-color: lightgrey; 
        right: 0;
        width: 100%; 
        z-index: 1;
      }

      .load-bar-complete {
        background-color: green; 
      }

      .load-bar-text {
        background-color: lightgrey; 
        font-family: Arial; 
        color: green; 
        position: fixed; 
        top: 0; 
        right: 0; 
        height: 30px; 
        width: 60px;
        text-align: center; 
        display: flex; 
        align-items: center;
      }

    </style>
    <script>
      function goToBottom() {
        window.scrollTo(0, document.body.scrollHeight);
      }

      function onElementHeightChange(elm, callback){
        var lastHeight = elm.clientHeight, newHeight;
        (function run(){
            newHeight = elm.clientHeight;
            if (lastHeight != newHeight) {
              callback();
            }
                
            lastHeight = newHeight;

            if (elm.onElementHeightChangeTimer) {
              clearTimeout(elm.onElementHeightChangeTimer);
            }
                
            elm.onElementHeightChangeTimer = setTimeout(run, 200);
        })();
      }

      onElementHeightChange(document.body, function(){
        goToBottom();
      });
    </script>
  </head>
  <body>
    <div class="load-bar load-bar-bg"></div>

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
    'competencias'        => array('c_iniciativa', 'c_resolucion', 'c_creatividad', 'c_planificacion', 'c_aprendizaje', 'c_comunicacion', 'c_negociacion', 'c_cliente', 'c_critica', 'c_analisis', 'c_calidad', 'c_espacialidad', 'c_coordinacion', 'c_descubrimiento', 'c_empatia', 'c_equipo', 'c_social', 'c_adaptabilidad', 'c_liderazgo', 'c_integridad', 'c_transmision', 'c_tecnologia', 'c_sensibilidad'),
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
  

  try {
    $pagina_index = fopen('profesiones/index.html', "w+");
  } catch( Exception $e ) {
    //echo 'No se puede crear este documento: ' . e
  }

  $index = '
<!DOCTYPE html>
<html>
  <head>
      <title>Lista de profesiones | queserademi.com</title>
      <meta name="description" content="&#10162; Comparador | Paro | Desempleo | Tasa de empleo | Salario bruto | Sueldo mínimo | Cuanto gana | Competencias profesionales | Descargar información | Cuanto tiempo se tarda | Trabajo del futuro | Profesiones mejor pagadas | Cuales son las mejores carreras para estudiar | Qué carrera estudiar</em>">
      <!--Compatibilidad y móvil-->
      <meta http-equiv="Content-Language" content="es">
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="robots" content="noodp">
      <meta name="viewport" content="width=device-width, initial-scale = 1.0">
      <meta name="apple-mobile-web-app-capable" content="yes">
      <meta name="theme-color" content="#d62e46">
      <!--OGs-->
      <link rel="canonical" href="https://queserademi.com/profesiones">
      <meta property="og:locale" content="es_ES">
      <meta property="og:type" content="website">
      <meta property="og:title" content="Lista de profesiones | queserademi">
      <meta property="og:description" content="&#10162; Comparador | Paro | Desempleo | Tasa de empleo | Salario bruto | Sueldo mínimo | Cuanto gana | Competencias profesionales | Descargar información | Cuanto tiempo se tarda | Trabajo del futuro | Profesiones mejor pagadas | Cuales son las mejores carreras para estudiar | Qué carrera estudiar">
      <meta property="og:url" content="https://queserademi.com/profesiones">
      <meta property="og:site_name" content="queserademi">
      <meta property="og:image" content="https://queserademi.com/images/logo.png">
      <!--Links css-->
      <link rel="icon" type="image/x-icon" href="../images/logo.png">
      <link rel="stylesheet" href="../css/bootstrap.min.css">
      <link rel="stylesheet" href="../css/font-awesome.css">
      <link rel="stylesheet" href="../css/style.css">
      <link rel="stylesheet" href="../css/style-comparador.css">
      <script src="../js/w3.js"></script>
    <!-- Google Tag Manager -->
    <script>
      (function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,\'script\',\'https://www.google-analytics.com/analytics.js\',\'ga\');

      ga(\'create\', \'UA-64706657-1\', \'auto\');
      ga(\'send\', \'pageview\');

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
    <!-- Facebook script -->
    <div id="fb-root"></div>
    <script>
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = \'https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v2.10\';
      fjs.parentNode.insertBefore(js, fjs);
    }(document, \'script\', \'facebook-jssdk\'));
    </script>
    <!-- End Facebook script -->

    <div class="row header qsdm-bgcolor-blue">

      <div class="col-md-1 col-sm-1 col-xs-2">
        <a href="https://queserademi.com">
          <img class="img-responsive" src="../images/logo-blanco.svg">
        </a>
      </div>

      <div class="col-md-4 col-sm-11 col-xs-10">  
        <h1 class="qsdm-color-white"><strong>Lista</strong> de profesiones</h1> 
      </div>

    </div>

    <div class="col-xs-12 margen"></div>
    <div class="col-xs-12 margen"></div>
    <div class="col-xs-12 margen"></div>

    <ul id="listaProfesiones" class="list-group list-group-flush">

    ';

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

  function prepareText($text) {
    $text = preg_replace( '/[\n\r]/', ' ', $text); // remove breaks
    $text = str_replace('"','',$text); // remove "
    $text = preg_replace( '/^(Nota:.*\.?)$/', ' ', $text); // remove Nota
    return Encoding::toUTF8(ucfirst($text)); // primer letra en mayuscula forzando el UTF8
  }

  function parseToList($text, $first_symbol, $second_symbol) {
      if (empty($text)) {
          return $text;
      }
      $split_text = explode($first_symbol, $text);
      if (count($split_text) <= 1) {
          return $text;
      }
      $list = array_pop($split_text);
      $initial_text = join(' ', $split_text);
      $list_items = explode($second_symbol, $list);
      return '<span>' . $initial_text . ':</span><ul class="list-group"><li class="list-group-item">' . join('</li><li class="list-group-item">', $list_items) . '</li></ul>';
  }

  function createExcerpts($text, $length, $more_txt) { 
      $split_text = explode(' ', $text, $length); // dividir el texto en dos
      $excerpt = array_pop($split_text);
      $content = join(' ', $split_text);

      $excerpt = parseToList($excerpt, ': -', '; -');
      $excerpt = parseToList($excerpt, ': a)', '; b)');
      
      return $content . '<div class="excerpt"><span hidden>' . $excerpt . '</span>' . '<strong class="more">' . $more_txt . '</strong></div>'; 
  }

  function getCompetenciasValues($competencias, $values) {
      $output = '';
      foreach ($competencias as $key => $competencia) {
          $value = 0;
          if ($values[$key] && is_numeric($values[$key])) {
              $value = 1; 
          }

          $output .= '{';
          $output .= 'name: "' . $competencia['name'] . '",';
          $output .= 'description: "' . $competencia['description'] . '",';
          $output .= 'icon: "' . $competencia['icon'] . '",';
          $output .= 'x: ' . $competencia['position']['x'] . ',';
          $output .= 'y: ' . $competencia['position']['y'] . ',';
          $output .= 'value: ' . $value;
          $output .= '},';
      }
      return $output;
  }

  function getDescriptionCompetencias($competencias) {
    $output = array();
    foreach($competencias as $competencia) { 
      $obj = "'" . $competencia['name'] . "':" . "{"
      ."'description':" . "'" . $competencia['description'] . "',"
      ."'icon':" . "'" . $competencia['icon'] . "'"
      ."}";
      array_push($output, $obj);
    }   
    return join(', ', $output);
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
          echo "<div>Error en la consulta.</div>\n" . $Exception;
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

  foreach ($nombres as $nombre) {

  if (array_search($nombre, $nombres) > $_COUNT_FROM) {  

    $repetir = true; // accede para cada iteracion del foreach
    $profesion = $nombre_ppal = $nombre_alt = $id_profesion = '';
    $nombre_ppal = $nombre['nombre_ppal']; // por defecto no se da valor al nombre ppal
    $nombre_alt = $nombre['nombre_alt'];
    $id_profesion = $nombre['id_profesion'];
    $nombres_alt = array();

    if (isLookingFor($nombre_ppal, $_IS_SEARCH_ALL)) { 

    while($repetir) { // mientras haya un nombre ppal se repetira este bucle

      $repetir = false;  //niega la repeticion para que solo haya un bucle
      
      // coger el nombre ppal si la id aun no ha sido usada
      if ( !in_array($id_profesion, $nombres_usados, TRUE) ) { // solo una vez!!!
        $repetir = true; // repetimos while en este caso para buscar un nombre alternativo
        $profesion = $nombre_ppal; // en este caso $nombre sera el nombre_ppal en lugar del alternativo
        echo '<div style="width: 100%; border-bottom: 1px solid black;"></div>';
        echo '<div>Nombre principal: <strong>'.$nombre_ppal.'</strong></div><br>';
      } else { // en le caso de que haya sido usado buscamos nombre alternativo
        echo '<span>Buscando nombre alternativo... </span>';
        if (empty($nombre_alt) || is_null($nombre_alt) || $nombre_alt == 'test' || in_array($nombre_alt, $nombres_usados_alt, TRUE)) {
          $repetir = false; //romper el bucle si el nombre alternativo esta vacio, nulo o repetido
          echo '<span>No hay nombre alternativo</span><br>';
          continue;        
        } else {
          $repetir = true; // repetimos while en este caso para buscar mas nombres alternativo
          $profesion = $nombre_alt; // profesion pasa a ser el nombre alternativo
          array_push($nombres_usados_alt, $profesion); // y lo incluimos en nombres alternativos usados
          echo '<span>Hay nombre alternativo: <strong>'.$nombre_alt.'</strong></span><br>';
          $nombres_alt[] = $nombre_alt;
        }
      }
      // incluir id_profesion en nombres usados
      array_push($nombres_usados, $id_profesion);

      foreach ($tablas as $tabla => $value) {
        $filas = 'filas_'.$tabla;
        $$filas = consulta($id_profesion, $tabla, $tablas, $pdo);
      }

      if (!empty($profesion)) {
        $_COUNT_FROM++;
        $percentage_complete = round(100 * $_COUNT_FROM / $_TOTAL_PROFESSION, 1);
        // darle url al html estatico
        $profesion_nosignos = getNombreLimpio($profesion);
        $profesion_dashed = str_replace(' ', '-', $profesion_nosignos); // remplazar espacios en blanco por underscore
        $monosilabes = array('-a-', '-e-', '-o-', '-u-', '-y-', '-en-', '-de-', '-del-', '-al-', '-el-', '-la-', '-los-', '-las-', '-para-', '-por-');
        $profesion_dashed = str_replace($monosilabes, '-', $profesion_dashed); // clean monosilabes
        $url_html = "profesiones/" . $profesion_dashed . ".html"; // agregar path y extension  
        // crear html estatico o reescribirlo si ya existe!!
        /*try {
          $pagina_html = fopen($url_html, "w+");
        } catch( Exception $e ) {
          //echo 'No se puede crear este documento: ' . e
        }*/
        echo '<div class="load-bar load-bar-complete" style="width: ' . $percentage_complete . '%; z-index: ' .$_COUNT_FROM . ';"></div>';
        echo '<div class="load-bar-text" style="z-index: ' .$_COUNT_FROM . ';">' . $percentage_complete . '%</div>';
        echo '<div><strong style="color: green;">' . $percentage_complete . ' % </strong> - pagina creada '. $_COUNT_FROM .': <strong style="color: green;">'.$url_html.'</strong></div>';
      }

      $nombre_profesion = ucfirst(mb_strtolower($profesion, 'UTF-8'));
      $description_info = prepareText($filas_info[0]["descripcion"]);
      $is_hidden = ($_COUNT_FROM > 10); // Hide when is more than 8

      $index .= '
      <li class="list-group-item list-group-item-action flex-column align-items-start ' . ($is_hidden ? 'hidden' : 'show') . '">
        <div class="d-flex w-100 justify-content-between">
          <h2 class="mb-1"><a href="' . $profesion_dashed . '.html">' . $nombre_profesion . '</a></h2>
          <!--small class="font-italic">
              <a href="' . $profesion_dashed . '.html">' . join('</a>, <a href="' . $profesion_dashed . '.html">', $nombres_alt) . '</a>
          </small-->
        </div>
        <h5 class="mb-1 descripcion">' . (!empty($description_info) ? createExcerpts($description_info, 100, ' [ + ]') : '<em>Sin descripción</em>') . '</h5>
      </li>
      ';
      
// TEMPLATE FOR STATIC PAGES
$html = '
<!DOCTYPE html>
<html>
  <head>
      <title>' . $nombre_profesion . ' | queserademi.com</title>
      <meta name="description" content="&#10162; Comparador | Paro | Desempleo | Tasa de empleo | Salario bruto | Sueldo mínimo | Cuanto gana | Competencias profesionales | Descargar información | Cuanto tiempo se tarda | Trabajo del futuro | Profesiones mejor pagadas | Cuales son las mejores carreras para estudiar | Qué carrera estudiar para ser <em>' . $nombre_profesion . '</em>">
      <!--Compatibilidad y móvil-->
      <meta http-equiv="Content-Language" content="es">
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="robots" content="noodp">
      <meta name="viewport" content="width=device-width, initial-scale = 1.0">
      <meta name="apple-mobile-web-app-capable" content="yes">
      <meta name="theme-color" content="#d62e46">
      <!--OGs-->
      <link rel="canonical" href="https://queserademi.com/' . $url_html . '">
      <meta property="og:locale" content="es_ES">
      <meta property="og:type" content="website">
      <meta property="og:title" content="' . $nombre_profesion . ' | queserademi">
      <meta property="og:description" content="&#10162; Comparador | Paro | Desempleo | Tasa de empleo | Salario bruto | Sueldo mínimo | Cuanto gana | Competencias profesionales | Descargar información | Cuanto tiempo se tarda | Trabajo del futuro | Profesiones mejor pagadas | Cuales son las mejores carreras para estudiar | Qué carrera estudiar para ser ' . $nombre_profesion . '">
      <meta property="og:url" content="https://queserademi.com/' . $url_html . '">
      <meta property="og:site_name" content="queserademi">
      <meta property="og:image" content="https://queserademi.com/images/logo.png">
      <!--Links css-->
      <link rel="icon" type="image/x-icon" href="../images/logo.png">
      <link rel="stylesheet" href="../css/bootstrap.min.css">
      <link rel="stylesheet" href="../css/font-awesome.css">
      <link rel="stylesheet" href="../css/style.css">
      <link rel="stylesheet" href="../css/style-comparador.css">
      <script src="../js/w3.js"></script>
    <!-- Google Tag Manager -->
    <script>
      (function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,\'script\',\'https://www.google-analytics.com/analytics.js\',\'ga\');

      ga(\'create\', \'UA-64706657-1\', \'auto\');
      ga(\'send\', \'pageview\');

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
    <!-- Facebook script -->
    <div id="fb-root"></div>
    <script>
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = \'https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v2.10\';
      fjs.parentNode.insertBefore(js, fjs);
    }(document, \'script\', \'facebook-jssdk\'));
    </script>
    <!-- End Facebook script -->

      <form id="formulario" role="form" action="../comparador.php">
          <div class="row header">

            <div class="col-md-4 ux-mobile-input-container">
              <div class="dropdown clearfix">
                <div class="input-group" id="scrollable-dropdown-menu">
                  <input name="profesion" id="buscador" class="typeahead principal center-block form-control input-lg" type="text" data-tipo="profesiones" placeholder="Busca otra profesión y compara" required value="'; $html .= $nombre_profesion . '" spellcheck="true" autocomplete="off">
                </div>
              </div>
            </div>

            <div class="col-md-4 hidden-sm hidden-xs text-center">
              <a href="https://queserademi.com">
                <h6 class="sublead qsdm-color-white">tu <strong>comparador</strong> de profesiones</h6>
                <img class="img-responsive" src="../images/logo-blanco.svg"> 
              </a>
            </div>

            <div class="col-md-4 ux-mobile-input-container">
              <div id="btnAddComparador">
                <span><i class="fa fa-plus-circle" aria-hidden="true"></i></span>
                <strong class="qsdm-color-white">Compara con otra profesión</strong>
              </div>
              <div class="dropdown clearfix" hidden>
                <div class="input-group" id="scrollable-dropdown-menu">
                  <input name="profesion_dos" id="buscador_dos" class="typeahead secundaria center-block form-control input-lg" type="text" data-tipo="profesiones" placeholder="Busca otra profesión y compara" required spellcheck="true" autocomplete="off" >
                </div>
              </div>
            </div>

          </div> 
      </form>

      <div class="col-xs-12 margen"></div>
      <div class="col-xs-12 margen"></div>
      <div class="col-xs-12 margen"></div>

      <div class="panel-group" id="accordion">
        <div class="panel panel-default">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
            <div class="panel-heading">
              <h2 class="text-center panel-title">
                  # Descubre las gráficas
              </h2>
            </div>
          </a>
          <div id="collapse1" class="panel-collapse collapse">
            <section class="panel-body">
              <article class="col-md-offset-2 col-md-5 col-sm-offset-1 col-sm-5 col-xs-12">
                Simplemente desliza tu ratón ó el dedo sobre las gráficas y encontrarás información sobre evolución del paro respecto a la media de desempleo, sueldo mínimo, medio y máximo en relación a los años de experiencia, las principales competencias profesionales y qué carrera estudiar para ser <strong>'. $profesion .'</strong>.
              </article>
              <article class="col-md-offset-0 col-md-3 col-sm-offset-0 col-sm-5 col-xs-offset-1 col-xs-10 text-right">
                <img class="img-gif" src="../video/gifts/desplegar_info.gif"/>
              </article>
            </section>
          </div>
        </div>
        <div class="panel panel-default">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
            <div class="panel-heading">
              <h2 class="text-center panel-title">
                # Compara dos profesiones
              </h2>
            </div>
          </a>
          <div id="collapse2" class="panel-collapse collapse">
            <section class="panel-body">
              <article class="col-md-offset-2 col-md-4 col-sm-offset-1 col-sm-5 col-xs-12">
                Haz click en "Compara otra profesión" e introduce la profesión que quieres comparar con <strong>'. $profesion .'</strong>. Visualizarás rapidamente las diferencias de tasa de empleo actual, el salario bruto anual, las capacidades que más se requieren y cuanto tiempo se tarda en tener una titulación de <strong>'. $profesion .'</strong> y la profesión comparada.    
                <br><br>
                <img class="img-gif" src="../video/gifts/compara_profesion.gif"/>   
              </article>
              <article class="col-md-offset-0 col-md-4 col-sm-offset-0 col-sm-5 col-xs-12 text-right">
                Además podrás descargar imagenes de las gráficas comparativas y compartir los resultados en tus redes sociales. Sólo tienes que abrir el menu de la esquina izquierda y seleccionar <a href="https://www.facebook.com/queserademicom" target="_blank" title="queserademi Facebook" onclick="window.open(\'https://www.facebook.com/queserademicom\'); return false;">facebook</a> ó <a href="https://www.linkedin.com/company/queserademi" target="_blank" title="queserademi LinkedIn" onclick="window.open(\'http://www.linkedin.com/company/queserademi\'); return false;">LinkedIn</a>. 
                <br><br>
                <img class="img-gif" src="../video/gifts/menu_compartir_rss.gif"/>
              </article>
            </section>
          </div>
        </div>
        <div class="panel panel-default">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
            <div class="panel-heading">
             <h2 class="text-center panel-title">             
                # Obtén conclusiones
             </h2>
            </div>
          </a>
          <div id="collapse3" class="panel-collapse collapse">
            <section class="panel-body">
              <article class="col-md-offset-2 col-md-4 col-sm-offset-1 col-sm-5 col-xs-12">
                La finalidad de esta herramienta es poder obtener una valoración rápida y objetiva de profesiones que puedan interesarte como trabajo del futuro ó profesiones a las que ya te dedicas. De esta forma podrás saber si <strong>'. $profesion .'</strong> está entre las profesiones mejor pagadas, qué salidas tiene y cuales son las mejores carreras para estudiar.
                <br><br>
                  <img class="img-gif" src="../video/gifts/conclusiones.gif"/> 
              </article>
              <article class="col-md-offset-0 col-md-4 col-sm-offset-0 col-sm-5 col-xs-12 text-right">
                También te invitamos a enviarnos todas tus sugerencias en 
                <br><br>
                <a class="col-xs-offset-2 col-xs-10 col-md-offset-6 btn col-md-6" style="border-color: #337ab7; color: #337ab7;" href="../quenossugieres">qué nos sugieres</a>
              </article>
            </section>
          </div>
        </div>
      </div>

      <div class="row body" style="height:120%;">
        <div class="col-md-6 col-xs-12 text-center">
          <div id="container_empleabilidad" class="grafica">
            <div class="preloader"></div>
          </div>
        </div>
        <div class="col-md-6 col-xs-12 text-center">
          <div id="container_salarios" class="grafica">
            <div class="preloader"></div>
          </div>
        </div>
        <div class="col-md-6 col-xs-12 text-center">
          <div id="container_competencias" class="grafica">
            <div class="preloader"></div>
          </div>
        </div>
        <div class="col-md-6 col-xs-12 text-center">
          <div id="container_formacion" class="grafica">
            <div class="preloader"></div>
          </div>
        </div>
        <div class="col-md-6 col-xs-12 text-center">
          <div id="container_noticias" class="grafica">
            <div class="preloader"></div>
          </div>
        </div>
        <div class="col-md-6 col-xs-12 text-center">
          <div id="container_info" class="grafica">
            <div class="preloader"></div>
          </div>
        </div>
        <!--div class="col-md-6 col-xs-12 text-center">
          <div id="container_satisfaccion" class="grafica">
            <div class="preloader"></div>
          </div>
        </div-->
      </div>  

      <div class="col-xs-12 margen"></div>

      <footer w3-include-html="../footer.html"></footer>
      <script type="text/javascript">
          w3.includeHTML();
      </script>

  <!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script type="text/javascript" src="../js/jquery-2.1.3.js"></script>
  <script type="text/javascript" src="../js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../js/typeahead.0.9.3.min.js"></script>

  <script type="text/javascript" src="../bower_components/highcharts/highcharts.js"></script>
  <script type="text/javascript" src="../bower_components/highcharts/modules/heatmap.js"></script>
  <script type="text/javascript" src="../bower_components/highcharts/modules/tilemap.js"></script>
  <script type="text/javascript" src="../bower_components/highcharts/highcharts-more.js"></script>
  <script type="text/javascript" src="../bower_components/highcharts/modules/exporting.js"></script>

  <script type="text/javascript" src="../js/scripts.js"></script>
  <script type="text/javascript" src="../js/scripts-combobox.js"></script> 
  <script type="text/javascript" src="../js/graficas.js"></script>
  <script type="text/javascript" async>
    '; 

/// SALARIOS

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
                    this.renderer.image('https://queserademi.com/images/logo.png', 15, 15, 30, 30).add();
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
                      text: '<a><i class=\"fa fa-linkedin-square fa-2x\" style=\"padding:5px\"></i>Compartir en LinkedIn</a>',
                      onclick: function(event) {
                        if (event.target.href === '') {
                              getUrlShare('linkedin', this, event.target);    
                          }
                      }
                },{
                    separator: true
                },{
                    text: '<a href=\"#\"><i class=\"glyphicon glyphicon-download-alt\" style=\"padding:5px\"></i>Descargar JPEG</a>',
                    onclick: function() {
                        this.exportChart({
                            type: 'image/jpeg',
                            filename: 'queseradermi_' + this.title.textStr + '_" . $profesion . "'
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
        }
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
            stickyTracking: !isMobile,
            events: {
                legendItemClick: function() {
                    return !isMobile; 
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
    capa_aviso += '<h3>Aún no tenemos información suficiente!</h3>';

        capa_aviso += '<p class=\"text-center\">Ayúdanos a completar información sobre <strong>salario</strong> de la profesión<br>';
        capa_aviso += '<strong>". mb_strtoupper($profesion,"UTF-8") ."</strong></p>';
        capa_aviso += '<a href=\"../colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: #d62e46; color: #d62e46;\">Colabora!</a>';

    capa_aviso += '</div></div>';

    $('#container_salarios').append(capa_aviso);";
} 

/// INFO

$script_info = "$('#container_info').html('<h5 style=\"margin:15px; font-weight: bold;\">+ INFORMACIÓN</h5><div id=\"info\"></div>');";

if( isset( $profesion ) ) {  
    $script_info .= "$('#info').append('<h4 class=\"principal nombre\">". mb_strtoupper($profesion,"UTF-8" ) ."</h4>');";
    if( empty($description_info) { 
        $script_info .= "$('#info').append('<p class=\"descripcion\" id=\"desc1\">Falta información! Ayúdanos a conseguirla.</p>');
        $('#info').append('<div class=\"col-md-8 col-md-offset-2\"><a href=\"../colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: #d62e46; color: #d62e46;\">Colabora!</a></div>');";
    } else { 
        $script_info .= "$('#info').append('<p class=\"descripcion\">". createExcerpts($description_info, 20, ' [ + ]') . "</p>');";
    } 
}

/// COMPETENCIAS

$competencias = array(
    'c_iniciativa'          => array(
                                        'name'  => 'Iniciativa y compromiso',
                                        'description' => 'Autonomía, constancia y tenacidad. Emprendimiento, automotivación y orientación al logro',
                                        'icon' => 'iniciativa',
                                        'grupo' => '',
                                        'position' => array('x' => '0', 'y' => '0')
                                    ),
    'c_resolucion'          => array(
                                        'name' => 'Resolución de problemas',
                                        'description' => 'Análisis de problemas, toma de decisiones, pensamiento analítico',
                                        'icon' => 'resolucion',
                                        'grupo' => '',
                                        'position' => array('x' => '0', 'y' => '1')
                                    ),
    'c_creatividad'         => array(
                                        'name' => 'Creatividad e innovación',
                                        'description' => 'Creatividad, innovación, originalidad',
                                        'icon' => 'creatividad',
                                        'grupo' => '',
                                        'position' => array('x' => '0', 'y' => '2')
                                    ),
    'c_planificacion'       => array(
                                        'name' => 'Planificación y estrategia',
                                        'description' => 'Organización, orientación estratégica, orientación a resultados, priorización',
                                        'icon' => 'planificacion',
                                        'grupo' => '',
                                        'position' => array('x' => '0', 'y' => '3')
                                    ),
    'c_aprendizaje'         => array(
                                        'name' => 'Facilidad de aprendizaje',
                                        'description' => 'Curiosidad, motivación autónoma, interés y rapidez para asimilar información nueva ',
                                        'icon' => 'aprendizaje',
                                        'grupo' => '',
                                        'position' => array('x' => '0', 'y' => '4')
                                    ),
    'c_comunicacion'        => array(
                                        'name' => 'Comunicación',
                                        'description' => 'Capacidad de comunicación oral y escrita',
                                        'icon' => 'comunicacion',
                                        'grupo' => '',
                                        'position' => array('x' => '1', 'y' => '1')
                                    ),
    'c_negociacion'         => array(
                                        'name' => 'Negociación',
                                        'description' => 'Capacidad comercial, persuasión, asertividad',
                                        'icon' => 'negociacion',
                                        'grupo' => '',
                                        'position' => array('x' => '1', 'y' => '2')
                                    ),
    'c_cliente'             => array(
                                        'name' => 'Orientación al cliente',
                                        'description' => 'Atención al cliente, capacidad de proveer explicaciones más y menos técnicas',
                                        'icon' => 'cliente',
                                        'grupo' => '',
                                        'position' => array('x' => '1', 'y' => '3')
                                    ),
    'c_critica'             => array(
                                        'name' => 'Pensamiento crítico',
                                        'description' => 'Capacidad de argumentar, de sintetizar, de analizar lenguaje explícito e implícito',
                                        'icon' => 'critica',
                                        'grupo' => '',
                                        'position' => array('x' => '1', 'y' => '4')
                                    ),
    'c_analisis'            => array(
                                        'name' => 'Análisis numérico',
                                        'description' => 'Razonamiento numérico, comprensión y manejo de conceptos matemático',
                                        'icon' => 'analisis',
                                        'grupo' => '',
                                        'position' => array('x' => '1', 'y' => '5')
                                    ),
    'c_calidad'             => array(
                                        'name' => 'Orientación a la calidad',
                                        'description' => 'Meticulosidad, exactitud, precisión, fiabilidad',
                                        'icon' => 'calidad',
                                        'grupo' => '',
                                        'position' => array('x' => '2', 'y' => '0')
                                    ),
    'c_espacialidad'        => array(
                                        'name' => 'Pensamiento espacial',
                                        'description' => 'Comprensión de interpretación y visualización de espacios y lugares, capacidad de orientación',
                                        'icon' => 'espacialidad',
                                        'grupo' => '',
                                        'position' => array('x' => '2', 'y' => '1')
                                    ),
    'c_coordinacion'        => array(
                                        'name' => 'Coordinación motora',
                                        'description' => 'Habilidad de movimiento y precisión corporal y manual',
                                        'icon' => 'coordinacion',
                                        'grupo' => '',
                                        'position' => array('x' => '2', 'y' => '2')
                                    ),
    'c_descubrimiento'      => array(
                                        'name' => 'Interés por el descubrimiento',
                                        'description' => 'Capacidad de investigación, pensamiento científico',
                                        'icon' => 'descubrimiento',
                                        'grupo' => '',
                                        'position' => array('x' => '2', 'y' => '3')
                                    ),
    'c_empatia'             => array(
                                        'name' => 'Empatía',
                                        'description' => 'Capacidad de tener una perspectiva distinta, de ponerse en el lugar del otro, de escucha activa',
                                        'icon' => 'empatia',
                                        'grupo' => '',
                                        'position' => array('x' => '2', 'y' => '4')
                                    ),
    'c_equipo'              => array(
                                        'name' => 'Trabajo en equipo',
                                        'description' => 'Capacidad de coordinarse con otras personas, delegar, admitir los conocimientos de los miembros del equipo',
                                        'icon' => 'equipo',
                                        'grupo' => '',
                                        'position' => array('x' => '2', 'y' => '5')
                                    ),
    'c_social'              => array(
                                        'name' => 'Habilidades sociales',
                                        'description' => 'Don de gentes, sociabilidad, networking, conocimientos interpersonales, inteligencia social',
                                        'icon' => 'social',
                                        'grupo' => '',
                                        'position' => array('x' => '3', 'y' => '0')
                                    ),
    'c_adaptabilidad'       => array(
                                        'name' => 'Adaptabilidad',
                                        'description' => 'Orientación al cambio, flexibilidad, inteligencia emocional, autorregulación',
                                        'icon' => 'adaptabilidad',
                                        'grupo' => '',
                                        'position' => array('x' => '3', 'y' => '1')
                                    ),
    'c_liderazgo'           => array(
                                        'name' => 'Liderazgo',
                                        'description' => 'Capacidad de mando y decisión, visión estratégica',
                                        'icon' => 'liderazgo',
                                        'grupo' => '',
                                        'position' => array('x' => '3', 'y' => '2')
                                    ),
    'c_integridad'          => array(
                                        'name' => 'Integridad',
                                        'description' => 'Ética, conciencia y compromiso ético',
                                        'icon' => 'integridad',
                                        'grupo' => '',
                                        'position' => array('x' => '3', 'y' => '3')
                                    ),
    'c_transmision'         => array(
                                        'name' => 'Transmisión de conocimientos',
                                        'description' => 'Capacidad e interés en formar a otros y divulgar información ',
                                        'icon' => 'transmision',
                                        'grupo' => '',
                                        'position' => array('x' => '3', 'y' => '4')
                                    ),
    'c_tecnologia'          => array(
                                        'name' => 'Habilidad Tecnológica',
                                        'description' => 'Adaptabilidad a las tecnologías, habilidades informáticas y electrónicas',
                                        'icon' => 'tecnologia',
                                        'grupo' => '',
                                        'position' => array('x' => '3', 'y' => '5')
                                    ),
    'c_sensibilidad'        => array(
                                        'name' => 'Sensibilidad',
                                        'description' => 'Habilidad para conectar con la naturaleza y otros seres vivos ó capacidad para apreciar la expresión artística',
                                        'icon' => 'sensibilidad',
                                        'grupo' => '',
                                        'position' => array('x' => '3', 'y' => '6')
                                    )
);

$btn_colabora_c_1 = 0;

foreach ($filas_competencias[0] as $fila_competencia) { 
  if (!is_numeric($fila_competencia)) {
    $btn_colabora_c_1++;
    break;
  }
}

$script_competencias = '
var seriesCompetencias = [' . getCompetenciasValues($competencias, $filas_competencias[0]) . '];
';

$script_competencias .= '
qsdmRed = \'rgba(214, 46, 70, .75)\';
qsdmBlue = \'rgba(51, 122, 183, .75)\';
qsdmPurple = \'rgba(145, 51, 183, .75)\';
qsdmGrey = \'rgba(187, 187, 187, .85)\';

var descriptions = {' . getDescriptionCompetencias($competencias) . '};'; 

$script_competencias .= '
$(\'#container_competencias\').highcharts({
    chart: {
        type: \'tilemap\',
        inverted: true,
        backgroundColor:\'rgba(255, 255, 255, 0)\',
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
        text: \'COMPETENCIAS PROFESIONALES\',
        align: "center",
        style: { 
            \'color\': \'#555\',
            \'fontSize\': \'14px\',
            \'fontWeight\': \'bold\'
        }
    },
    legend: { 
        enable: false,
        itemStyle: {
            width: \'300%\'
        },
        title: {
            text: \'<span>(Click para ver información)</span>\',
            style: {
                fontStyle: \'italic\',
                fontSize: \'9px\',
                color: \'#888\'
            }
        } 
    },
    xAxis: {
        visible: false
    },
    yAxis: {
        visible: false
    },
    colorAxis: {
        dataClasses: [{
            from: 0,
            to: 1,
            color: qsdmGrey,
            name: \'No asignado\'
        }, {
            from: 1,
            to: 2,
            color: qsdmRed,
            name: \''. $profesion .'\'
        }
        ]
    },
    exporting: {
        buttons: {
            contextButton: {
                menuItems: [{
                    text: \'<a><i class="fa fa-facebook-square fa-2x" style="padding:5px"></i>Compartir en Facebook</a>\',
                    onclick: function(event) {
                        if (event.target.href === \'\') {
                            getUrlShare(\'facebook\', this, event.target);    
                        }
                    }
                },{
                    text: \'<a><i class="fa fa-linkedin-square fa-2x" style="padding:5px"></i>Compartir en LinkedIn</a>\',
                    onclick: function(event) {
                        if (event.target.href === \'\') {
                        getUrlShare(\'linkedin\', this, event.target);    
                        }
                    }
                },{
                    separator: true
                },{
                    text: \'<a href="#"><i class="glyphicon glyphicon-download-alt" style="padding:5px"></i>Descargar JPEG</a>\',
                    onclick: function() {
                        this.exportChart({
                            type: \'image/jpeg\',
                            filename: \'queseradermi_\' + this.title.textStr + \'_' . $profesion . '\'
                        });
                    }
                }]
            },
            anotherButton: {
                text: \'???\',
                y: 28,
                x: 0,
                width: 24,
                onclick: function () {
                    // agregar capa de glosario semitransparente (con opcion a quitar)
                    var capa_glosario = \'<div class="capa-glosario">\';
                    capa_glosario += \'<div class="cerrar-glosario"><img class="icon" src="../images/cross.svg"></img></div>\';
                    capa_glosario += \'<div class="col-md-10 col-md-offset-1">\';
                   
                    capa_glosario += \'<h3>Dudas? No te preocupes, te lo aclaramos aquí!</h3><br>\';
                    capa_glosario += \'<dl>\';
                    for (name in descriptions) {
                        capa_glosario += \'<dt>\';
                        capa_glosario += \'<img height="20px" width="auto" src="../images/iconos/\' + descriptions[name].icon + \'.png">\';
                        capa_glosario += \'&nbsp;&nbsp;&nbsp;<strong>\' + name + \'</strong>\';
                        capa_glosario += \'</dt>\';
                        capa_glosario += \'<dd>\' + descriptions[name].description + \'</dd>\';
                    }
                    capa_glosario += \'</dl>\';

                    capa_glosario += \'</div>\';
                    capa_glosario += \'</div>\';

                    $(\'#container_competencias\').append(capa_glosario);

                    // cerrar glosario
                    $(\'.cerrar-glosario\').click( function() {
                        $(this).parent().remove();
                    });
                }
            }
        },
        chartOptions: {
            chart: {
                events: {
                  load: function(event) {                
                    this.renderer.image(\'https://queserademi.com/images/logo.png\', 15, 15, 30, 30).add();
                  }
                } 
            }
        }
    },
    tooltip: {
        shared: true,
        headerFormat: \'<strong style="font-size:17px">{point.key}</strong><br>\',
        formatter: function() {          
            return \'<strong style="font-size:17px;color:rgb(0,0,0);">\'+ this.key +\'</strong><br/>\'+\'<span>\'+ descriptions[this.key].description +\'</span>\';   
        },
        style: {
            display: \'block\', 
            width: \'300px\',
            whiteSpace: \'normal\'
        }    
    },
    credits: {
         enabled: false
    },
    plotOptions: {
        series: {
            dataLabels: {
                enabled: true,
                color: \'#FFFFFF\',
                useHTML: true,
                formatter: function() {
                    return \'<img class="iconos-competencias" src="../images/iconos/\' + this.point.icon + \'.png">\';
                }
            },
            tileShape: \'circle\',
            cursor: \'pointer\',
            stickyTracking: !isMobile,
            events: {
                legendItemClick: function() {
                    return !isMobile; 
                }               
            }          
        }
    },
    series: [{
        data: seriesCompetencias
    }]
});';

if ($btn_colabora_c_1 > 0) { 
    
    $script_competencias .= "
    var capa_aviso = '<div class=\"capa-aviso\">';
    capa_aviso += '<div class=\"cerrar-aviso\"><a href=\"#\"><img class=\"icon\" src=\"../images/cross.svg\"></img></a></div>';
    capa_aviso += '<div class=\"col-md-10 col-md-offset-1\">';
    capa_aviso += '<h3>Aún no tenemos información suficiente!</h3>';

        capa_aviso += '<p class=\"text-center\">Ayúdanos a completar información sobre <strong>competencias profesionales</strong> de la profesión<br>';
        capa_aviso += '<strong>". mb_strtoupper($profesion,"UTF-8") ."</strong></p>';
        capa_aviso += '<a href=\"../colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: #d62e46; color: #d62e46;\">Colabora!</a>';

    capa_aviso += '</div>';
    capa_aviso += '</div>';

    $('#container_competencias').append(capa_aviso);";
}

/// EMPLEABILIDAD 

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
                this.renderer.image('https://queserademi.com/images/logo.png', 15, 15, 30, 30).add();
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
                  text: '<a><i class=\"fa fa-linkedin-square fa-2x\" style=\"padding:5px\"></i>Compartir en LinkedIn</a>',
                  onclick: function(event) {
                    if (event.target.href === '') {
                          getUrlShare('linkedin', this, event.target);    
                      }
                  }
              },{
                  separator: true
              },{
                  text: '<a href=\"#\"><i class=\"glyphicon glyphicon-download-alt\" style=\"padding:5px\"></i>Descargar JPEG</a>',
                  onclick: function() {
                      this.exportChart({
                          type: 'image/jpeg',
                          filename: 'queseradermi_' + this.title.textStr + '_" . $profesion . "'
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
        max: 100,
        title: {
            text: 'Dificultad de conseguir trabajo %'
        },
        plotBands: [
            { // Paro alto
                from: ". $media_max .",
                to: 100,
                color: 'rgba(0, 0, 0, 0.3)',
                label: {
                    align: 'right',
                    verticalAlign: 'top',
                    textAlign: 'center',
                    x: 15,
                    y: 40,
                    text: '<i class=\"fa fa-frown-o\" aria-hidden=\"true\"></i>',
                    useHTML: true,
                    style: {
                        color: '#999',
                        fontSize:'20px',
                        zIndex: '-1'
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
                    align: 'right',
                    verticalAlign: 'top',
                    x: 15,
                    y: 20,
                    textAlign: 'center',
                    text: '<i class=\"fa fa-smile-o\" aria-hidden=\"true\"></i>',
                    useHTML: true,
                    style: {
                        color: '#999',
                        fontSize:'20px',
                        zIndex: '-1'
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
        }
    },
    credits: {
        enabled: false
    },
    plotOptions: {
        series: {
            cursor: 'pointer',
            stickyTracking: !isMobile,
            events: {
                legendItemClick: function() {
                    return !isMobile; 
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
        color: 'rgba(0, 0, 0, 0.6)',
        dashStyle: 'shortdot',
        marker: {
            fillColor: 'transparent',
            lineWidth: 1,
            lineColor: 'rgba(0, 0, 0, 0.6)',
        }
      }
    ]
};";

$script_empleabilidad .= "$('#container_empleabilidad').highcharts(chartEmpleabilidad);";

if( $btn_colabora_e_1 > 0 ) { 
    $script_empleabilidad .= "var capa_aviso = '<div class=\"capa-aviso\">';
    capa_aviso += '<div class=\"cerrar-aviso\"><a href=\"#\"><img class=\"icon\" src=\"../images/cross.svg\"></img></a></div>';
    capa_aviso += '<div class=\"col-md-10 col-md-offset-1\">';
    capa_aviso += '<h3>Aún no tenemos información suficiente!</h3>';

        capa_aviso += '<p class=\"text-center\">Ayúdanos a completar información sobre <strong>desempleo</strong> de la profesión<br>';
        capa_aviso += '<strong>". mb_strtoupper($profesion,"UTF-8") ."</strong></p>';
        capa_aviso += '<a href=\"../colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: #d62e46; color: #d62e46;\">Colabora!</a>';

    capa_aviso += '</div>';
    capa_aviso += '</div>';

    $('#container_empleabilidad').append(capa_aviso);";
} 

/// NOTICIAS

$script_noticias = "

$('#container_noticias').html('<h5 style=\"margin: 15px; font-weight: bold;\">CANAL DE NOVEDADES</h5><div id=\"noticiasContainer\"></div>');

var loaded = false;

function showNews() {
  if(loaded) return;

  $.ajax({
      url: 'https://queserademi.com/noticias/wp-json/wp/v2/posts?_embed',
      method: 'GET',
      success: function(result) {
          var title, content, src, imageSrc, imageAlt, i, posts = '';
          for (i = 0; i < result.length; i++) {
              title = result[i].title.rendered;
              content = result[i].excerpt.rendered;
              src = result[i].link;
              imageSrc = result[i]._embedded['wp:featuredmedia'][0].source_url;
              imageAlt = result[i]._embedded['wp:featuredmedia'][0].title.rendered;
              posts += '<div class=\"list-group-item col-xs-12\">';
              posts +=  '<div class=\"col-md-4 col-sm-4 col-xs-5\">';
              posts +=    '<a href=\"' + src + '\">';
              posts +=      '<img src=\"' + imageSrc + '\" alt=\"' + imageAlt + '\" class=\"img-thumbnail\">';
              posts +=    '</a>';
              posts +=  '</div>';
              posts +=  '<div class=\"col-md-8 col-sm-8 col-xs-7 text-left post-title\">';
              posts +=    '<a href=\"' + src + '\">';
              posts +=      '<strong>' + title + '</strong>';
              posts +=    '</a>';
              posts +=  '</div>';
              posts +=  '<div class=\"col-md-12 col-xs-12 text-left post-content\">';
              posts +=    '<a href=\"' + src + '\">';
              posts +=      content;
              posts +=    '</a>';
              posts +=  '</div>';
              posts += '</div>';
          }
          $('#noticiasContainer').append('<div class=\"list-group\">' + posts + '</div>');
      },
      error: function(xhr, textStatus, errorThrown) {
          console.log(xhr, textStatus, errorThrown);
          $('#noticiasContainer').append('<h2>Lo sentimos<br>No hay noticias</h2>');
      }
  });

  loaded = true;
}

showNews();

";

/// FORMACION

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
              $serie .= 'data: [' . $formac['duracion_academica'] . ', 0], ';
              $serie .= 'color: "#d62e46"';
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
          spacingBottom: 40,
          spacingTop: 20,
          spacingLeft: 40,
          spacingRight: 20,
          width: null,
          height: 380,
          events: {
            load: function() {
              if (this.series && this.series[0]) {
                var profesion = this.series[0].points[0];
                this.tooltip.refresh(profesion);
              }                      
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
          categories: ['<div style=\'text-overflow: ellipsis;width: 300px;overflow: hidden;\'>" . mb_strtoupper($profesion, 'UTF-8') . "<br><strong>Terminarías a los <strong>" . getTotalAnyosEstudios($arbol_formaciones, "duracion_academica") . " años</strong></div>', ''],
          labels: {
            x: 8,
            y: 30,
            useHTML : true,
            style: { 
                fontSize: '11px',
                maxWidth: '0px',
                'text-overflow': 'ellipsis',
                'white-space': 'normal',
                'overflow': 'visible !important'
            }
          }
      },
      yAxis: {
          min: 0,
          title: {
              text: 'Duración de estudios (años)'
          }
      },
      legend: {
          enabled: false
      },
      tooltip: {
          headerFormat: '',
          pointFormat: '<span>{series.name}</span><br><strong>Duración de estudios (años): {point.y}</strong>',
          style: {
              display: 'block', 
              width: '310px',
              whiteSpace: 'normal' ,
              fontSize: 10
          },
          positioner: function (labelWidth, labelHeight, point) {
            var tooltipX = 40;
            var tooltipY = point.plotY - 15;
            return {
                x: tooltipX,
                y: tooltipY
            };
          }
      },
      credits: {
          enabled: false
      },
      plotOptions: {
          series: {
              cursor: 'pointer',
              stacking: 'normal',
              stickyTracking: !isMobile,
              pointWidth: 30 
          }
      },
      exporting: {
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
                      text: '<a><i class=\"fa fa-linkedin-square fa-2x\" style=\"padding:5px\"></i>Compartir en LinkedIn</a>',
                      onclick: function(event) {
                        if (event.target.href === '') {
                              getUrlShare('linkedin', this, event.target);    
                          }
                      }
                  },{
                      separator: true
                  },{
                      text: '<a href=\"#\"><i class=\"glyphicon glyphicon-download-alt\" style=\"padding:5px\"></i>Descargar JPEG</a>',
                      onclick: function() {
                          this.exportChart({
                              type: 'image/jpeg',
                              filename: 'queseradermi_' + this.title.textStr + '_" . $profesion . "'
                          });
                      }
                  }]
              }
          },
          chartOptions: {
              chart: {
                  events: {
                    load: function(event) {                
                      this.renderer.image('https://queserademi.com/images/logo.png', 15, 15, 30, 30).add();
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
    capa_aviso += '<h3>Aún no tenemos información suficiente!</h3>';

        capa_aviso += '<p class=\"text-center\">Ayúdanos a completar información sobre <strong>formación</strong> de la profesión<br>';
        capa_aviso += '<strong>" . mb_strtoupper($profesion,"UTF-8") . "</strong></p>';
        capa_aviso += '<a href=\"../colabora.php?profesion=". $profesion ."\" class=\"btn btn-aviso\" style=\"border-color: #d62e46; color: #d62e46;\">Colabora!</a>';

    capa_aviso += '</div>';
    capa_aviso += '</div>';

    // debe aparecer despues de 1 segundo
    $('#container_formacion').append(capa_aviso);";
}

  // incluir scripts y cerrar html 

    $html .= $script_salarios . $script_info . $script_competencias . $script_empleabilidad . $script_noticias . $script_formacion; 
    
    $html .= '
  </script>
  </body>
</html>'; // end static page
    
        // save static page
        fwrite($pagina_html, $html);
        fclose($pagina_html);

        } // end while
        if (!$_IS_SEARCH_ALL) {
          break;
        }
      } // end if isLookingFor 
    } // end if
  } // end foreach

  $index .= '
    </ul>

    <div class="col-xs-12 margen"></div>

    <footer w3-include-html="../footer.html"></footer>
    <script type="text/javascript">
        w3.includeHTML();
    </script>

    <!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="../js/jquery-2.1.3.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>

    <script type="text/javascript" src="../js/scripts.js"></script>
    
  </body>
</html>'; // end index list page

  // guardar index
    fwrite($pagina_index, $index);
    fclose($pagina_index);

} catch( Exception $e ) {
  die('Error: '.$e->GetMessage());
}
?>

  </body>
</html>