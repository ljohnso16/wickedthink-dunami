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
	//gs_register_sidebars();
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
	
	//Removes individual page title
	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

	
	add_action('genesis_footer', 'smb_footer', 5);
	add_filter('genesis_footer_creds_text', 'sp_footer_creds_filter');	
	
} // End of Set Up Function
//* Modify the header URL - HTML5 Version
function child_header_title( $title, $inside, $wrap ) {
    $inside = sprintf( '<a href="'.home_url('/').'" title="%s"><img alt="'.get_bloginfo( 'name' ).'" src="' . get_stylesheet_directory_uri() . '/images/logo.png" /></a>', esc_attr( get_bloginfo( 'name' ) ), get_bloginfo( 'name' ) );
    return sprintf( '<%1$s class="site-title">%2$s</%1$s>', $wrap, $inside );
}
//adds sliders based on the slug given on each page
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
function dunami_social_icons(){
	echo '<a href="#" class="footer-logo"><img alt="" src="' . get_stylesheet_directory_uri() . '/images/facebook.png" /></a>
<a href="#" class="footer-logo"><img alt="" src="' . get_stylesheet_directory_uri() . '/images/twitter.png" /></a>
<a href="#" class="footer-logo"><img alt="" src="' . get_stylesheet_directory_uri() . '/images/linkedin.png" /></a>
<a href="#" class="footer-logo"><img alt="" src="' . get_stylesheet_directory_uri() . '/images/gplus.png" /></a>
<a href="#" class="footer-logo"><img alt="" src="' . get_stylesheet_directory_uri() . '/images/ytube.png" /></a>';
}
function smb_footer() {
	echo wp_nav_menu( array(
	                 'theme_location'  => 'footer',
	                 'container_class' => 'col-md-6',
	                 'items_wrap'      => '<ul id="menu-footer" class="menu"><h4>More from Dunami</h4>%3$s</ul>'
	)).'<div class="col-md-6">'.dunami_social_icons().'</div><div class="clearfix"></div>';
}



function sp_footer_creds_filter( $creds ) {
	$location = '<span class="footer-links"><a href="#">Privacy Policy</a> <a href="#">Legal</a> <a href="#">Site Feedback</a> </span>';
	$creds = $location . '<span class="footer-copyright">Dunami [footer_copyright] </span>';
	return $creds;
}

