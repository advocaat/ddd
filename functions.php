<?php
/**
 * templater functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package templater
 */

if ( ! function_exists( 'templater_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function templater_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on templater, use a find and replace
	 * to change 'templater' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'templater', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Header', 'templater' ),
		'social' => esc_html__( 'Social', 'templater'),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'templater_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
	
	
	// Add support for Custom logo
	add_theme_support( 'custom-logo', array(
		'width' => 90,
		'height' => 90,
		'flex-width' => true,
	));	
}
endif;
add_action( 'after_setup_theme', 'templater_setup' );


/**
 * Register custom fonts.
 */
function templater_fonts_url() {
	$fonts_url = '';

	/**
	 * Translators: If there are characters in your language that are not
	 * supported by Lora and Raleway, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$lora = _x( 'on', 'Lora font: on or off', 'templater' );
	$raleway = _x( 'on', 'Raleway font: on or off', 'templater' );
	$font_families = array();

	if ( 'off' !== $lora ) {
		
		$font_families[] = 'Lora:400,400i,700';

	}
	
	if ( 'off' !== $raleway ) {
		
		$font_families[] = 'Raleway:400,400i,700';

	
	}
	if( in_array( 'on', array($lora, $raleway))){
			$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}

/**
 * Add preconnect for Google Fonts.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function templater_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'templater-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}



add_filter( 'wp_resource_hints', 'templater_resource_hints', 10, 2 );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function templater_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'templater_content_width', 640 );
}
add_action( 'after_setup_theme', 'templater_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function templater_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'templater' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'templater' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'templater_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function templater_scripts() {
	wp_enqueue_style('templater-fonts', templater_fonts_url());
	
	wp_enqueue_style( 'templater-style', get_stylesheet_uri() );

	wp_enqueue_script( 'templater-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), '20151215', true );
	wp_localize_script('templater-navigation', 'templaterScreenReaderText', array(
		'expand' => __( 'Expand child menu', 'templater'),
		'collapse' => __( 'Collapse child menu', 'templater'),
	));
	
	
	wp_enqueue_script( 'templater-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'templater_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
