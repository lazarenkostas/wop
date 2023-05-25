<?php
/**
 * Page
 */
get_header(); ?>
	<main class="main-content">
					<?php if ( have_posts() ) : ?>
						<?php while ( have_posts() ) : the_post(); ?>
								<div class="entry__content">
									<?php the_content( '', true ); ?>
								</div>
						<?php endwhile; ?>
					<?php endif; ?>
	</main>
<?php get_footer(); ?>
