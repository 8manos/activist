<?php

class kcTheme {
	public static $dir_theme;
	public static $url_theme;


	public static function setup() {
		self::$dir_theme = $dir = get_template_directory();
		self::$url_theme = get_template_directory_uri();

		# The libs
		require_once "{$dir}/p/krr.php";
		if ( !function_exists('kc_dropdown_menu') && !is_admin() )
			require_once "{$dir}/p/kc-dropdown-menu.php";

		# Set the content width based on the theme's design and stylesheet.
		global $content_width;
		if ( ! isset( $content_width ) )
			$content_width = 920;

		// Theme Hook Alliance
		define( 'THA_HOOKS_VERSION', '1.0-draft' );
		add_theme_support( 'tha_hooks', array('all') );

		# i18n
		load_theme_textdomain( 'activist', self::$dir_theme . '/l' );

		# Menus
		register_nav_menus( array(
			'main' => __('Header Menu', 'activist'),
			'footer' => __('Footer Menu', 'activist')
		) );

		add_action( 'init', array(__CLASS__, 'defaults') );
		add_action( 'widgets_init', array(__CLASS__, 'register_sidebars') );
		add_action( 'wp_enqueue_scripts', array(__CLASS__, 'sns'), 100 );
		#add_action( 'wp_print_footer_scripts', array(__CLASS__, '_sns_vars'), 9 );
		add_action( 'tha_entry_content_after', array(__CLASS__, 'comments') );
	}


	public static function defaults() {
		add_action( 'wp_head', 'kct_head_stuff', 1 );
		add_filter( 'body_class', 'kct_body_class' );
		add_filter( 'post_class', 'kct_post_class', 10, 3 );
		add_filter( 'comment_form_default_fields', 'kct_comment_form_fields' );
		add_filter( 'page_css_class', 'kct_list_pages_css_class', 10, 5 );

		/* Enable [embed] shortcode in text widgets */
		global $wp_embed;
		add_filter( 'widget_text', array( $wp_embed, 'run_shortcode' ), 8 );
		add_filter( 'widget_text', array( $wp_embed, 'autoembed'), 8 );
		/* Misc */
		add_filter( 'get_frm_stylesheet', '__return_false' );
		if ( !defined('WPSEO_VERSION') )
			add_filter( 'wp_title', 'kct_doc_title' );
	}


	public static function register_sidebars() {
		$sidebars = array(
			'wa-footer' => __('Footer widget area', 'activist'),
		);

		foreach ( $sidebars as $id => $name )
			register_sidebar(array(
				'id'            => $id,
				'name'          => $name,
				'before_widget' => '<aside id="%1$s" class="widget %2$s">'.PHP_EOL,
				'after_widget'  => '</aside>'.PHP_EOL,
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>'
			));
	}


	public static function sns() {
		$ver = '20120915';
		wp_enqueue_style( 'activist', self::$url_theme.'/style.css', false, $ver );

		wp_register_script( 'html5', self::$url_theme.'/j/html5shiv.js', false, '3.6' );
		wp_register_script( 'html5-print', self::$url_theme.'/j/html5shiv-printshiv.js', false, '3.6' );

		#wp_register_script( 'yepnope', self::$url_theme.'/j/yepnope.1.5.4-min.js', false, '1.5.4', true );
		#wp_enqueue_script( 'activist', self::$url_theme.'/j/scripts.js', array('yepnope', 'jquery'), $ver, true );

		if ( is_singular() && post_type_supports(get_post_type(), 'comments') && comments_open() && get_option('thread_comments') )
			wp_enqueue_script( 'comment-reply' );
	}


	public static function _sns_vars() { ?>
<script>
	var kcTheme = <?php echo json_encode(array(
		'locale'   => get_locale(),
		'homeURL'  => home_url(),
		'themeURL' => self::$url_theme,
		'js'       => kc_get_sns( array(), 'js' )
	)) ?>
</script>
	<?php }


	public static function comments() {
		if ( is_singular() && ( get_comments_number() || ( post_type_supports( get_post_type(), 'comments' ) && comments_open() ) ) )
			comments_template( '', true );
	}
}
add_action( 'after_setup_theme', array('kcTheme', 'setup') );
