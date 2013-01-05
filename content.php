				<?php do_action( 'tha_entry_before' ); ?>
				<article id="post-<?php the_ID() ?>" <?php post_class() ?>>
					<?php do_action( 'tha_entry_top' ); ?>

					<header class="entry-title">
						<?php do_action( 'tha_entry_header_top' ); ?>
						<h1><?php if ( !is_singular() ) { ?><a href="<?php the_permalink() ?>"><?php } the_title(); if ( !is_singular() ) { ?></a><?php } ?></h1>
						<?php do_action( 'tha_entry_header_bottom' ); ?>
					</header>

					<?php do_action( 'tha_entry_content_before' ); ?>
					<div class="entry-content">
						<?php do_action( 'tha_entry_content_top' ); ?>
						<?php the_content(__('Continue&hellip;', 'activist')) ?>
						<?php do_action( 'tha_entry_content_bottom' ); ?>
					</div>
					<?php do_action( 'tha_entry_content_after' ); ?>

					<?php do_action( 'tha_entry_bottom' ); ?>
				</article>
				<?php do_action( 'tha_entry_after' ); ?>