<?php 

if( isset( $_GET['profesion']  )  ) {
  $profesion = $_GET['profesion'];
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>queserademi.com | Colabora con queserademi</title>
    <meta name="description" content="Con dos minutos de tu tiempo construirás con nosotros un espacio donde encontrar los datos más relevantes del mundo laboral para compartir con los profesionales de hoy y del futuro. Tu colaboración anónima nos permite seguir avanzando en nuestro empeño de ofrecer toda la información analizada de forma gratuita y de fácil acceso.">
    <!--Compatibilidad y móvil-->
    <meta http-equiv="Content-Language" content="es">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noodp">
    <meta name="viewport" content="width=device-width, initial-scale = 1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#d62e46">
    <!--OGs-->
    <link rel="canonical" href="https://queserademi.com/colabora.php">
    <meta property="og:locale" content="es_ES">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Orientación Laboral y Comparador de Profesiones | queserademi">
    <meta property="og:description" content="Con dos minutos de tu tiempo construirás con nosotros un espacio donde encontrar los datos más relevantes del mundo laboral para compartir con los profesionales de hoy y del futuro. Tu colaboración anónima nos permite seguir avanzando en nuestro empeño de ofrecer toda la información analizada de forma gratuita y de fácil acceso.">
    <meta property="og:url" content="https://queserademi.com/colabora.php">
    <meta property="og:site_name" content="queserademi">
    <meta property="og:image" content="https://queserademi.com/images/logo.png">
    <!--Links css-->
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css">
    <link rel="stylesheet" href="js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style-colabora.css">
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
    <header class="not-scroll-hidden" w3-include-html="header.html"></header>
    <div data-role="page">
      
    <div data-role="main" class="container-full ui-content">
      <form id="formulario-colabora" class="form-horizontal" role="form" action="verificador.php" method="post" onsubmit="return validacion()">

          <div class="row header">

            <div class="col-md-4 col-md-offset-1 col-xs-11 col-xs-offset-1">  
              <h4>colabora con queserademi, <br>de forma anónima y <br><strong>en sólo 2 minutos...</strong></h4>  
            </div>

            <div class="col-md-2 hidden-sm hidden-xs text-center">
              <a data-role="none" href="https://queserademi.com">
                <img class="img-responsive" src="images/logo-blanco.svg"> 
              </a>
            </div>

            <div class="col-md-4 hidden-sm hidden-xs" align="right">
              <h4><a data-role="none" href="porquecolaborar.html">por qué es importante <br><strong>colaborar</strong>?</a></h4>
            </div>

          </div> 

          <div class="row body">

            <div class="col-md-6 col-md-offset-3 col-xs-12">
              
                <div class="group-1 form-group dropdown clearfix profesion required">
                  <label for="profesion" class="col-sm-3 control-label"><strong>profesión</strong>:</label>
                  <div class="col-sm-9">  
                    <div class="input-group" style="width: 100%;">
                      <input name="profesion" id="profesion" class="typeahead center-block form-control input-lg" type="search" data-tipo="profesiones" data-role="none" data-enhance="false" placeholder="busca una profesión (ej. Electricista)" data-clear-btn="true" value="<?php echo @$profesion; ?>" autofocus required spellcheck="true" autocomplete="off">
                    </div>
                  </div>
                </div>
                
                <div class="group-1 form-group">
                  <label for="descripcion" class="col-sm-3 control-label"><strong>descripción</strong>: (opcional)</label>
                  <div class="col-sm-9">
                    <textarea name="descripcion" id="descripcion" class="normal-input center-block form-control input-lg" rows="5" placeholder="escribe una breve descripción de la profesión "></textarea>
                  </div>
                </div>

                <div class="group-1 form-group dropdown clearfix estudios">
                  <label for="estudios_asoc" class="col-sm-3 control-label">los últimos <strong>estudios</strong> realizados:</label>
                  <div class="col-sm-9">
                    <div class="input-group" style="width: 100%;">
                      <input name="estudios_asoc" type="search" id="estudios_asoc" class="typeahead center-block form-control input-lg" data-tipo="formaciones" data-role="none" data-enhance="false" placeholder="busca una formación (ej. Master...)" data-clear-btn="true" required spellcheck="true" autocomplete="off">           
                    </div>
                  </div>
                </div>

                <div class="group-1 form-group">
                  <label for="tiempo-estudios" class="col-sm-3 control-label">y cuantos <strong>años (reales)</strong> duraron esos estudios:</label>
                  <div class="col-md-9 col-xs-12">
                    <input type="range" name="tiempo_estudios" id="tiempo-estudios" value="5" min="1" max="15" data-highlight="true" data-popup-enabled="true">                 
                  </div>
                </div>

                <div class="group-1 form-group">
                  <label for="trabajas" class="col-sm-3 control-label">trabajando actualmente?:</label>
                  <div class="col-md-9 col-xs-12">
                     <input type="checkbox" data-role="flipswitch" name="trabajas" id="trabajas" data-on-text="Si" data-off-text="No">                  
                  </div>
                </div>      

                <div class="group-1 form-group">
                  <label for="comunidad-autonoma" class="col-sm-3 control-label">donde?:</label>
                  <div class="col-md-9 col-xs-12">
                    <fieldset class="ui-field-contain">
                      <select name="comunidad_autonoma" id="comunidad-autonoma" data-native-menu="false">
                        <option value="">selecciona tu región</option>
                        <option value="and">Andalucía</option>
                        <option value="ara">Aragón</option>
                        <option value="ast">Principado de Asturias</option>
                        <option value="bal">Baleares</option>
                        <option value="can">Canarias</option>
                        <option value="cat">Cantabria</option>
                        <option value="man">Castilla-La Mancha</option>
                        <option value="leo">Castilla y León</option>
                        <option value="cat">Cataluña</option>
                        <option value="cym">Ceuta y Melilla</option>
                        <option value="ext">Extremadura</option>
                        <option value="gal">Galicia</option>
                        <option value="rio">La Rioja</option>
                        <option value="mad">Comunidad de Madrid</option>
                        <option value="mur">Región de Murcia</option>
                        <option value="nav">Comunidad Foral de Navarra</option>
                        <option value="vas">País Vasco</option>
                        <option value="val">Comunidad Valenciana</option>
                        <option value="for">En el extranjero</option>
                        <option><hr></option>
                      </select>
                    </fieldset>                 
                  </div>
                </div>

                <div class="group-1 form-group">
                  <label for="acceso" class="col-sm-3 control-label">como fue el <strong>acceso</strong> al puesto de trabajo:</label>
                  <div class="col-md-9 col-xs-12">
                    <fieldset class="con-otro" data-role="controlgroup" data-type="horizontal">
                      <label for="entrevista">Entrevista</label>
                      <input type="radio" name="acceso" id="entrevista" value="entrevista" checked="checked">
                      <label for="oposiciones">Oposiciones</label>
                      <input type="radio" name="acceso" id="oposiciones" value="oposiciones">
                      <label for="otro-acceso" class="otro-label">Otro</label>
                      <input type="radio" class="otro" name="acceso" id="otro-acceso" value="otro">
                    </fieldset>                  
                  </div>
                </div>

                <div class="group-1 form-group">
                  <label for="sector" class="col-sm-3 control-label">el tipo de <strong>sector</strong>:</label>
                  <div class="col-md-9 col-xs-12">
                    <fieldset data-role="controlgroup" data-type="horizontal">
                      <label for="publico">Publico</label>
                      <input type="radio" name="sector" id="publico" value="publico">
                      <label for="privado">Privado</label>
                      <input type="radio" name="sector" id="privado" value="privado" checked="checked">
                    </fieldset>                  
                  </div>
                </div>

                <div class="group-1 form-group">
                  <label for="contrato" class="col-sm-3 control-label">el tipo de <strong>contrato</strong> conseguido:</label>
                  <div class="col-md-9 col-xs-12">
                    <fieldset class="con-otro" data-role="controlgroup" data-type="horizontal">
                      <label for="indefinido">Indefinido</label>
                      <input type="radio" name="contrato" id="indefinido" value="indefinido" checked="checked">
                      <label for="temporal">Temporal</label>
                      <input type="radio" name="contrato" id="temporal" value="temporal">
                      <label for="practicas">Practicas</label>
                      <input type="radio" name="contrato" id="practicas" value="practicas">
                      <label for="otro-contrato" class="otro-label">Otro</label>
                      <input type="radio" class="otro" name="contrato" id="otro-contrato" value="otro">
                    </fieldset>                  
                  </div>
                </div>

                <div class="group-1 form-group">
                  <label for="jornada-laboral" class="col-sm-3 control-label">tu <strong>jornada laboral</strong> (Ejemplo: 9h - 18h): </label>
                  <div class="col-md-9 col-xs-12 " data-role="rangeslider">
                    <input type="range" name="jornada_laboral_min" id="jornada-laboral-min" value="9" min="0" max="23" step="1" data-highlight="true" data-popup-enabled="true">
                    <input type="range" name="jornada_laboral_max" id="jornada-laboral-max" value="18" min="0" max="23" step="1" data-highlight="true" data-popup-enabled="true">                                  
                  </div>
                </div>

                <div class="group-1 form-group">
                  <label for="movilidad" class="col-sm-3 control-label">requiere <strong>movilidad</strong> al extranjero?:</label>
                  <div class="col-md-9 col-xs-12">
                     <input type="checkbox" data-role="flipswitch" name="movilidad" id="movilidad" data-on-text="Si" data-off-text="No">                  
                  </div>
                </div> 

                <div class="group-1 form-group">
                  <label for="horas-semana" class="col-sm-3 control-label">las <strong>horas semanales</strong> totales de trabajo:</label>
                  <div class="col-md-9 col-xs-12">
                    <input type="range" name="horas_semana" id="horas-semana" value="40" min="10" max="70" step="1" data-highlight="true" data-popup-enabled="true">                 
                  </div>
                </div>
                <div class="group-1 form-group">
                  <label for="horas-real" class="col-sm-3 control-label">y las <strong>horas reales</strong> dedicadas:</label>
                  <div class="col-md-9 col-xs-12">
                    <input type="range" name="horas_real" id="horas-real" value="30" min="10" max="70" step="1" data-highlight="true" data-popup-enabled="true">                 
                  </div>
                </div>

                <div class="group-1 form-group">
                  <label for="puesto" class="col-sm-3 control-label"><strong>Estatus</strong> dentro de la empresa:</label>
                  <div class="col-md-9 col-xs-12">
                    <fieldset class="con-otro" data-role="controlgroup" data-type="horizontal">
                      <label for="director">Director</label>
                      <input type="radio" name="puesto" id="director" value="director">
                      <label for="jefe">Jefe de equipo</label>
                      <input type="radio" name="puesto" id="jefe" value="jefe">
                      <label for="empleado">Empleado</label>
                      <input type="radio" name="puesto" id="empleado" value="empleado" checked="checked">
                      <label for="otro-puesto" class="otro-label">Otro</label>
                      <input type="radio" class="otro" name="puesto" id="otro-puesto" value="otro">
                    </fieldset>                  
                  </div>
                </div>

                <div class="group-1 form-group">
                  <label for="edad-jubilacion" class="col-sm-3 control-label">la <strong>edad de jubilación</strong> estimada:</label>
                  <div class="col-md-9 col-xs-12">
                    <input type="range" name="edad_jubilacion" id="edad-jubilacion" value="60" min="50" max="70" step="1" data-popup-enabled="true">                 
                  </div>
                </div>

                <div class="group-1 form-group">
                  <label for="tiempo-trabajo" class="col-sm-3 control-label">los <strong>años trabajados</strong> en esta profesión (experiencia):</label>
                  <div class="col-md-9 col-xs-12">
                    <input type="range" name="tiempo_trabajo" id="tiempo-trabajo" value="6" min="0" max="40" data-highlight="true" data-popup-enabled="true">                 
                  </div>
                </div>

                <div class="group-1 form-group">
                  <label for="s_general_anual" class="col-sm-3 control-label">tu <strong>salario</strong> actual<br>(elige anual o mensual):</label>
                  <div class="col-md-9 col-xs-12">
                    <input type="range" name="s_general_anual" id="s_general_anual" value="18000" min="7200" max="96000" step="100" data-highlight="true" data-popup-enabled="true">
                    <fieldset data-role="controlgroup" data-type="horizontal">
                      <label for="anual">€ Bruto Anual</label>
                      <input type="radio" name="s_modo" id="anual" value="anual" checked="checked">
                      <label for="mensual">€ Neto Mensual</label>
                      <input type="radio" name="s_modo" id="mensual" value="mensual">
                    </fieldset>                  
                  </div>
                </div>

                <div id="btnAddGrupoSalario" class="col-md-12 col-xs-12">
                    <span><i class="fa fa-plus-circle" aria-hidden="true"></i></span>
                    <strong>&nbsp;podrías darnos más información sobre tu salario?</strong>
                </div>

                <div class="group-salario col-md-12 col-xs-12 text-center titulo1">
                  <h5>selecciona el <strong>rango salarial</strong> aproximado según experiencia en esta profesión (elige anual o mensual)</h5>
                </div>
                <div class="group-salario form-group">
                  <label for="s_principiante" class="col-sm-3 control-label">[menos de 5 años]: </label>
                  <div class="col-md-9 col-xs-12 " data-role="rangeslider">
                    <input type="range" class='s_principiante' name="s_principiante_min" id="s_principiante_min" value="6000" min="2400" max="120000" step="100" data-highlight="true" data-popup-enabled="true">
                    <input type="range" class='s_principiante' name="s_principiante_max" id="s_principiante_max" value="24000" min="2400" max="120000" step="100" data-highlight="true" data-popup-enabled="true">                                
                  </div>
                </div>
                <div class="group-salario form-group">
                  <label for="s_junior" class="col-sm-3 control-label">[de 5 a 10 años]: </label>
                  <div class="col-md-9 col-xs-12 " data-role="rangeslider">
                    <input type="range" class='s_junior' name="s_junior_min" id="s_junior_min" value="12000" min="2400" max="120000" step="100" data-highlight="true" data-popup-enabled="true">
                    <input type="range" class='s_junior' name="s_junior_max" id="s_junior_max" value="36000" min="2400" max="120000" step="100" data-highlight="true" data-popup-enabled="true">                                
                  </div>
                </div>
                <div class="group-salario form-group">
                  <label for="s_intermedio" class="col-sm-3 control-label">[de 10 a 15 años]: </label>
                  <div class="col-md-9 col-xs-12 " data-role="rangeslider">
                    <input type="range" class='s_intermedio' name="s_intermedio_min" id="s_intermedio_min" value="18000" min="2400" max="120000" step="100" data-highlight="true" data-popup-enabled="true">
                    <input type="range" class='s_intermedio' name="s_intermedio_max" id="s_intermedio_max" value="42000" min="2400" max="120000" step="100" data-highlight="true" data-popup-enabled="true">                                  
                  </div>
                </div>
                <div class="group-salario form-group">
                  <label for="s_senior" class="col-sm-3 control-label">[más de 15 años]: </label>
                  <div class="col-md-9 col-xs-12 " data-role="rangeslider">
                    <input type="range" class='s_senior' name="s_senior_min" id="s_senior_min" value="24000" min="2400" max="120000" step="100" data-highlight="true" data-popup-enabled="true">
                    <input type="range" class='s_senior' name="s_senior_max" id="s_senior_max" value="48000" min="2400" max="120000" step="100" data-highlight="true" data-popup-enabled="true">                           
                  </div>
                </div>

                <div class="group-1 col-md-12 col-xs-12 text-center titulo1">
                  <h5>pon nota a las <strong>capacidades</strong> que se necesitan para esta profesión [1-5]</h5>
                </div>
                <div class="group-1 col-md-4 col-xs-12 text-center">
                  <div class="form-group required">
                    <label for="c_equipo" class="titulo2">cooperación:</label>
                    <input type="range" name="c_equipo" id="c_equipo" value="3" min="1" max="5" step="1" data-popup-enabled="true" required>
                  </div>
                </div>
                <div class="group-1 col-md-4 col-xs-12 text-center">
                  <div class="form-group required">
                    <label for="c_analisis" class="titulo2">análisis:</label>
                    <input type="range" name="c_analisis" id="c_analisis" value="3" min="1" max="5" step="1" data-popup-enabled="true" required>
                  </div>
                </div>
                <div class="group-1 col-md-4 col-xs-12 text-center">
                  <div class="form-group required">
                    <label for="c_objetivos" class="titulo2">logro de objetivos:</label>
                    <input type="range" name="c_organizacion" id="c_organizacion" value="3" min="1" max="5" step="1" data-popup-enabled="true" required>
                  </div>
                </div>
                <div class="group-1 col-md-4 col-xs-12 text-center">
                  <div class="form-group required">
                    <label for="c_comunicacion" class="titulo2">comunicación:</label>
                    <input type="range" name="c_comunicacion" id="c_comunicacion" value="3" min="1" max="5" step="1" data-popup-enabled="true" required>
                  </div>
                </div>
                <div class="group-1 col-md-4 col-xs-12 text-center">
                  <div class="form-group required">
                    <label for="c_forma_fisica" class="titulo2">destreza y físico:</label>
                    <input type="range" name="c_forma_fisica" id="c_forma_fisica" value="3" min="1" max="5" step="1" data-popup-enabled="true" required>
                  </div>
                </div>
                <div class="group-1 col-md-4 col-xs-12 text-center">
                  <div class="form-group required">
                    <label for="c_persuasion" class="titulo2">persuasión:</label>
                    <input type="range" name="c_forma_fisica" id="c_forma_fisica" value="3" min="1" max="5" step="1" data-popup-enabled="true" required>
                  </div>
                </div>

                <div class="group-1 col-md-12 col-xs-12 text-center titulo1">
                  <h5>y de nivel de <strong>idiomas</strong>, que se necesitaría? [1-5]</h5>
                </div>
                <div class="group-1 col-md-6 col-xs-12 text-center">
                  <div class="form-group required">
                    <label for="i_ingles" class="titulo2">inglés:</label>
                    <input type="range" name="i_ingles" id="i_ingles" value="3" min="1" max="5" step="1" data-popup-enabled="true" required>
                  </div>
                </div>
                <div class="group-1 col-md-6 col-xs-12 text-center i-bloqueado">
                  <div class="form-group">
                    <label for="i_frances" class="titulo2">francés:</label>
                    <input type="range" name="i_frances" id="i_frances" value="1" min="1" max="5" step="1" data-popup-enabled="true" required>
                  </div>
                </div>
                <div class="group-1 col-md-6 col-xs-12 text-center i-bloqueado">
                  <div class="form-group">
                    <label for="i_aleman" class="titulo2">alemán:</label>
                    <input type="range" name="i_aleman" id="i_aleman" value="1" min="1" max="5" step="1" data-popup-enabled="true" required>
                  </div>
                </div>
                <div class="group-1 col-md-6 col-xs-12 text-center i-bloqueado">
                  <div class="form-group">
                    <label for="i_otro" class="titulo2" style="display:inline-flex;">otro:
                      <input type="text" data-role='none' data-enhance="false" name="i_otro_val" style="height: 30px;width: 100px;margin-left: 20px;margin-top: -10px; border: solid 2px darkgrey;">
                    </label>
                    <input type="range" name="i_otro" id="i_otro" value="1" min="1" max="5" step="1" data-popup-enabled="true" required>
                  </div>
                </div>

                <div class="group-1 col-md-12 col-xs-12 text-center titulo1">
                  <h5>por último, puntúa tu <strong>satisfacción</strong> sobre esta profesión</h5>
                </div>
                <div class="group-1 col-md-12 col-xs-12 text-center stars">
                  <div class="form-group"> 
                    <fieldset data-role="controlgroup" data-type="horizontal">
                      <label for="star-1">&#9733;</label>
                      <input type="checkbox" name="stars[]" id="star-1" value="1">
                      <label for="star-2">&#9733;</label>
                      <input type="checkbox" name="stars[]" id="star-2" value="2">
                      <label for="star-3">&#9733;</label>
                      <input type="checkbox" name="stars[]" id="star-3" value="3">
                      <label for="star-4">&#9733;</label>
                      <input type="checkbox" name="stars[]" id="star-4" value="4">
                      <label for="star-5">&#9733;</label>
                      <input type="checkbox" name="stars[]" id="star-5" value="5">
                    </fieldset>
                  </div>
                </div>

                <div class="group-1 form-group">
                  <label for="colaborador" class="col-sm-3 control-label" style="display: inline-block;
""><strong>nombre</strong>: (opcional y anónimo)</label>
                  <div class="col-sm-9">                 
                    <input name="colaborador" type="text" id="colaborador" class="normal-input center-block form-control input-lg" placeholder="tu nombre completo" data-clear-btn="true"/>
                  </div>
                </div>

                <div class="group-1 form-group">
                  <label for="email" class="col-sm-3 control-label"><strong>email</strong>: (opcional y anónimo)</label>
                  <div class="col-sm-9">
                    <input name="email" type="email" id="email" class="normal-input center-block form-control input-lg" placeholder="tu dirección email" data-clear-btn="true"/>
                  </div>
                </div>

                <div class="group-1 col-md-6 col-xs-12 inputs-ocultos">
                  <div class="form-group">
                    <label for="verificacion">¡Si ves esto, no rellenes el siguiente campo!</label>
                    <input type="text" name="verificacion" id="verif" />
                  </div>
                </div>

                <div class="group-1 col-md-6 col-xs-12 inputs-ocultos">
                  <div class="form-group">
                    <label for="codigo-gen">¡Si ves esto, no rellenes el siguiente campo!</label>
                    <input type="text" name="codigo_gen" id="codigo-gen" />
                  </div>
                </div>

                <div class="group-1 col-md-12 col-xs-12 text-center">
                  <div class="form-group"> 
                    <button type="submit" class="btn btn-default btn-qsdm" data-role='none' data-enhance="false">COLABORA!</button>
                  </div>
                </div>
             
            </div>
    
          </div> 

      </form>

      <div class="col-xs-12 margen"></div>

    </div>
    </div>
    <div id="progressBarContainer">
      <div class="col-md-6 col-md-offset-3 col-xs-12">
        <input type="range" name="progreso" id="progreso" step="1" min="0" data-highlight="true" data-theme="b" data-track-theme="d">
        <div class="text-center">Animo! Has completado el <strong id=progressBarPercentage></strong> del formulario!</div>
      </div>
    </div>
    <footer w3-include-html="footer.html"></footer>
      <script type="text/javascript">
          w3.includeHTML();
      </script>
      <!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
      <script type="text/javascript" src="js/jquery-2.1.3.js" ></script>
      <script>
        $(document).on('mobileinit', function () {
            $.mobile.ignoreContentEnabled = true;
            $.mobile.ajaxEnabled = false; // no jqm for links
        });
      </script>
      <script type="text/javascript" src="js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.js"></script>
      <script type="text/javascript" src="js/bootstrap.min.js"></script>
      <script type="text/javascript" src="js/typeahead.0.9.3.min.js"></script>
      <script type="text/javascript" src="js/scripts.js"></script>   
      <script type="text/javascript" src="js/scripts-combobox.js"></script>
      <script type="text/javascript">
        $(document).ready(function() {
          //forzar autofocus
          $('input[name=profesion]').focus();

          // setting default styles
          $('.verif').parent().css('visibility','hidden');
          $('.ui-input-text').removeClass('ui-corner-all ui-shadow-inset');
          $('.typeahead').css('background-color','#FFF');

          // incializar barra de progreso
          var $numTotalFormGroup = $('.form-group').length;
          $('#progreso').attr('max', $numTotalFormGroup)
          .slider().val(1)
          .slider('option', { disabled: true }).slider('refresh');

          function increaseProgressBar() {
            var $newVal = Number($('#progreso').slider().val()) + 1;
            var $newPercentage = Math.round($newVal / $numTotalFormGroup * 100);
            $('#progreso').slider().val($newVal).slider('refresh');
            $('#progressBarPercentage').text($newPercentage + '%');
            // Si supera un tercio cambiar al azul
            if ($newVal > $numTotalFormGroup / 3) {
              $('#progreso').next().find('.ui-slider-bg').css('background-color', '#3388cc');
            }
          }

          increaseProgressBar();

          //captar inputs cambiados
          var inputsYaModificados = {};
          $('.form-group input').bind('change', function(event, ui) {
            var inputModificado = event.target;
            if (!inputsYaModificados.hasOwnProperty(inputModificado.name)) {
              inputsYaModificados[inputModificado.name] = inputModificado;
              increaseProgressBar();
            }
          });

          //asegurar que se chequea el valor
          $('.ui-radio input').on('touchstart click', function () { 
            $(this).prop('checked',true).val($(this).prop('id')); 
            $(this).parent().siblings('.ui-radio').children('input').prop('checked',false); ;
            $(this).checkboxradio('refresh'); 
          });
  
          $('#formulario-colabora').on( 'touchstart click', function(e){
            var $fieldset = $(e.target).parents('fieldset.con-otro');
            var $otro_label = $fieldset.children().find('.otro-label');
            
            if($fieldset.length > 0 && $(e.target).hasClass('otro-label') ) {
              var tema = $otro_label.next().attr('name');
              $otro_label.parent('.ui-radio').replaceWith('<input type="text" class="otro-input" id="'+tema+'" placeholder="escribe otro valor">'); 
              $(this).children().find('.otro-input').focus();
              return false;             
            } else {
              var $otro_input = $('.otro-input');
              var tema = $otro_input.attr('id');
              var valor = $otro_input.val();
              if( !valor ) 
                valor = 'Otro';
              $otro_input.replaceWith('<div class="ui-radio"><label for="'+tema+'" class="otro-label ui-btn ui-corner-all ui-btn-inherit ui-btn-active ui-radio-on ui-last-child">'+valor+'</label><input type="radio" class="otro" name="'+tema+'" id="'+valor+'" value="'+valor+'" data-cacheval="true"></div>');
              if(valor!='Otro') {
                $('#'+valor).prop('checked', true);
                $('#'+valor).parent().siblings().children('input').prop('checked', false).checkboxradio("refresh");         
              }
            }
            $fieldset.children("input[type='radio']").checkboxradio("refresh");
          });

          // cambio de modo de salario: anual o mensual
          var salario_values = {}, salario_inputs = ['s_general_anual', 's_principiante_min', 's_principiante_max', 's_junior_min', 's_junior_max', 's_intermedio_min', 's_intermedio_max', 's_senior_min', 's_senior_max'];

          for (var i = 0; i < salario_inputs.length; i++) {
            var $salario_input = $("input[name=" + salario_inputs[i] + "]");
            salario_values[salario_inputs[i]] = {
              'min_anual': $salario_input.attr('min'),
              'val_anual': $salario_input.val(),
              'max_anual': $salario_input.attr('max')
            }
          }

          function setSalariosInputValues(divisor) {
            for (var i = 0; i < salario_inputs.length; i++) {
              var $salario_input = $("input[name=" + salario_inputs[i] + "]");
              $salario_input.prop({
                min: salario_values[salario_inputs[i]].min_anual / divisor,
                max: salario_values[salario_inputs[i]].max_anual / divisor
              }).val(salario_values[salario_inputs[i]].val_anual / divisor);
            }
          }

          $("input[name='s_modo']").bind('change', function(event, ui) {
            var divisor = ($(event.target).val() === 'mensual') ? 12 : 1;
            setSalariosInputValues(divisor);
            $('.s_senior, .s_intermedio, .s_junior, .s_principiante, #s_general_anual').slider('refresh');
          });

          // campos bloqueados por defecto
          $('.s_senior, .s_intermedio, #i_frances, #i_aleman, #i_otro').slider( "disable" );

          // mostrar rangos de salario 
          $('#btnAddGrupoSalario').on('touchstart click', function () {
            $(this).focus();
            $(this).hide();
            $('.group-salario').each(function () {        
              $(this).show();
            });
          });

          // desbloquear rangos de salario segun el tiempo de trabajo
          $('#tiempo-trabajo').slider({
            stop: function( event, ui ) {
              var tiempo_trabajo = $(this).slider().val();
              if( tiempo_trabajo < 5 ) {
                $( '.s_junior, .s_senior, .s_intermedio' ).slider( "disable" );
              } else if( tiempo_trabajo >= 5 && tiempo_trabajo < 10 ) {
                $( '.s_junior' ).slider( "enable" );
                $( '.s_senior, .s_intermedio' ).slider( "disable" );
              } else if( tiempo_trabajo >= 10 && tiempo_trabajo < 15 ) {
                $( '.s_junior, .s_intermedio' ).slider( "enable" );
                $( '.s_senior' ).slider( "disable" );
              } else if( tiempo_trabajo >= 15 ) {
                $( '.s_senior, .s_intermedio' ).slider( "enable" );
              }
            }
          });
          // desbloquear rangos de idiomas si click on them
          $( '.i-bloqueado' ).on("touchstart click",function(e){
            $(this).find("input[type='number']").slider( "enable" );
          });

          // star rating grado de satisfaccion
          $('.stars').on("touchstart click",function(e){
            var $checked_star = e.target;
            var $stars = $(this).find('.ui-checkbox label');
            var $stars_input = $(this).find('.ui-checkbox input');
            var checked_index = $stars.length;
            $stars.addClass('ui-checkbox-on ui-btn-active').removeClass('ui-checkbox-off');
            $stars_input.prop('checked', true);
            $stars.each( function(index,$star){
              if ( $star == $checked_star )
                checked_index = index;
              if ( index>=checked_index ) {
                $stars.eq(index).removeClass('ui-checkbox-on ui-btn-active').addClass('ui-checkbox-off');
                $stars_input.eq(index).prop( "checked", false );
              }
            });    
            $stars_input.checkboxradio("refresh");
          });

          // generar codigo aleatorio e introducirlo en el input oculto
          $('#codigo-gen').val( generateKey() );

          // ocultar footer si hacemos scroll hasta el fondo
          $(window).on('scrollstop', function() {
            var isScrollBottom = $(window).scrollTop() == $(document).height() - $(window).height();
            if (isScrollBottom) {
              $('footer').slideUp(500);
              $('#progressBarContainer').animate({
                bottom: 0,
              }, 500);
            }
            else {
              $('footer').slideDown(500);
              $('#progressBarContainer').animate({
                bottom: '50px',
              }, 500);
            }    
          });

          // ocultar progress bar cuando input en los typeahead SOLO EN MOBILES
          $('input.typeahead').focus(function() {
            $('#progressBarContainer').addClass('hidden-xs');
            $('footer').addClass('hidden-xs');
          }).blur(function() {
            $('#progressBarContainer').removeClass('hidden-xs');
            $('footer').removeClass('hidden-xs');
          });

          // ocultar progress bar cuando el movil esta en landscape
          $(window).on("orientationchange", function(event) {
            var orientation = event.orientation;
            if (orientation === 'portrait') {
              $('#progressBarContainer').removeClass('hidden-xs');
            } else {
              $('#progressBarContainer').addClass('hidden-xs');
            }
          });

          // lanzar ajax cada 5 segundos
          /*
          $.ajax({
            url: 'verificador.php',
            type: 'POST',
            data: { keyword: keyword, estudios_asoc: estudios_asoc }, 
            success: function( msg ) { desplegarLista( $input, msg ) } 
          });  */
          var actualizador = setInterval( function() {
            $.post('verificador.php', $('#formulario-colabora').serialize());
          }, 5000);

          /*$('#formulario-colabora').submit( function() {
            clear(actualizador)
          });*/

          /*$('input#estudios_asoc').on('change', function() {
            var query = $(this).slider().val();
            console.log(query);

            $.get( 'ajax.php', { tipo: 'formaciones', query: query } )
            .done(function( data ) {
              alert( "Data Loaded: " + data );
            });
          });*/

        });
      </script>
  </body>

</html>

