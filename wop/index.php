<?php
/**
 * Index
 *
 * Standard loop for the front-page
 */
get_header(); ?>
	<main class="main-content">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class( 'entry' ); ?>>
                    <h1 class="page-title entry__title"><?php the_title(); ?></h1>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div title="<?php the_title_attribute(); ?>" class="entry__thumb">
                            <?php the_post_thumbnail( 'large' ); ?>
                        </div>
                    <?php endif; ?>
                    <div class="entry__content">
                        <?php the_content( '', true ); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
	</main>

<?php get_footer(); ?>