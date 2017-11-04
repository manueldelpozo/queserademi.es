<!DOCTYPE html>
<html <?php language_attributes(); ?>>
       <head>
	    <meta charset="<?php bloginfo( 'charset' ); ?>">
	    <meta name="viewport" content="width=device-width">
	    <link rel="profile" href="http://gmpg.org/xfn/11">
	    <!--link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"-->

	    <!--Links css-->
	    <!--link rel="icon" type="image/x-icon" href="http://queserademi.com/images/logo.png"-->
            <link rel="stylesheet" href="http://queserademi.com/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/font-awesome.css">
	    <link rel="stylesheet" href="http://queserademi.com/css/style.css">
	    <link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url')?>">

             <?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<!-- Google Tag Manager (noscript) -->
		<noscript>
		  <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KSJZX5B"
		  height="0" width="0" style="display:none;visibility:hidden"></iframe>
		</noscript>
		<!-- End Google Tag Manager (noscript) -->
        <div id="preloader"></div>
<div class="background-image"></div>

<div class="row header">

	            <div class="col-md-3 col-md-offset-1 hidden-xs">  
                      <h4>canal de novedades<br><strong class="titulo-blog">queserademi</strong></h4>  
	            </div>

	            <div class="col-xs-3  text-center col-md-4" style="z-index: 1;">
	              <a href="http://queserademi.com/noticias">
	                <img class="img-responsive" src="http://queserademi.com/images/logo.png">               
	              </a>
	            </div>

	            <div class="col-xs-9 hidden-sm hidden-md hidden-lg" style="z-index: 1;">
	               <h4>canal de novedades<br><strong class="titulo-blog">queserademi</strong></h4> 
	            </div>  

	            <!--div class="col-md-3 hidden-xs" align="right"-->
	              <!--h4><a href="http://queserademi.com/colabora.php">colabora :)<br> son solo 2 min!</a></h4-->
			<?php get_sidebar('sidebar-1'); ?>
	            <!--/div-->

	        </div>
<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
* Please browse readme.txt for credits and forking information
 * @package noteblog
 */

?>

<!--
  <div id="page" class="hfeed site">
    <header id="masthead">
      <nav class="navbar lh-nav-bg-transform navbar-default navbar-fixed-top navbar-left"> 
        <div class="container" id="navigation_menu">
          <div class="navbar-header"> 
            <?php if ( has_nav_menu( 'primary' ) ) { ?>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> 
              <span class="sr-only"><?php echo esc_html('Toggle Navigation', 'noteblog') ?></span> 
              <span class="icon-bar"></span> 
              <span class="icon-bar"></span> 
              <span class="icon-bar"></span> 
            </button> 
            <?php } ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
              <?php 
              if (!has_custom_logo()) { 
                echo '<div class="navbar-brand">'; bloginfo('name'); echo '</div>';
              } else {
                the_custom_logo();
              } ?>
            </a>
          </div> 
          <?php if ( has_nav_menu( 'primary' ) ) {
              noteblog_header_menu(); // main navigation 
            }
            ?>

          </div>
        </nav>
        <?php if ( is_front_page() ) { ?>
        <div class="site-header">
          <div class="site-branding"> 
            <span class="home-link">
              <?php if (get_theme_mod('hero_image_title') ) : ?>
              <span class="frontpage-site-title"><?php echo wp_kses_post(get_theme_mod('hero_image_title')) ?></span>
              <?php else : ?>
              <span class="frontpage-site-title"><?php bloginfo( 'name' ); ?></span>
            <?php endif; ?>

            <?php if (get_theme_mod('hero_image_subtitle') ) : ?>
            <span class="frontpage-site-description"><?php echo wp_kses_post(get_theme_mod('hero_image_subtitle')) ?></span>
            <?php else : ?>
            <span class="frontpage-site-description"><?php bloginfo( 'description' ); ?></span>
          <?php endif; ?>
      </span>

    </div>
  </div>
  <?php } else {  ?>

  <?php } ?>
</header>    
-->

<div id="content" class="site-content">