<?php
/**
 * The template for displaying the footer.
 *
 * Please browse readme.txt for credits and forking information
 * Contains the closing of the #content div and all content after
 *
 * @package noteblog
 */

?>

</div><!-- #content -->

<?php if ( is_active_sidebar( 'footer_widget_left') ||  is_active_sidebar( 'footer_widget_middle') ||  is_active_sidebar( 'footer_widget_right')  ) : ?>
<div class="footer-widget-wrapper">
	<div class="container">

		<div class="row">
			<div class="col-md-4">
				<?php dynamic_sidebar( 'footer_widget_left' ); ?> 
			</div>
			<div class="col-md-4">
				<?php dynamic_sidebar( 'footer_widget_middle' ); ?> 
			</div>
			<div class="col-md-4">
				<?php dynamic_sidebar( 'footer_widget_right' ); ?> 
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
	
<footer id="colophon" class="site-footer">
	<div class="row site-info">
		<div class="copy-right-section">
		<?php if (get_theme_mod('footer_copyright_content') ) : ?>
		<?php echo wp_kses_post(get_theme_mod('footer_copyright_content')) ?>
		<?php else : ?>
		<?php echo '&copy; '.date_i18n(__('Y','noteblog')); ?> <?php bloginfo( 'name' ); ?>
		<?php endif; ?>
		</div>
	</div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

<!-- mi footer -->
<footer>
	<div class="row">
				<div class="col-lg-12 col-md-12 hidden-sm hidden-xs text-center">
					<button type="button" data-toggle="dropup" aria-expanded="false" class="btn-footer" id="btn-footer-md" ><span class="caret flecha"></span></button>
		        </div>
		        <div class="hidden-lg hidden-md col-sm-12 col-xs-12" style="padding: 0;">
		        	<div class="col-sm-3 col-xs-3 text-center">
		        		<a href="http://queserademi.com">	
			        		<img class="img-menu" src="http://queserademi.com/images/logo.svg" width='35px' height="auto">      	
		          		</a>
		        	</div>
		        	<div class="col-sm-3 col-sm-offset-6 col-xs-3 col-xs-offset-6">
						<button type="button" data-toggle="dropup" aria-expanded="false" class="btn-footer" id="btn-footer-xs" ><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span></button>
					</div>
		        </div>
				<div class="col-md-2 col-md-offset-0 hidden-sm hidden-xs col-xs-6 col-xs-offset-3 text-center">
			        <a href="http://queserademi.com">	
			          	<p id="titulo" style='opacity:1;margin-top:-10px;'>
				          	<img class="image-container" src="http://queserademi.com/images/logo.svg">
				          	<strong>que</strong>sera<strong>de</strong>mi
			          	</p>
			        </a>
		        </div>
			    <div class="col-md-10 col-sm-12 col-xs-12 text-center">
			        <div class="col-md-2 col-md-offset-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
					  <a href="http://queserademi.com/colabora.php">cómo colaborar</a>
					  <span class="hidden-sm hidden-xs separador">|</span>
					</div>
					<div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
					  <a href="http://queserademi.com/porquecolaborar.html">por qué colaborar</a>
					  <span class="hidden-sm hidden-xs separador">|</span>
					</div>
					<div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu">
					  <a href="http://queserademi.com/quienessomos.html">quiénes somos</a>
					  <span class="hidden-sm hidden-xs separador">|</span>
					</div>
					<div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu sel-menu">
					  <a href="http://queserademi.com/noticias/">qué noticias</a>
					</div>
			        <div class="col-md-2 col-sm-12 col-xs-12 hidden-xs mobile-menu social">
			          <ul class="share-buttons">
			            <li><a href="https://www.facebook.com/queserademicom" target="_blank" title="Share on Facebook" onclick="window.open('https://www.facebook.com/queserademicom'); return false;"><i class="fa fa-facebook-square fa-2x"></i></a></li>
			            <li><a href="mailto:?subject=Comparador%20de%20profesiones&body=:%20http%3A%2F%2Fwww.queserademi.com" target="_blank" title="Email" onclick="window.open('mailto:?subject=' + encodeURIComponent(document.title) + '&body=' +  encodeURIComponent(document.URL)); return false;"><i class="fa fa-envelope-square fa-2x"></i></a></li>
			          </ul>
			        </div>
		        </div>
		        <div class="col-md-10 col-md-offset-2 col-sm-12 col-xs-12 terminos text-center">
	                <div class="col-md-2 col-md-offset-4 col-sm-12 col-xs-12 hidden-xs mobile-menu">
	                    <a href="http://queserademi.com/quenossugieres.html">qué nos sugieres</a>
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
    <script type="text/javascript" src="http://queserademi.com/js/jquery-2.1.3.js"></script>
    <script type="text/javascript" src="http://queserademi.com/js/bootstrap.min.js"></script>            
    <script type="text/javascript" src="http://queserademi.com/js/scripts.js"></script>
    <?php wp_footer(); ?>

</body>
</html>



</body>
</html>
