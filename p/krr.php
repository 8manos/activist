<?php

/**
 * Check requirements
 *
 * This will check or the required plugins/functions needed
 * for the theme to work. If one of the requirements doesnt exist,
 * it will activate the default theme set by WP_DEFAULT_THEME constant
 *
 * @param array $reqs Array of classes/functions to check
 */
function kct_check_req( $reqs, $message = '' ) {
	foreach ( $reqs as $req ) {
		if ( !class_exists($req) || !function_exists($req) ) {
			$message .= '<br />&laquo; <a href="'.wp_get_referer().'">'.__('Go back', 'activist').'</a>.';
			switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
			wp_die( $message );
		}
	}
}


/**
 * Some more body classes
 */
function kct_body_class( $classes ) {
	if ( is_singular() ) {
		$classes[] = 'singular';
	}

	return $classes;
}


/**
 * Print sidebar
 */
function kct_do_sidebar( $sidebar, $wrap = true, $class = 'sidebar' ) {
	if ( !is_active_sidebar($sidebar) )
		return;
?>
<?php do_action( 'tha_sidebars_before', $sidebar, $wrap, $class ); ?>
<?php if ( $wrap ) { ?>
<div id="<?php echo $sidebar ?>" class="<?php echo $class ?>">
<?php } ?>
	<?php do_action( 'tha_sidebar_top', $sidebar ); ?>
	<?php do_action( "tha_sidebar_top_{$sidebar}"  ); ?>
	<?php dynamic_sidebar( $sidebar ); ?>
	<?php do_action( "tha_sidebar_bottom_{$sidebar}" ); ?>
	<?php do_action( 'tha_sidebar_bottom', $sidebar ); ?>
<?php if ( $wrap ) { ?>
</div>
<?php } ?>
<?php do_action( 'tha_sidebars_after', $sidebar, $wrap, $class ); ?>
	<?php
}


/**
 * Some more body classes
 */
function kct_post_class( $classes, $class, $post_id ) {
	if ( current_theme_supports('post-thumbnails') && has_post_thumbnail() )
		$classes[] = 'has-post-thumbnail';

	if ( is_singular() && get_queried_object_id() === $post_id )
		$classes[] = 'kc-current-post';

	return $classes;
}


/**
 * Document title (<title></title>)
 */
function kct_doc_title( $title ) {
	global $page, $paged;

	$sep = apply_filters( 'kct_doc_title_sep', '&laquo;' );
	$seplocation = apply_filters( 'kct_doc_title_seplocation', 'right' );
	$pg_sep = apply_filters( 'kct_doc_title_pagenum_sep', '|' );
	$home_sep = apply_filters( 'kct_doc_title_home_sep', '&mdash;' );

	$site_name = get_bloginfo( 'name', 'display' );
	$site_desc = get_bloginfo( 'description', 'display');
	$page_num = ( $paged >= 2 || $page >= 2 ) ? " ${pg_sep} " . sprintf( __('Page %s', 'activist'), max($paged, $page) ) : '';

	# Homepage
	if ( is_home() || is_front_page() ) {
		$title = $site_name;
		if ( $site_desc )
			$title .= " ${home_sep} ${site_desc}";
		$title .= $page_num;
	} else {
		if ( $seplocation == 'right' )
			$title = "${title} ${page_num} ${sep} ${site_name}";
		else
			$title = "${site_name} ${sep} ${title} ${page_num}";
	}

	return $title;
}


# <head /> stuff
function kct_head_stuff() {
	?>
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<?php wp_print_scripts( array('html5', 'html5-print') ) ?>
<![endif]-->
	<?php
}


/**
 * Paginate Links on index pages
 */
function kct_page_navi( $query = null, $echo = true ) {
	if ( !$query ) {
		global $wp_query;
		$query = $wp_query;
	}

	if ( !is_object($query) )
		return false;

	$current = max( 1, $query->query_vars['paged'] );
	$big = 999999999;

	$pagination = array(
		'base'    => str_replace( $big, '%#%', get_pagenum_link($big) ),
		'format'  => '',
		'total'   => $query->max_num_pages,
		'current' => $current,
		'type'    => 'list'
	);
	$links = paginate_links($pagination);

	if ( empty($links) )
		return false;

	if ( $echo ) :
	?>
		<nav class="kc-page-navi">
			<?php echo $links ?>
		</nav>
	<?php
	else :
		return $links;
	endif;
}


/**
 * Get comments number of a post
 *
 * @param $post_id int Post ID
 * @param $type Comments type. ''|pings|comment|pingback|trackback Empty string for all types (default)
 *
 * @return int Comments number
 */
function kct_get_comments_count( $post_id = 0, $type = '' ) {
	return count(get_comments(array(
		'post_id' => $post_id,
		'status'  => 'approve',
		'type'    => $type
	)));
}


/**
 * Response list (comments & pings)
 */
