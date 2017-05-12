<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

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
        <div class='seo-header'>
	        <h1>Página 404</h1>
	        <h2>Not found 404</h2>
        </div>
        <div class="background-image grayscale"></div>
		<div class="container-full">

			<div class="row header">
				<div class="col-md-6 col-md-offset-3 col-xs-12 text-center">
					<a href="http://queserademi.com">
						<h1 id="titulo" class="lead"><strong>que</strong>sera<strong>de</strong>mi</h1>
						<img class="img-responsive" src="http://queserademi.com/images/logo.svg">
					</a>
					<h6 class="sublead">ERROR 404</h6>
			    </div>
			</div>

			<div class="row body">
			  	<div class="col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1 text-center">
					<h1>Disculpanos...</h1>
					<h2>No es problema de tu red. <br>Simplemente esta página no existe.</h2>
					<hr>
					<h4>Por favor, prueba otra dirección o contacta con nosotros a través de 
						<a href="mailto:info@queserademi.com?subject=Pregunta%20para%20queserademi&body=Hola,%0D%0A%0D%0AQuiero contactar con vosotros para..." target="_top">info@queserademi.com</a>
					</h4>
				</div>
			</div>
			<div class="col-xs-12 hidden-sm hidden-md hidden-lg margen"></div>
		</div>
<?php get_footer(); ?>
