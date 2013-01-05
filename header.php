<?php
ob_start();
language_attributes();
$lang_attr = ob_get_clean();
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php echo $lang_attr; ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" <?php echo $lang_attr; ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" <?php echo $lang_attr; ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php echo $lang_attr; ?>> <!--<![endif]-->
<head>
	<?php do_action( 'tha_head_top' ); ?>
	<meta charset="<?php bloginfo( 'charset' ) ?>">
	<title><?php wp_title('') ?></title>
	<?php wp_head() ?>
	<?php do_action( 'tha_head_bottom' ); ?>
</head>

<body <?php body_class() ?>>
	<div id="page">
		<?php do_action( 'tha_header_before' ); ?>
		<header id="branding" role="banner">
			<?php do_action( 'tha_header_top' ); ?>
			<hgroup>
				<h1 id="site-title"><a class="no-ajaxy" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><span><?php bloginfo( 'name' ); ?></span></a></h1>
				<?php if ( $site_desc = get_bloginfo('description') ) { ?>
				<h2 id="site-description"><?php echo $site_desc ?></h2>
				<?php } ?>
			</hgroup>
			<?php if ( $menu_id = kct_get_menu_by_location('main') ) : ?>
			<?php wp_nav_menu( array(
					'menu'            => $menu_id,
					'container'       => 'nav',
					'container_id'    => 'main-menu',
					'container_class' => 'menu-container main-menu-container',
					'menu_class'      => 'menu main-menu',
					'fallback_cb'     => false
				) );
			?>
			<?php kc_dropdown_menu( $menu_id, array(
					'menu_id' => 'main-menu-small',
					'submit_text' => __('Go', 'THEME_NAME')
				) );
			?>
			<?php endif; ?>
			<?php do_action( 'tha_header_bottom' ); ?>
		</header>
		<?php do_action( 'tha_header_after' ); ?>