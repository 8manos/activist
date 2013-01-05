<?php get_header() ?>

		<?php do_action( 'tha_content_before' ); ?>
		<div id="main" class="main-content" role="main">
			<?php do_action( 'tha_content_top' ); ?>
			<?php if ( have_posts() ) : ?>
				<?php do_action( 'tha_loop_before' ); ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content', apply_filters( 'kct_content_template', get_post_type() ) ); ?>
				<?php endwhile; ?>
				<?php do_action( 'tha_loop_after' ); ?>
			<?php else : ?>
				<?php get_template_part( 'content', apply_filters( 'kct_content_template', '404' ) ); ?>
			<?php endif; ?>
			<?php do_action( 'tha_content_bottom' ); ?>
		</div>
		<?php do_action( 'tha_content_after' ); ?>

<?php get_footer() ?>