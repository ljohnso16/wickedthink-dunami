<?php

/**
 * Custom amendments for the theme.
 *
 * @category   Genesis_Sandbox
 * @package    Functions
 * @subpackage Functions
 * @author     Travis Smith and Jonathan Perez
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link       http://surefirewebservices.com/
 * @since      1.1.0
 */

// Initialize Sandbox ** DON'T REMOVE **
require_once( get_stylesheet_directory() . '/lib/init.php');

add_action( 'genesis_setup', 'gs_theme_setup', 15 );

//Theme Set Up Function
function gs_theme_setup() {
	
	//Enable HTML5 Support
	add_theme_support( 'html5' );

	//Enable Post Navigation
	add_action( 'genesis_after_entry_content', 'genesis_prev_next_post_nav', 5 );

	/** 
	 * 01 Set width of oEmbed
	 * genesis_content_width() will be applied; Filters the content width based on the user selected layout.
	 *
	 * @see genesis_content_width()
	 * @param integer $default Default width
	 * @param integer $small Small width
	 * @param integer $large Large width
	 */
	$content_width = apply_filters( 'content_width', 600, 430, 920 );
	
	//Custom Image Sizes
	add_image_size( 'featured-image', 225, 160, TRUE );

	// Enable Custom Header
	add_theme_support('genesis-custom-header',array(
		'width' => 400,
		'height' => 81
	));


	// Add support for structural wraps
	add_theme_support( 'genesis-structural-wraps', array(
		'header',
		'nav',
		'subnav',
		'inner',
		'footer-widgets',
		'footer'
	) );

	//* Reposition the primary navigation menu
	remove_action( 'genesis_after_header', 'genesis_do_nav' );
	add_action( 'genesis_header', 'genesis_do_nav', 12 );
	add_filter( 'genesis_seo_title', 'child_header_title', 10, 3 );


	/**
	 * 07 Footer Widgets
	 * Add support for 3-column footer widgets
	 * Change 3 for support of up to 6 footer widgets (automatically styled for layout)
	 */
	add_theme_support( 'genesis-footer-widgets', 3 );

	/**
	 * 08 Genesis Menus
	 * Genesis Sandbox comes with 4 navigation systems built-in ready.
	 * Delete any menu systems that you do not wish to use.
	 */
	add_theme_support(
		'genesis-menus', 
		array(
			'primary'   => __( 'Primary Navigation Menu', CHILD_DOMAIN ), 
			'about'     => __( 'Navigation Menu Shows in About Page', CHILD_DOMAIN ),
			'footer'    => __( 'Footer Navigation Menu', CHILD_DOMAIN ),
			'mobile'    => __( 'Mobile Navigation Menu', CHILD_DOMAIN ),
		)
	);
	
	// Add Mobile Navigation
	add_action( 'genesis_before', 'gs_mobile_navigation', 5 );
	
	//Enqueue Sandbox Scripts
	add_action( 'wp_enqueue_scripts', 'gs_enqueue_scripts' );
	
	/**
	 * 13 Editor Styles
	 * Takes a stylesheet string or an array of stylesheets.
	 * Default: editor-style.css 
	 */
	//add_editor_style();
	
	
	// Register Sidebars
	gs_register_sidebars();
		//Disable all emoji's
	function disable_wp_emojicons() {

	  // all actions related to emojis
	  remove_action( 'admin_print_styles', 'print_emoji_styles' );
	  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	  remove_action( 'wp_print_styles', 'print_emoji_styles' );
	  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	}
	add_action( 'init', 'disable_wp_emojicons' );

	remove_filter( 'the_content', 'wpautop' );
	remove_filter( 'the_excerpt', 'wpautop' );
	
	// add_filter( 'genesis_attr_site-header', 'themeprefix_primary_nav_id' );
	// function themeprefix_primary_nav_id( $attributes ) {
	 
	//  $attributes['class'] .= ' navbar navbar-default navbar-fixed-top';
	//  return $attributes;
	// }	

} // End of Set Up Function
//* Modify the header URL - HTML5 Version
function child_header_title( $title, $inside, $wrap ) {
    $inside = sprintf( '<a href="'.home_url('/').'" title="%s"><img alt="'.get_bloginfo( 'name' ).'" src="' . get_stylesheet_directory_uri() . '/images/logo.png" /></a>', esc_attr( get_bloginfo( 'name' ) ), get_bloginfo( 'name' ) );
    return sprintf( '<%1$s class="site-title">%2$s</%1$s>', $wrap, $inside );
}

add_action( 'genesis_after_header', 'dunami_header_sliders' );
function dunami_header_sliders() {
    $post_id = get_the_ID();
	$ourshortcode = types_render_field('solil-shortcode', array('id' => $post_id, 'show_name' => false, 'output' => 'raw'));	
	if(!empty($ourshortcode)){
		echo do_shortcode('[soliloquy slug="'.$ourshortcode.'"]');
	}
	else
		return;

} 

// Register Sidebars
function gs_register_sidebars() {
	$sidebars = array(
		array(
			'id'			=> 'home-top',
			'name'			=> __( 'Home Top', CHILD_DOMAIN ),
			'description'	=> __( 'This is the top homepage section.', CHILD_DOMAIN ),
		),
		array(
			'id'			=> 'home-middle-01',
			'name'			=> __( 'Home Left Middle', CHILD_DOMAIN ),
			'description'	=> __( 'This is the homepage left section.', CHILD_DOMAIN ),
		),
		array(
			'id'			=> 'home-middle-02',
			'name'			=> __( 'Home Middle Middle', CHILD_DOMAIN ),
			'description'	=> __( 'This is the homepage middle section.', CHILD_DOMAIN ),
		),
		array(
			'id'			=> 'home-middle-03',
			'name'			=> __( 'Home Right Middle', CHILD_DOMAIN ),
			'description'	=> __( 'This is the homepage right section.', CHILD_DOMAIN ),
		),
		array(
			'id'			=> 'home-bottom',
			'name'			=> __( 'Home Bottom', CHILD_DOMAIN ),
			'description'	=> __( 'This is the homepage right section.', CHILD_DOMAIN ),
		),
		array(
			'id'			=> 'portfolio',
			'name'			=> __( 'Portfolio', CHILD_DOMAIN ),
			'description'	=> __( 'Use featured posts to showcase your portfolio.', CHILD_DOMAIN ),
		),
		array(
			'id'			=> 'after-post',
			'name'			=> __( 'After Post', CHILD_DOMAIN ),
			'description'	=> __( 'This will show up after every post.', CHILD_DOMAIN ),
		),
	);
	
	foreach ( $sidebars as $sidebar )
		genesis_register_sidebar( $sidebar );
}

/**
 * Enqueue and Register Scripts - Twitter Bootstrap, Font-Awesome, and Common.
 */
require_once('lib/scripts.php');

/**
 * Add navigation menu 
 * Required for each registered menu.
 * 
 * @uses gs_navigation() Sandbox Navigation Helper Function in gs-functions.php.
 */

//Add Mobile Menu
function gs_mobile_navigation() {
	
	$mobile_menu_args = array(
		'echo' => true,
	);
	
	gs_navigation( 'mobile', $mobile_menu_args );
}

// Add Widget Area After Post
add_action('genesis_after_entry', 'gs_do_after_entry');
function gs_do_after_entry() {
 	if ( is_single() ) {
 	genesis_widget_area( 
                'after-post', 
                array(
                        'before' => '<aside id="after-post" class="after-post"><div class="home-widget widget-area">', 
                        'after' => '</div></aside><!-- end #home-left -->',
                ) 
        );
 }
 }