add_action('genesis_before_footer', 'gs_do_before_footer');
function gs_do_before_footer() {
	$post_id = get_the_ID();
	$staticsection1 = types_render_field('extra-section-1', array('id' => $post_id, 'show_name' => false, 'output' => 'raw'));	
	if(!empty($staticsection1)){
		echo '<div id="static-section-1-area"><div class="area-wrap">'.$staticsection1.'</div></div>';
	}
	$staticsection2 = types_render_field('extra-section-2', array('id' => $post_id, 'show_name' => false, 'output' => 'raw'));	
	if(!empty($staticsection2)){
		echo '<div id="static-section-2-area"><div class="wrap">'.$staticsection2.'</div></div>';
	}		
	$staticsection3 = types_render_field('extra-section-3', array('id' => $post_id, 'show_name' => false, 'output' => 'raw'));	
	if(!empty($staticsection3)){
		echo '<div id="static-section-3-area"><div class="area-wrap">'.$staticsection3.'</div></div>';
	}
	$staticsection4 = types_render_field('extra-section-4', array('id' => $post_id, 'show_name' => false, 'output' => 'raw'));	
	if(!empty($staticsection4)){
		echo '<div id="static-section-4-area"><div class="wrap">'.$staticsection4.'</div></div>';
	}
	$staticsection5 = types_render_field('extra-section-5', array('id' => $post_id, 'show_name' => false, 'output' => 'raw'));		
	if(!empty($staticsection5)){
		echo '<div id="static-section-5-area"><div class="area-wrap">'.$staticsection5.'</div></div>';
	}	
	echo '<div id="lets-talk-footer"><div class="wrap"><h2>Let\'s Talk</h2><p>Stop focusing on <span>WHAT</span>, start knowing <span>WHO</span> matters.<br />Scedule your non-obligation demo today and see how the Dunami platform<br />can powerfully impact your organization</p><a class="dunami-effect btn btn-default" href="#" title="">Schedule Demo</a></div></div>';			
}
add_shortcode('focus-who-matters','generate_focus_how_matters');
function generate_focus_how_matters(){
return '
<div id="who-matters" class="dunami-carousel-custom carousel slide" data-ride="carousel">
    <div class="carousel-outer">
        <!-- Wrapper for slides -->
        <div class="carousel-inner">

            <div class="id-key-influences item active">
                <div class="col-md-3">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Identify Key Influencers</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" /></div><div class="clearfix"></div>
            </div>


            <div class="item ignore-irrelevant">
                <div class="col-md-3">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Ignore The Irrelevant</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" /></div><div class="clearfix"></div>
            </div>
            <div class="item see-connections">
                <div class="col-md-3">&nbsp;</div>
                <div class="text col-md-4">
					<h2>See The Connections</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" /></div><div class="clearfix"></div>                
            </div>
            <div class="item discover-networks">
                <div class="col-md-3">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Discover Critical Networks</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" /></div><div class="clearfix"></div>                
            </div>            
        </div>
        <!-- Controls -->
        <div class="controls">
	        <a class="" href="#who-matters" data-slide="prev">
	            <span class="pull-left glyphicon glyphicon-chevron-left"></span>
	        </a>
	        <a class="" href="#who-matters" data-slide="next">
	            <span class="pull-right glyphicon glyphicon-chevron-right"></span>
	        </a>
	        <div class=clearfix"></div>
		</div>        
    </div>    
    <!-- Indicators -->

    
	<div class="indicator-row">
		<div class="title">Focus On <br />Who Matters</div>	        
	    <ol class="carousel-indicators">	   	    
		        <li data-target="#who-matters" data-slide-to="0" class="indicator active">Identify <br />Key Influencers</li>
		        <li data-target="#who-matters" data-slide-to="1" class="indicator">Ignore<br />The Irrelevant</li>
		        <li data-target="#who-matters" data-slide-to="2" class="indicator">See The<br /> Connections</li>
		        <li data-target="#who-matters" data-slide-to="3" class="indicator">Discover<br /> Critical Networks</li>
	    </ol>
	    <div class="space">&nbsp;</div>
    </div>
</div>
';
}

