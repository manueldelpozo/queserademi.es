<?php 
try {
  require('conexion.php');
  
  if( isset( $_GET['profesion_uno']  )  ) {
    $profesion_uno = $_GET['profesion_uno'];
    $consulta = "SELECT * FROM profesiones WHERE profesion LIKE '$profesion_uno'";
    $result = $pdo->prepare($consulta);
    $result->execute();
    $registro = $result->fetch();

  }  
  if( isset( $_GET['profesion_dos'] ) ) { 
    $profesion_dos = $_GET['profesion_dos'];
    $consulta_dos = "SELECT * FROM profesiones WHERE profesion LIKE '$profesion_dos'";
    $result_dos = $pdo->prepare($consulta_dos);
    $result_dos->execute();
    $count_dos = $result_dos->rowCount();
    $registro_dos = $result_dos->fetch();
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

      <meta prefix="og: http://ogp.me/ns#" property="og:title" content="Bienvenido a queserademi" />
      <meta prefix="og: http://ogp.me/ns#" property="og:image" content="http://www.queserademi.es/images/logo.png" />
      <meta prefix="og: http://ogp.me/ns#" property="og:url" content="http://www.queserademi.es/" />
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
      <script type="text/javascript" src="js/scripts.js" defer></script>   
      <script type="text/javascript" src="js/graficas.js" ></script>
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
    <div class="background-image"></div>
    <div class="container-full">
      <form id="formulario" role="form" action="comparador.php" method="get" onsubmit="return validacion()">

          <div class="row header">
            <div class="col-xs-12 hidden-sm hidden-md hidden-lg margen"></div>

            <div class="col-md-4">
              <div class="dropdown clearfix">
                <div class="input-group" id="scrollable-dropdown-menu" style="width: 90%;margin-bottom: 10px;">
                  <input name="profesion_uno" id="buscador" class="typeahead principal center-block form-control input-lg" type="text" placeholder="Busca otra profesión y compara" value="<?php echo @$profesion_uno; ?>" required> 
                  <span class="input-group-btn" >
                    <button class="btn btn-default btn-submit" type="submit" style="background-color: rgba(255, 255, 255, 0.6);border-color: rgb(204, 204, 204);height: 50px;position: absolute;top: 0;"><strong>&gt;</strong></button>
                  </span>
                </div>
              </div>
            </div>

            <div class="col-md-4 hidden-xs text-center">
              <a href="index.html">
                <h6 class="sublead">Tu comparador de profesiones</h6>
                <img class="img-responsive" src="images/logo.svg" height="60px"> 
              </a>
            </div>

            <div class="col-md-4">
              <div class="dropdown clearfix">
                <div class="input-group" id="scrollable-dropdown-menu" style="width: 90%;margin-bottom: 10px;">
                  <input name="profesion_dos" id="buscador_dos" class="typeahead secundaria center-block form-control input-lg" type="text" placeholder="Busca otra profesión y compara" value="<?php echo @$profesion_dos; ?>" required autofocus>
                  <span class="input-group-btn" >
                    <button class="btn btn-default btn-submit" type="submit" style="background-color: rgba(255, 255, 255, 0.6);border-color: rgb(204, 204, 204);height: 50px;position: absolute;top: 0;"><strong>&gt;</strong></button>
                  </span>
                </div>
              </div>
            </div>

          </div> 

          <div class="row body">
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container" class="grafica"></div>
            </div>
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container3" class="grafica"></div>
            </div>
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container2" class="grafica"></div>
            </div>
            <div class="col-md-6 col-xs-12 text-center">
              <div id="container1" class="grafica"></div>
            </div>
          </div> 

      </form>
    </div>

    <footer>
      <div class="row">
        <div class="col-md-12 hidden-xs hidden-sm text-center">
          <button type="button" data-toggle="dropup" aria-expanded="false" class="btn-footer" id="btn-footer-md" ><span class="caret flecha"></span></button>
            </div>
            <div class="col-xs-12 hidden-md hidden-lg">
              <div class="col-xs-3">
                <a href="index.html"> 
                  <img class="img-menu" src="images/logo.svg">        
                  </a>
              </div>
              <div class="col-xs-3 col-xs-offset-6">
            <button type="button" data-toggle="dropup" aria-expanded="false" class="btn-footer" id="btn-footer-xs" ><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span></button>
          </div>
            </div>
        <div class="col-md-2 col-md-offset-0 hidden-xs hidden-sm col-xs-6 col-xs-offset-3 text-center">
              <a href="index.html"> 
                  <p id="titulo" style='opacity:1;margin-top:-10px;'>
                    <img class="image-container" src="images/logo.svg">
                    <strong>que</strong>sera<strong>de</strong>mi
                  </p>
              </a>
            </div>
        
            <div class="col-md-2 col-xs-12 hidden-xs mobile-menu text-center">
              <a href="colabora.php">Colabora con qsdm</a>
            </div>
            <div class="col-md-2 col-xs-12 hidden-xs mobile-menu text-center">
              <a href="porquecolaborar.html">Por qué colaborar</a>
            </div>
            <div class="col-md-2 col-xs-12 hidden-xs mobile-menu text-center">
              <a href="quienessomos.html">Quiénes somos</a>
            </div>
            <div class="col-md-2 col-xs-12 hidden-xs mobile-menu text-center">
              <a href="mailto:info@queserademi.es?subject=Pregunta%20para%20queserademi&body=Hola,%0D%0A%0D%0AQuiero contactar con vosotros para..." target="_top">Contacta con nosotros</a>
            </div>
            <div class="col-md-2 col-xs-12 hidden-xs mobile-menu text-center">
              <ul class="share-buttons">
                <li><a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.queserademi.es&t=Comparador%20de%20profesiones" target="_blank" title="Share on Facebook" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL) + '&t=' + encodeURIComponent(document.URL)); return false;"><i class="fa fa-facebook-square fa-2x"></i></a></li>
                <li><a href="https://plus.google.com/share?url=http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Share on Google+" onclick="window.open('https://plus.google.com/share?url=' + encodeURIComponent(document.URL)); return false;"><i class="fa fa-google-plus-square fa-2x"></i></a></li>
                <li><a href="http://www.linkedin.com/shareArticle?mini=true&url=http%3A%2F%2Fwww.queserademi.es&title=Comparador%20de%20profesiones&summary=&source=http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Share on LinkedIn" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(document.URL) + '&title=' +  encodeURIComponent(document.title)); return false;"><i class="fa fa-linkedin-square fa-2x"></i></a></li>
                <li><a href="mailto:?subject=Comparador%20de%20profesiones&body=:%20http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Email" onclick="window.open('mailto:?subject=' + encodeURIComponent(document.title) + '&body=' +  encodeURIComponent(document.URL)); return false;"><i class="fa fa-envelope-square fa-2x"></i></a></li>
              </ul>
            </div>
            <div class="col-md-2 col-md-offset-4 col-xs-12 hidden-xs mobile-menu text-center">
              <a rel="license" href="https://creativecommons.org/licenses/by/4.0/">Terminos de uso</a>
            </div>
            <div class="col-md-2 col-xs-12 hidden-xs mobile-menu text-center">
              <small>&copy; 2015 queserademi.es</small>
            </div>
      </div>
      </footer>

  </body>

  <script type="text/javascript" async>
    <?php 
      include('js/grafica_funcion.js'); 
      include('js/grafica_info.js'); 
      include('js/grafica_barras.js'); 
      include('js/grafica_radar.js'); 
    ?>
  </script>

</html>

<?php
} catch( Exception $e ) {
  die('Error: '.$e->GetMessage());
}
?>