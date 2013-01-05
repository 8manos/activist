<?php

if ( is_404() ) {
	$title = __('This is somewhat embarrassing, isn&rsquo;t it?', 'activist');
	$info = __('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help :)', 'activist');
}
else {
	$title = __('Nothing Found', 'activist');
	$info = __('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'activist');
}
?>
				<?php do_action( 'tha_entry_before' ); ?>
				<article id="not-found" class="hentry entry404">
					<?php do_action( 'tha_entry_top' ); ?>

					<header class="entry-title">
						<?php do_action( 'tha_entry_header_top' ); ?>
						<h1><?php echo $title ?></h1>
						<?php do_action( 'tha_entry_header_bottom' ); ?>
					</header>

					<?php do_action( 'tha_entry_content_before' ); ?>
					<div class="entry-content">
						<?php do_action( 'tha_entry_content_top' ); ?>
            <p><?php echo $info ?></p>
						<?php do_action( 'tha_entry_content_bottom' ); ?>
					</div>
					<?php do_action( 'tha_entry_content_after' ); ?>

					<?php do_action( 'tha_entry_bottom' ); ?>
				</article>
				<?php do_action( 'tha_entry_after' ); ?>