add_shortcode('success-stories','generate_success_stories');
function generate_success_stories(){
return '
<div id="success-stories" class="dunami-carousel-custom carousel slide" data-ride="carousel">
    <div class="carousel-outer">
        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item active">
            	<p class="head-title">Success Stories from those using Dunami</p>
            	<p class="quote">"Praesent sapien massa, convallis a pellentesque nec, egestas non nisi..."</p>
            	<p class="person">Amazon Represenative</p>
            </div>
            <div class="item">
            	<p class="head-title">Success Stories from those using Dunami</p>
            	<p class="quote">"Praesent sapien massa, convallis a pellentesque nec, egestas non nisi..."</p>
            	<p class="person">NBC Represenative</p>
            </div>
            <div class="item">
            	<p class="head-title">Success Stories from those using Dunami</p>
            	<p class="quote">"Praesent sapien massa, convallis a pellentesque nec, egestas non nisi..."</p>
            	<p class="person">Coca-Cola Represenative</p>
            </div>
            <div class="item">
            	<p class="head-title">Success Stories from those using Dunami</p>
            	<p class="quote">"Praesent sapien massa, convallis a pellentesque nec, egestas non nisi..."</p>
            	<p class="person">AT&T Represenative</p>
            </div>
        </div>
    </div>    
	<div class="indicator-row">
	    <div class="space">&nbsp;</div>
	    <ol class="carousel-indicators">	   	    
		        <li data-target="#success-stories" data-slide-to="0" class="indicator active"><img src="'.get_stylesheet_directory_uri().'/images/amazon.png" /></li>
		        <li data-target="#success-stories" data-slide-to="1" class="indicator"><img src="'.get_stylesheet_directory_uri().'/images/nbc.png" /></li>
		        <li data-target="#success-stories" data-slide-to="2" class="indicator"><img src="'.get_stylesheet_directory_uri().'/images/coke.png" /></li>
		        <li data-target="#success-stories" data-slide-to="3" class="indicator"><img src="'.get_stylesheet_directory_uri().'/images/att.png" /></li>
	    </ol>
	    <div class="space">&nbsp;</div>
    </div>
</div>
';
}
add_shortcode('social-media-counter','generate_socialmedia_counter');
function generate_socialmedia_counter(){
return '
<div class="three-row">
	<div class="color">
		<div class="col-md-3 hidden-sm hidden-xs">&nbsp;</div>
		<div class="col-md-2 initial">
			<img src="'.get_stylesheet_directory_uri().'/images/twitter-thumb.png">
			<p class="number">5,700+</p>
			<p class="middle">Tweets happen every second</p>
			<p class="small">241 million active users</p>
		</div>
		<div class="col-md-2 second">
			<img src="'.get_stylesheet_directory_uri().'/images/facebook-thumb.png">
			<p class="number">1,000,000</p>
			<p class="middle">links shared every second</p>
			<p class="small">1+ billion active users</p>			
		</div>
		<div class="col-md-2 third">
			<img src="'.get_stylesheet_directory_uri().'/images/instagram-thumb.png">
			<p class="number">60,000,000</p>
			<p class="middle">pictures uploaded every day</p>
			<p class="small">200 million active users</p>			
		</div>
		<div class="col-md-3 hidden-sm hidden-xs">&nbsp;</div>
	</div>
</div>
';
}
//generate brandmanagment menu
add_shortcode('about-menu', 'print_about_menu');
function print_about_menu($atts, $content = null) {   
    return wp_nav_menu( array(
	                 'echo' => false,
	                 'theme_location'  => 'about',
	                 'container_class' => '',
                     'link_after'      => '<div class="nav-divider"> | </div>'
	                 //'items_wrap'      => '<ul id="menu-footer" class="menu"><h4>More from Dunami</h4>%3$s</ul>'
	));
}


//[how-do-this]
add_shortcode('how-do-this','generate_how_do_this');
function generate_how_do_this(){
return '
<div class="three-row alt-size-a">
<div class="col-md-12 "><h2>How Do We Do This?</h2></div>
	<div class="color">
		<div class="col-md-3 hidden-sm hidden-xs alt-size-b">&nbsp;</div>
		<div class="col-md-2 initial alt-size-b">
			<img src="'.get_stylesheet_directory_uri().'/images/how-1.png">
			<p class="head-title">Patendted Relationship<br/>Network Analytics</p>
			<p class="text">By smartly spanning out across relationships within a specific topic or group, Dunami allows you to know who is (and is not) a meaningful part of a topic of discussion and find the entire network of those who matter of social activity.</p>
		</div>
		<div class="col-md-2 alt-size-b second">
			<img src="'.get_stylesheet_directory_uri().'/images/how-3.png">
			<p class="head-title">Proprietary Influencer<br/>Mathematics</p>
			<p class="text">Dunami breaks through the boundaries of influence measurement to allow you to precisely score the people who are most important within this entire network</p>			
		</div>
		<div class="col-md-2 alt-size-b third">
			<img src="'.get_stylesheet_directory_uri().'/images/how-2.png">
			<p class="head-title">Behavioral Attribute<br />Modeling</p>
			<p class="text">Dinami helps you figure out the interests, activties, demographics, group affiliations, etc., of these people through the application of artifical intelligence learning algorithms.</p>			
		</div>
		<div class="col-md-3 alt-size-b hidden-sm hidden-xs">&nbsp;</div>
	</div>
</div>
';
}
add_shortcode('dunami-leadership','generate_dunami_leadership');
function generate_dunami_leadership(){
	return '
<div class="leardership">
	<div class="row-a">
		<div class="col-md-3 hidden-sm hidden-xs">&nbsp;</div>
		<div class="col-md-2"><img src="'.get_stylesheet_directory_uri().'/images/leadership-1.png"><h2>Tony Marshall</h2><p>President and Chief Technical Officer</p></div>
		<div class="col-md-2"><img src="'.get_stylesheet_directory_uri().'/images/leadership-2.png"><h2>Pat Butler</h2><p>Chief Executive Officer & Chief Scientist</p></div>
		<div class="col-md-2"><img src="'.get_stylesheet_directory_uri().'/images/leadership-3.png"><h2>Andrew Woglom</h2><p>Chief Financial Officer</p></div>
		<div class="col-md-3 hidden-sm hidden-xs">&nbsp;</div>
		<div class="clearfix">&nbsp;</div>
	</div>
	<div class="clearfix">&nbsp;</div>
	<div class="row-b">
		<div class="col-md-4 hidden-xs">&nbsp;</div>
		<div class="col-md-2"><img src="'.get_stylesheet_directory_uri().'/images/leadership-4.png"><h2>Steve Davis</h2><p>Chief Business Development Officer</p></div>
		<div class="col-md-2"><img src="'.get_stylesheet_directory_uri().'/images/leadership-5.png"><h2>Mark Schwalm</h2><p>Executive Vice-President</p></div>
		<div class="col-md-4 hidden-xs">&nbsp;</div>
		<div class="clearfix">&nbsp;</div>
	</div>
</div>
	';
}

