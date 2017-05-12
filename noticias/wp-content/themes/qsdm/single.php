<?php get_header(); ?>

<div id="blog">

  <section>
        <?php define( 'WP_USE_THEMES', false ); get_header(); ?>

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

          <article class="blogbox2">
                        <div class="blogtitle2"><a><?php the_title();?></a></div>
                        <div class="blogdata">
                            <p><?php the_date();?></p>
                        </div>
                        <div class="gallerytext2">
                          <p><?php the_content();?></p>
                        </div>

          </article>

        <?php endwhile; else : ?>
        <h1>No se encontraron Articulos</h1>
        <?php endif; ?>

  </section>

</div>

<?php get_footer(); ?>
