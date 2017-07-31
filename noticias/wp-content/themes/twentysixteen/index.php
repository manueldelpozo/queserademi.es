<?php get_header(); ?>

<div id="blog">

  <section>
        <?php define( 'WP_USE_THEMES', false ); get_header(); ?>

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

          <article class="blogbox">
                        <div class="blogimg"><a href="<?php the_permalink();?>">
                            <?php if (has_post_thumbnail() ) {the_post_thumbnail('list_articles_thumbs');}?>
                        </a></div>
                        <h1 class="blogtitle"><a href="<?php the_permalink();?>"><?php the_title();?></a></h1>
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