add_shortcode('dunami-platform','generate_dunami_platform');
function generate_dunami_platform(){
return '
<div id="dunami-platform" class="dunami-carousel-custom carousel slide" data-ride="carousel">
    <div class="carousel-outer">
        <!-- Wrapper for slides -->
        <div class="carousel-inner">

            <div class="id-key-influences item active">
                <div class="col-md-3">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Identify Key Influencers</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" /></div><div class="clearfix"></div>
            </div>


            <div class="item ignore-irrelevant">
                <div class="col-md-3">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Brand Managment</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" /></div><div class="clearfix"></div>
            </div>
            <div class="item see-connections">
                <div class="col-md-3">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Public Relations</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" /></div><div class="clearfix"></div>                
            </div>
            <div class="item discover-networks">
                <div class="col-md-3">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Corporate Communications</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" /></div><div class="clearfix"></div>                
            </div>
            <div class="item ignore-irrelevant">
                <div class="col-md-3">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Corporate Research</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" /></div><div class="clearfix"></div>
            </div>
            <div class="item see-connections">
                <div class="col-md-3">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Social Media</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" /></div><div class="clearfix"></div>                
            </div>  
        </div>
        <!-- Controls -->
        <div class="controls">
	        <a class="" href="#dunami-platform" data-slide="prev">
	            <span class="pull-left glyphicon glyphicon-chevron-left"></span>
	        </a>
	        <a class="" href="#dunami-platform" data-slide="next">
	            <span class="pull-right glyphicon glyphicon-chevron-right"></span>
	        </a>
	        <div class=clearfix"></div>
		</div>        
    </div>    
    <!-- Indicators -->

    
	<div class="indicator-row">
		<div class="title">The Power<br />of Dunami\'s <br />Platform</div>	        
	    <ol class="carousel-indicators">	   	    
		        <li data-target="#dunami-platform" data-slide-to="0" class="indicator active">Brand<br />Managment</li>
		        <li data-target="#dunami-platform" data-slide-to="1" class="indicator">Public<br /> Relations</li>
		        <li data-target="#dunami-platform" data-slide-to="2" class="indicator">Corporate<br />Communications</li>
		        <li data-target="#dunami-platform" data-slide-to="3" class="indicator">Corporate<br />Research</li>
		        <li data-target="#dunami-platform" data-slide-to="4" class="indicator">Social<br />Media</li>
		        <li data-target="#dunami-platform" data-slide-to="5" class="indicator">Advertising<br />Agencies</li>
	    </ol>
	    <div class="space">&nbsp;</div>
    </div>
</div>
';
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