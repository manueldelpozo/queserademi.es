
<!DOCTYPE html>
<html>
  <head>
      <meta http-equiv="Content-Language" content="es">
      <meta charset="utf-8">
      <title>Comparador de Profesiones</title>
      <meta name="description" content="Colabora con queserademi">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta prefix="og: http://ogp.me/ns#" property="og:title" content="Colabora con queserademi" />
      <meta prefix="og: http://ogp.me/ns#" property="og:image" content="http://www.queserademi.es/images/logo.png" />
      <meta prefix="og: http://ogp.me/ns#" property="og:url" content="http://www.queserademi.es/" />
      <link rel="icon" type="image/x-icon" href="images/logo.png">
      <link rel="stylesheet" href="css/bootstrap.min.css" />
      <link rel="stylesheet" type="text/css" href="css/slider.css">
      <link rel="stylesheet" href="css/style.css" />
      <link rel="stylesheet" href="css/style-comparador.css" />
      <!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
      <script type="text/javascript" src="js/jquery-2.1.3.js" ></script>
      <script type="text/javascript" src="js/bootstrap.min.js" ></script>
      <script type="text/javascript" src="js/bootstrap-slider.js" ></script>
      <script type="text/javascript" src="js/scripts.js" defer></script>   
      
      <style type="text/css" media="screen">
        #ex1Slider .slider-selection {
          background: #BABABA;
        }  
      </style>
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
        (function(w,d,s,l,i){
          w[l]=w[l]||[];
          w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});
          var f=d.getElementsByTagName(s)[0], j=d.createElement(s), dl=l!='dataLayer'?'&l='+l:'';
          j.async=true;
          j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;
          f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-5MQKZX');
    </script>
    <script type="text/javascript">
      $('#ex1').slider({
        alert("funciona");
        formatter: function(value) {
          return 'Current value: ' + value;
        }
      });
    </script>
    <!-- End Google Tag Manager -->
    <div class="container-full">
      <form id="formulario" role="form" action="comparador.php" method="get" onsubmit="return validacion()">

          <div class="row header">

            <div class="col-md-4 col-xs-12 text-center">
              
            </div>

            <div class="col-md-4 hidden-xs text-center">
              <a href="index.html">
                <h6 class="sublead">Tu comparador de profesiones</h6>
                <img class="img-responsive" src="images/logo.svg" height="60px"> 
              </a>
            </div>

            <div class="col-md-4 text-center">
      
            </div>

          </div> 

          <div class="row body">

            <div class="col-md-6 col-xs-12 text-center">
              <div class="col-md-8 col-md-offset-2 col-xs-12">
                <input name="nombre" type="text" id="nombre" class="typeahead principal center-block form-control input-lg" autocomplete="off" placeholder="Tu nombre completo" value="<?php //echo @$profesion_uno; ?>" autofocus>
                <input name="email" type="email" id="email" class="typeahead principal center-block form-control input-lg" autocomplete="off" placeholder="Tu direccion email" value="<?php //echo @$profesion_uno; ?>">
                <div class="dropdown clearfix profesion">
                    <div class="input-group">
                      <input name="profesion" type="text" id="profesion" class="typeahead principal center-block form-control input-lg" autocomplete="off" placeholder="Busca la profesión" value="<?php //echo @$profesion_uno; ?>" required>           
                      <div class="input-group-btn" style="height:60px;top:-7px;">
                         <button type="button" class="btn btn-default dropdown-toggle buscador" data-toggle="dropdown" aria-expanded="false" id="list_complete" style="background-color: transparent;border-color: black;border-left: 0;padding: 12px;"><span class="caret"></span></button>
                      </div>
                    </div>
                    <ul class="dropdown-menu scrollable-menu" role="menu" aria-labelledby="menu1" id="medicos_list_id"></ul>
                </div>
                <div class="dropdown clearfix estudios">
                    <div class="input-group">
                      <input name="estudios" type="text" id="estudios" class="typeahead principal center-block form-control input-lg" autocomplete="off" placeholder="Estudios asociados" value="<?php //echo @$profesion_uno; ?>">           
                      <div class="input-group-btn" style="height:60px;top:-7px;">
                         <button type="button" class="btn btn-default dropdown-toggle buscador" data-toggle="dropdown" aria-expanded="false" id="list_complete_estudios" style="background-color: transparent;border-color: black;border-left: 0;padding: 12px;"><span class="caret"></span></button>
                      </div>
                    </div>
                    <ul class="dropdown-menu scrollable-menu" role="menu" aria-labelledby="menu1" id="medicos_list_id"></ul>
                </div>
                <textarea name="descripcion" id="descripcion" placeholder="Escribe una corta descripcion de la profesion"></textarea>
              </div>
            </div>

            <div class="col-md-6 col-xs-12 text-center">
              <div class="col-md-8 col-md-offset-2 col-xs-12">

                <div class="col-md-12 col-xs-12 text-center">
                  <h2>SALARIO APROXIMADO</h2>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="titulo2">Salario en 2010: <strong id="s_past"></strong> euros/mes</div>
                  <input class="sliders" id="ex1" data-slider-id='ex1Slider' type="text" data-slider-min="700" data-slider-max="20000" data-slider-step="50" data-slider-value="1500"/>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="titulo2">Salario en 2015: <strong id="s_present"></strong> euros/mes</div>
                  <input class="sliders" id="ex2" data-slider-id='ex2Slider' type="text" data-slider-min="700" data-slider-max="20000" data-slider-step="50" data-slider-value="1500"/>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="titulo2">Salario en 2020: <strong id="s_future"></strong> euros/mes</div>
                  <input class="sliders" id="ex3" data-slider-id='ex3Slider' type="text" data-slider-min="700" data-slider-max="20000" data-slider-step="50" data-slider-value="1500"/>
                </div>

                <hr>

                <div class="col-md-12 col-xs-12 text-center">
                  <h2>DESEMPLEO (%)</h2>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="titulo2">Desempleo en 2010: <strong id="p_past"></strong> %</div>
                  <input class="sliders" id="ex4" data-slider-id='ex4Slider' type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="50"/>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="titulo2">Desempleo en 2015: <strong id="p_present"></strong> %</div>
                  <input class="sliders" id="ex5" data-slider-id='ex5Slider' type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="50"/>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="titulo2">Desempleo en 2020: <strong id="p_future"></strong> %</div>
                  <input class="sliders" id="ex6" data-slider-id='ex6Slider' type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="50"/>
                </div>

                <hr>

                <div class="col-md-12 col-xs-12 text-center">
                  <h2>CAPACIDADES PROFESIONALES</h2>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="titulo2">Memoria: <strong id="c_memoria"></strong></div>
                  <input class="sliders" id="ex7" data-slider-id='ex7Slider' type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="5"/>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="titulo2">Logica: <strong id="c_logica"></strong></div>
                  <input class="sliders" id="ex8" data-slider-id='ex8Slider' type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="5"/>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="titulo2">Creatividad: <strong id="c_creatividad"></strong></div>
                  <input class="sliders" id="ex9" data-slider-id='ex9Slider' type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="5"/>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="titulo2">Comunicacion: <strong id="c_comunicacion"></strong></div>
                  <input class="sliders" id="ex10" data-slider-id='ex10Slider' type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="5"/>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="titulo2">Forma Fisica: <strong id="c_formafisica"></strong></div>
                  <input class="sliders" id="ex11" data-slider-id='ex11Slider' type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="5"/>
                </div>

              </div>
            </div>
    
          </div> 

      </form>
    </div>
  </body>

</html>

