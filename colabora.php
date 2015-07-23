
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
      <link href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">
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
      <script type="text/javascript" src="js/scripts.js" defer></script>   
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
            $(idvalue).html( "<span style='color:#342777;'>" + slideEvt.value + unidad + "<span>");
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
        .form-group.required label:after {
          content:"*";
          color:red;
        }
        ul.share-buttons{
          list-style: none;
          padding: 0;
        }
        ul.share-buttons li{
          display: inline;
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

            <div class="col-md-3 col-md-offset-1 col-xs-12">  
              <h3>Colabora con queserademi, <br><strong>son solo 2 minutos...</strong></h3>  
            </div>

            <div class="col-md-4 hidden-xs text-center">
              <a href="index.html">
                <img class="img-responsive" src="images/logo.svg" height="60px"> 
              </a>
            </div>

            <div class="col-md-4 text-center">
              <h3><a href="porquecolaborar.html">Por qué colaborar?</a></h3>
            </div>

          </div> 

          <div class="row body">

            <div class="col-md-6 col-xs-12 text-center">
              <div class="col-md-10 col-md-offset-1 col-xs-12">
                <div class="form-group">
                  <label for="colaborador">Nombre:</label>
                  <input name="colaborador" type="text" id="colaborador" class="normal-input center-block form-control input-lg" placeholder="Tu nombre completo" value="<?php //echo @$profesion_uno; ?>" autofocus/>
                </div>
                <div class="form-group">
                  <label for="email">Email:</label>
                  <input name="email" type="email" id="email" class="normal-input center-block form-control input-lg" placeholder="Tu direccion email" value="<?php //echo @$profesion_uno; ?>"/>
                </div>
                <div class="form-group dropdown clearfix profesion required">
                  <label for="profesion">Profesión:</label>
                    <div class="input-group">
                      <input name="profesion" type="text" id="profesion" class="typeahead center-block form-control input-lg" autocomplete="off" placeholder="Busca la profesión" value="<?php //echo @$profesion_uno; ?>" required/>           
                      <div class="input-group-btn" style="height:60px;top:-7px;">
                         <button type="button" class="btn btn-default dropdown-toggle buscador" data-toggle="dropdown" aria-expanded="false" id="list_complete" style="background-color: rgba(255, 255, 255, 0.6);border-color: black;border-left: 0;padding: 12px;"><span class="caret"></span></button>
                      </div>
                    </div>
                    <ul class="dropdown-menu scrollable-menu" role="menu" aria-labelledby="menu1" id="medicos_list_id"></ul>
                </div>
                <div class="form-group dropdown clearfix estudios">
                  <label for="estudios_asoc">Estudios asociados:</label>
                    <div class="input-group">
                      <input name="estudios_asoc" type="text" id="estudios_asoc" class="typeahead center-block form-control input-lg" autocomplete="off" placeholder="Estudios asociados" value="<?php //echo @$profesion_uno; ?>"/>           
                      <div class="input-group-btn" style="height:60px;top:-7px;">
                         <button type="button" class="btn btn-default dropdown-toggle buscador" data-toggle="dropdown" aria-expanded="false" id="list_complete_estudios" style="background-color: rgba(255, 255, 255, 0.6);border-color: black;border-left: 0;padding: 12px;"><span class="caret"></span></button>
                      </div>
                    </div>
                    <ul class="dropdown-menu scrollable-menu" role="menu" aria-labelledby="menu1" id="medicos_list_id"></ul>
                </div>
                <div class="form-group">
                  <label for="descripcion">Descripción:</label>
                  <textarea name="descripcion" id="descripcion" class="normal-input center-block form-control input-lg" rows="5" placeholder="Escribe una breve descripción de la profesion"></textarea>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-xs-12 text-center">
              <div class="col-md-10 col-md-offset-1 col-xs-12">

                <div class="col-md-12 col-xs-12 text-center titulo1">
                  <h5><strong>SALARIO (€/mes neto aprox.)</strong></h5>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group">
                    <label class="titulo2">Salario 2010: </label>
                    <input class="sliders salarios" id="s_past" type="text" data-slider-min="700" data-slider-max="15000" data-slider-step="50" data-slider-value="2000" data-slider-handle="square"/>
                    <strong id="s_past_value"></strong>                  
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group required">
                    <label class="titulo2">Salario 2015:</label>
                    <input class="sliders salarios" id="s_present" type="text" data-slider-min="700" data-slider-max="15000" data-slider-step="50" data-slider-value="2000" data-slider-handle="square" required/>
                    <strong id="s_present_value"></strong>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group">
                    <label class="titulo2">Salario 2020: </label>
                    <input class="sliders salarios" id="s_future" type="text" data-slider-min="700" data-slider-max="15000" data-slider-step="50" data-slider-value="2000" data-slider-handle="square"/>
                    <strong id="s_future_value"></strong>
                  </div>
                </div>

                <hr>

                <div class="col-md-12 col-xs-12 text-center titulo1">
                  <h5><strong>DESEMPLEO (% aprox.)</strong></h5>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group">
                    <label class="titulo2">Desempleo 2010: </label>
                    <input class="sliders paros" id="p_past" type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="10" data-slider-handle="square"/>
                    <strong id="p_past_value"></strong>                 
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group required">
                    <label class="titulo2">Desempleo 2015:</label> 
                    <input class="sliders paros" id="p_present" type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="10" data-slider-handle="square" required/>
                    <strong id="p_present_value"></strong>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group">
                    <label class="titulo2">Desempleo 2020: </label>
                    <input class="sliders paros" id="p_future" type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="10" data-slider-handle="square"/>
                    <strong id="p_future_value"></strong>
                  </div>
                </div>

                <hr>

                <div class="col-md-12 col-xs-12 text-center titulo1">
                  <h5><strong>CAPACIDADES PROFESIONALES [1-10]</strong></h5>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group required">
                    <label class="titulo2">Memoria:</label><strong id="c_memoria_value"></strong>
                    <input class="sliders capacidades" id="c_memoria" type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="5" required/>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group required">
                    <label class="titulo2">Lógica:</label><strong id="c_logica_value"></strong>
                    <input class="sliders capacidades" id="c_logica" type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="5" required/>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group required">
                    <label class="titulo2">Creatividad:</label><strong id="c_creatividad_value"></strong>
                    <input class="sliders capacidades" id="c_creatividad" type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="5" required/>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group required">
                    <label class="titulo2">Comunicación:</label><strong id="c_comunicacion_value"></strong>
                    <input class="sliders capacidades" id="c_comunicacion" type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="5" required/>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group required">
                    <label class="titulo2">Forma Física:</label><strong id="c_formafisica_value"></strong>
                    <input class="sliders capacidades" id="c_formafisica" type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="5" required/>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12 text-center">
                  <div class="form-group">
                    <label for="verificacion" class="verif">¡Si ves esto, no rellenes el siguiente campo!</label>
                    <input name="verificacion" class="verif" />
                  </div>
                </div>

                <div class="col-md-4 col-md-offset-8 col-xs-12">
                  <div class="form-group"> 
                    <button type="submit" class="btn btn-default colabora">COLABORA!</button>
                  </div>
                </div>

              </div>
            </div>
    
          </div> 

      </form>
    </div>

    <footer>
      <div class="row">
        <div class="col-md-3 col-xs-12 text-center">
          <a href="colabora.php">Colabora con queserademi</a>
        </div>
        <div class="col-md-2 col-xs-12 text-center">
          <a href="porquecolaborar.html">Por qué colaborar</a>
        </div>
        <div class="col-md-3 col-xs-12 text-center">
          <a href="quienessomos.html">Quiénes somos</a>
        </div>
        <div class="col-md-2 col-xs-12 text-center">
          <a href="mailto:info@queserademi.es?subject=Pregunta%20para%20queserademi&body=Hola,%0D%0A%0D%0AQuiero contactar con vosotros para..." target="_top">Contacta con nosotros</a>
        </div>
        <div class="col-md-2 col-xs-12 text-center">
          <ul class="share-buttons">
            <li><a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.queserademi.es&t=Comparador%20de%20profesiones" target="_blank" title="Share on Facebook" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL) + '&t=' + encodeURIComponent(document.URL)); return false;"><i class="fa fa-facebook-square fa-2x"></i></a></li>
            <li><a href="https://plus.google.com/share?url=http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Share on Google+" onclick="window.open('https://plus.google.com/share?url=' + encodeURIComponent(document.URL)); return false;"><i class="fa fa-google-plus-square fa-2x"></i></a></li>
            <li><a href="http://www.linkedin.com/shareArticle?mini=true&url=http%3A%2F%2Fwww.queserademi.es&title=Comparador%20de%20profesiones&summary=&source=http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Share on LinkedIn" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(document.URL) + '&title=' +  encodeURIComponent(document.title)); return false;"><i class="fa fa-linkedin-square fa-2x"></i></a></li>
            <li><a href="mailto:?subject=Comparador%20de%20profesiones&body=:%20http%3A%2F%2Fwww.queserademi.es" target="_blank" title="Email" onclick="window.open('mailto:?subject=' + encodeURIComponent(document.title) + '&body=' +  encodeURIComponent(document.URL)); return false;"><i class="fa fa-envelope-square fa-2x"></i></a></li>
          </ul>
        </div>
      </div>
    </footer>
  </body>

</html>

