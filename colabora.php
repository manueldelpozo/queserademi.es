
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
      <link rel="stylesheet" href="css/slider.css">
      <link rel="stylesheet" href="css/style.css" />
      <!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
      <script type="text/javascript" src="js/jquery-2.1.3.js" ></script>
      <script type="text/javascript" src="js/bootstrap.min.js" ></script>
      <script type="text/javascript" src="js/bootstrap-slider.js" ></script>
      <script type="text/javascript" src="js/scripts.js"></script>   
      <script type="text/javascript">
        $(document).ready(function() {
          
          $(".sliders").slider({
            formatter: function(value) {
              return value;
            }
          });
          
          $(".sliders").on("slide", function(slideEvt) {
            var idname = $(this).attr("id");
            var idvalue = "#" + idname + "_value";
            var unidad = "";
            if ( $(this).hasClass("salarios") )
              unidad = " €/mes";
            else if ( $(this).hasClass("paros") )
              unidad = " %";
            $(idvalue).text( slideEvt.value + unidad );
          });
          
          $(".slider").css("width","100%");
          
        });
      </script>
      <style type="text/css" media="screen">
        .slider-selection {
          background: #BABABA;
        }
        .colabora {
          width: 100%;
          margin-top: 50px;
        }
        .titulo1 {
          margin-top: 20px;
          border-top: 1px;
        }
        .slider-handle {
          background-image: linear-gradient(to bottom, red, #c00);
        }
        .normal-input {
          -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
          -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
          background-color: rgba(255, 255, 255, 0.6);
          /* For IE 5.5 - 7*/
          filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);
          /* For IE 8*/
          -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
          border-color: black;
        }
        .verif{ 
          display: none; 
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
    <!-- End Google Tag Manager -->
    
    
    <div class="container-full">
      <form id="formulario" role="form" action="verificador.php" method="post" onsubmit="return validacion()">

          <div class="row header">

            <div class="col-md-4 col-xs-12">  
              <h3>Colabora con el proyecto queserademi, son solo 5 minutos...</h3>  
            </div>

            <div class="col-md-4 hidden-xs text-center">
              <a href="index.html">
                <h6 class="sublead">Tu comparador de profesiones</h6>
                <img class="img-responsive" src="images/logo.svg" height="60px"> 
              </a>
            </div>

            <div class="col-md-4 text-center">
              <h3><a href="">Por que colaborar?</a></h3>
            </div>

          </div> 

          <div class="row body">

            <div class="col-md-6 col-xs-12 text-center">
              <div class="col-md-8 col-md-offset-2 col-xs-12">
                <div class="form-group">
                  <label for="nombre">Nombre:</label>
                  <input name="nombre" type="text" id="nombre" class="normal-input center-block form-control input-lg" placeholder="Tu nombre completo" value="<?php //echo @$profesion_uno; ?>" autofocus>
                </div>
                <div class="form-group">
                  <label for="email">Email:</label>
                  <input name="email" type="email" id="email" class="normal-input center-block form-control input-lg" placeholder="Tu direccion email" value="<?php //echo @$profesion_uno; ?>">
                </div>
                <div class="form-group dropdown clearfix profesion">
                  <label for="profesion">Profesion:</label>
                    <div class="input-group">
                      <input name="profesion" type="text" id="profesion" class="typeahead center-block form-control input-lg" autocomplete="off" placeholder="Busca la profesión" value="<?php //echo @$profesion_uno; ?>" required>           
                      <div class="input-group-btn" style="height:60px;top:-7px;">
                         <button type="button" class="btn btn-default dropdown-toggle buscador" data-toggle="dropdown" aria-expanded="false" id="list_complete" style="background-color: transparent;border-color: black;border-left: 0;padding: 12px;"><span class="caret"></span></button>
                      </div>
                    </div>
                    <ul class="dropdown-menu scrollable-menu" role="menu" aria-labelledby="menu1" id="medicos_list_id"></ul>
                </div>
                <div class="form-group dropdown clearfix estudios">
                  <label for="estudios_asoc">Estudios asociados:</label>
                    <div class="input-group">
                      <input name="estudios_asoc" type="text" id="estudios_asoc" class="typeahead center-block form-control input-lg" autocomplete="off" placeholder="Estudios asociados" value="<?php //echo @$profesion_uno; ?>">           
                      <div class="input-group-btn" style="height:60px;top:-7px;">
                         <button type="button" class="btn btn-default dropdown-toggle buscador" data-toggle="dropdown" aria-expanded="false" id="list_complete_estudios" style="background-color: transparent;border-color: black;border-left: 0;padding: 12px;"><span class="caret"></span></button>
                      </div>
                    </div>
                    <ul class="dropdown-menu scrollable-menu" role="menu" aria-labelledby="menu1" id="medicos_list_id"></ul>
                </div>
                <div class="form-group">
                  <label for="descripcion">Descripcion:</label>
                  <textarea name="descripcion" id="descripcion" class="normal-input center-block form-control input-lg" rows="5" placeholder="Escribe una corta descripcion de la profesion"></textarea>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-xs-12 text-center">
              <div class="col-md-8 col-md-offset-2 col-xs-12">

                <div class="col-md-12 col-xs-12 text-center titulo1">
                  <h4>SALARIO APROXIMADO</h4>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group">
                    <div class="titulo2">Salario en 2010: <strong id="s_past_value"></strong></div>
                    <input class="sliders salarios" id="s_past" type="text" data-slider-min="700" data-slider-max="15000" data-slider-step="50" data-slider-value="2000" data-slider-handle="square"/>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group">
                    <div class="titulo2">Salario en 2015: <strong id="s_present_value"></strong></div>
                    <input class="sliders salarios" id="s_present" type="text" data-slider-min="700" data-slider-max="15000" data-slider-step="50" data-slider-value="2000" data-slider-handle="square"/>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group">
                    <div class="titulo2">Salario en 2020: <strong id="s_future_value"></strong></div>
                    <input class="sliders salarios" id="s_future" type="text" data-slider-min="700" data-slider-max="15000" data-slider-step="50" data-slider-value="2000" data-slider-handle="square"/>
                  </div>
                </div>

                <hr>

                <div class="col-md-12 col-xs-12 text-center titulo1">
                  <h4>DESEMPLEO (%)</h4>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group">
                    <div class="titulo2">Desempleo en 2010: <strong id="p_past_value"></strong></div>
                    <input class="sliders paros" id="p_past" type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="10" data-slider-handle="square"/>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group">
                    <div class="titulo2">Desempleo en 2015: <strong id="p_present_value"></strong></div>
                    <input class="sliders paros" id="p_present" type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="10" data-slider-handle="square"/>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group">
                    <div class="titulo2">Desempleo en 2020: <strong id="p_future_value"></strong></div>
                    <input class="sliders paros" id="p_future" type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="10" data-slider-handle="square"/>
                  </div>
                </div>

                <hr>

                <div class="col-md-12 col-xs-12 text-center titulo1">
                  <h4>CAPACIDADES PROFESIONALES</h4>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group">
                    <div class="titulo2">Memoria: <strong id="c_memoria_value"></strong></div>
                    <input class="sliders capacidades" id="c_memoria" type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="5"/>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group">
                    <div class="titulo2">Logica: <strong id="c_logica_value"></strong></div>
                    <input class="sliders capacidades" id="c_logica" type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="5"/>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group">
                    <div class="titulo2">Creatividad: <strong id="c_creatividad_value"></strong></div>
                    <input class="sliders capacidades" id="c_creatividad" type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="5"/>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group">
                    <div class="titulo2">Comunicacion: <strong id="c_comunicacion_value"></strong></div>
                    <input class="sliders capacidades" id="c_comunicacion" type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="5"/>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group">
                    <div class="titulo2">Forma Fisica: <strong id="c_formafisica_value"></strong></div>
                    <input class="sliders capacidades" id="c_formafisica" type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="5"/>
                  </div>
                </div>
                
                <label for="verificacion" class="verif">¡Si ves esto, no llenes el siguiente campo!</label>
                <input name="verificacion" class="verif" />

                <div class="col-md-4 col-md-offset-8 col-xs-12">
                  <div class="form-group"> 
                    <button type="submit" class="btn btn-default colabora">Colabora</button>
                  </div>
                </div>

              </div>
            </div>
    
          </div> 

      </form>
    </div>
  </body>

</html>