function kct_response_list( $post_id = 0 ) {
	if ( !$post_id ) {
		global $post;
		if ( !is_object($post) )
			return;

		$post_id = $post->ID;
	}

	foreach ( array('comment' => __('Comments', 'activist'), 'pings' => __('Pings', 'activist')) as $type => $title ) {
		if ( !kct_get_comments_count($post_id, $type) )
			continue; ?>
	<?php do_action( 'tha_comments_before', $type ) ?>
	<h2 id="<?php echo $type ?>-title"><?php echo apply_filters( "kct_{$type}_list_title", $title, $post_id ) ?></h2>

	<?php do_action( 'tha_comments_list_before', $type ) ?>
	<ol id="<?php echo $type ?>list" class="responselist">
		<?php wp_list_comments( array('callback' => "kct_{$type}_list", 'type' => $type) ); ?>
	</ol>
	<?php do_action( 'tha_comments_list_after', $type ) ?>

	<?php do_action( 'tha_comments_after', $type ) ?>
	<?php }
}


/**
 * Comments list
 */
function kct_comment_list( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<?php do_action( 'tha_comment_top', $comment ) ?>
		<article id="comment-<?php comment_ID(); ?>" class="comment-item">
			<footer>
				<?php do_action( 'tha_comment_header_top', $comment ) ?>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, apply_filters( 'kct_comment_avatar_size', 48) ); ?>
					<cite class="fn"><?php comment_author_link() ?></cite>
				</div>

				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>" class="comment-date"><?php printf( __( '%1$s at %2$s', 'activist' ), get_comment_date(), get_comment_time() ); ?></a>
					<?php comment_reply_link( array_merge($args, array(
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'before'    => '<span class="reply-link"> &ndash; ',
						'after'     => '</span>'
					)) ); ?>
					<?php edit_comment_link( __('Edit', 'activist'), ' &ndash; ' ); ?>
				</div>
				<?php do_action( 'tha_comment_header_bottom', $comment ) ?>
			</footer>

			<div class="comment-content">
				<?php do_action( 'tha_comment_content_top', $comment ) ?>
				<?php
					if ( $comment->comment_approved == '0' )
						echo '<p><em>'.__( 'Your comment is awaiting moderation.', 'activist' ).'</em></p>';
					comment_text();
				?>
			</div>
			<?php do_action( 'tha_comment_content_bottom', $comment ) ?>
		</article>
		<?php do_action( 'tha_comment_bottom', $comment ) ?>
	<?php
}


/**
 * Pings list
 */
function kct_pings_list( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<?php do_action( 'tha_comment_content_top', $comment ) ?>
		<?php comment_author_link(); ?><?php edit_comment_link( __('Edit', 'activist'), ' | ' ); ?>
		<?php do_action( 'tha_comment_content_bottom', $comment ) ?>
<?php }


/**
 * Comment form fields
 */
function kct_comment_form_fields( $fields ) {
	$commenter = wp_get_current_commenter();
	$user = wp_get_current_user();
	$user_identity = ! empty( $user->ID ) ? $user->display_name : '';

	$req = get_option( 'require_name_email' );
	$aria_req = ($req ? " aria-required='true'" : '');

	$fields['author'] = '<p class="comment-form-author">' . '<label for="author">' . __('Name', 'activist') . ($req ? ' <span class="required">*</span>' : '')  . '</label>'.
                      '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';
	$fields['email']  = '<p class="comment-form-email"><label for="email">' . __('Email', 'activist') . ($req ? ' <span class="required">*</span>' : '') . '</label> ' .
                      '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';
	$fields['url']    = '<p class="comment-form-url"><label for="url">' . __('Website', 'activist') . '</label>' .
                      '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>';

	return $fields;
}


/**
 * wp_list_pages() CSS Classes
 *
 * Append some necessary CSS classes to page lists items
 * outputted by wp_list_pages() when used for a custom post type
 */
function kct_list_pages_css_class( $css_class, $page, $depth, $args, $current_page ) {
	if ( empty($args['post_type']) || !is_singular($args['post_type']) )
		return $css_class;

	if ( !empty($current_page) ) {
		$_current_page = get_post( $current_page );
		if ( in_array( $page->ID, $_current_page->ancestors ) )
			$css_class[] = 'current_page_ancestor';
		if ( $page->ID == $current_page )
			$css_class[] = 'current_page_item';
		elseif ( $_current_page && $page->ID == $_current_page->post_parent )
			$css_class[] = 'current_page_parent';
	} elseif ( $page->ID == get_option('page_for_posts') ) {
		$css_class[] = 'current_page_parent';
	}

	return $css_class;
}


/**
 * Get nav menu ID by theme location
 *
 * @param string $location Theme location
 * @return bool|int Menu ID. False on failure
 */
function kct_get_menu_by_location( $location ) {
	$menu_id = false;
	if (
		( $locations = get_nav_menu_locations() )
		&& isset( $locations[$location] )
		&& ( $menu = wp_get_nav_menu_object( $locations[$location] ) )
		&& !is_wp_error( $menu )
	)
		$menu_id = $menu->term_id;

	return $menu_id;
}
