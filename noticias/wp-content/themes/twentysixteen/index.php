<?php get_header(); ?>
<!-- Google Tag Manager -->
		<noscript>
			<iframe src="//www.googletagmanager.com/ns.html?id=GTM-WS6V49" height="0" width="0" style="display:none;visibility:hidden"></iframe>
		</noscript>
		<script>
			(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','GTM-WS6V49');
		</script>
        <!-- End Google Tag Manager -->
        <div id="preloader"></div>
<div class="background-image"></div>
<div id="blog">

  <section>
        <?php define( 'WP_USE_THEMES', false ); get_header(); ?>

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

          <article class="blogbox">
                        <div class="blogimg"><a href="<?php the_permalink();?>">
                            <?php if (has_post_thumbnail() ) {the_post_thumbnail('list_articles_thumbs');}?>
                        </a></div>
                        <div class="blogtitle"><a href="<?php the_permalink();?>"><?php the_title();?></a></div>
                        <div class="gallerytext">
                          <p><?php the_excerpt();?></p>
                        </div>
                        <div class="blogdata">
                            <p><?php the_date();?></p>
                        </div>
          </article>

        <?php endwhile; else : ?>
        <h1>No se encontraron Articulos</h1>
        <?php endif; ?>

  </section>

</div>

<?php get_footer(); ?>
