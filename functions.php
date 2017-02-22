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
	$staticsection0 = types_render_field('extra-section-0', array('id' => $post_id, 'show_name' => false, 'output' => 'raw'));
	if(!empty($ourshortcode)){
		echo do_shortcode('[soliloquy slug="'.$ourshortcode.'"]');
	}
	if(!empty($staticsection0)){
		echo '<div id="static-section-0-area"><div class="area-wrap">'.$staticsection0.'</div></div>';
	}
}
function dunami_social_icons(){
	return '<div class="social-icons"><a href="#" class="footer-logo"><img alt="Facebook Logo" src="' . get_stylesheet_directory_uri() . '/images/facebook.png" /></a>
<a href="#" class="footer-logo"><img alt="Twitter Logo" src="' . get_stylesheet_directory_uri() . '/images/twitter.png" /></a>
<a href="#" class="footer-logo"><img alt="Linkedin Logo" src="' . get_stylesheet_directory_uri() . '/images/linkedin.png" /></a>
<a href="#" class="footer-logo"><img alt="Google+ Logo" src="' . get_stylesheet_directory_uri() . '/images/gplus.png" /></a>
<a href="#" class="footer-logo"><img alt="YouTube Logo" src="' . get_stylesheet_directory_uri() . '/images/ytube.png" /></a></div>';
}
function smb_footer() {
	echo '<div>'.wp_nav_menu( array(
	                 'theme_location'  => 'footer',
	                 'echo' 		   =>  false,
	                 'container_class' => 'col-md-6',
	                 'items_wrap'      => '<ul id="menu-footer" class="menu"><h4>More from Dunami</h4>%3$s</ul>'
	)).'<div class="col-md-6">'.dunami_social_icons().do_shortcode('[gravityform id=2 title=false description=false ajax=false]').'<div class="clearfix"></div></div><div class="clearfix"></div></div>';
}



function sp_footer_creds_filter( $creds ) {
	$location = '<div class="clearfix"></div><div class="footer-links col-md-12"><a href="#">Privacy Policy</a> <a href="#">Legal</a> <a href="#">Site Feedback</a> </div>';
	$creds = $location . '<div class="footer-copyright col-md-12">Dunami [footer_copyright] </div>';
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
	//mega footer here
	echo '<div id="lets-talk-footer"><div class="wrap"><h2>Let\'s Talk</h2><p>Stop focusing on <span>WHAT</span>, start knowing <span>WHO</span> matters.<br />Schedule your non-obligation demo today and see how the Dunami platform<br />can powerfully impact your organization</p>    <a class="dunami-effect btn btn-default" href="#" title="" data-toggle="modal" data-target="#schedule-demo">Schedule Demo</a></div></div>';			
	//Modal/Lightbox for Schedule Demo Here
	echo '
		  <!-- Modal -->
		  <div class="modal fade" id="schedule-demo" role="dialog">
		    <div class="modal-dialog modal-lg">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		        </div>
		        <div class="modal-body">
		          '.do_shortcode('[gravityform id="4" title="false" description="false" ajax="true"]').'
		        </div>
		      </div>		      
		    </div>
		  </div>
	';
	//Modal/Lightbox for yTube video
	echo '
		  <!-- Modal -->
		  <div class="modal fade" id="youtube" role="dialog">
		    <div class="modal-dialog modal-lg">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		        </div>
		        <div class="modal-body">
					<h3 style="text-align: center;">The Dunami Difference:
					Why who is <span>better</span> than <span>what</span>.</h3>
					Cras ultricies ligula sed magna dictum porta. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Curabitur aliquet quam id dui posuere blandit. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui.
					<p style="text-align: center;"><img src="http://dunami.staging.wpengine.com/wp-content/uploads/2017/02/ytube-video.png" alt="Dunami YouTube Video Link"></p>
					<h3 style="text-align: center;">We know who matters.</h3>
					<h3 style="text-align: center;">Learn more about what Dunami can do for you.</h3>
					<p style="text-align: center;"><a class="dunami-effect btn btn-default video-link" href="#" title="" data-toggle="modal" data-target="#schedule-demo">Schedule Demo</a></p>
		        </div>
		      </div>		      
		    </div>
		  </div>
	';	
}
add_shortcode('focus-who-matters','generate_focus_how_matters');
function generate_focus_how_matters(){
return '
<div id="who-matters" class="dunami-carousel-custom carousel slide" data-ride="carousel">
    <div class="carousel-outer">
        <!-- Wrapper for slides -->
        <div class="carousel-inner">

            <div class="id-key-influences item active">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Identify Key Influencers</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>
            </div>


            <div class="item ignore-irrelevant">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Ignore The Irrelevant</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>
            </div>
            <div class="item see-connections">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>See The Connections</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>                
            </div>
            <div class="item discover-networks">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Discover Critical Networks</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>                
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
		<div class="title"><div><span>Focus On </span><br /><span>Who Matters</span></div></div>	        
		<div class="triangle"></div>	        
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
add_shortcode('sucess-stories','generate_success_stories');
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
            	<p class="quote">"Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Nulla porttitor accumsan tincidunt...."</p>
            	<p class="person">AT&T Represenative</p>
            </div>
        </div>
    </div>    
	<div class="indicator-row">
		<div class="success-triangle">&nbsp;</div>
	    <div class="space"></div>
	    <ol class="carousel-indicators">	   	    
		        <li data-target="#success-stories" data-slide-to="0" class="indicator success-indicator active"><img src="'.get_stylesheet_directory_uri().'/images/amazon.png" alt="Amazon Logo" /></li>
		        <li data-target="#success-stories" data-slide-to="1" class="indicator success-indicator"><img src="'.get_stylesheet_directory_uri().'/images/nbc.png" alt="NBC Logo"/></li>
		        <li data-target="#success-stories" data-slide-to="2" class="indicator success-indicator"><img src="'.get_stylesheet_directory_uri().'/images/coke.png" alt="Coco Cola Logo" /></li>
		        <li data-target="#success-stories" data-slide-to="3" class="indicator success-indicator"><img src="'.get_stylesheet_directory_uri().'/images/att.png" alt="AT&T Logo"/></li>
	    </ol>
	    <div class="space"></div>
    </div>
</div>
';
}
add_shortcode('social-media-counter','generate_socialmedia_counter');
function generate_socialmedia_counter(){
return '
<div class="three-row">
	<div class="color">
		<div class="three-row-padder">&nbsp;</div>
		<div class="initial">
			<span class="icon"><i class="fa fa-twitter" aria-hidden="true"></i>
			<p>TWITTER</p></span>
			<p class="number">5,700+</p>
			<p class="middle">Tweets happen every second</p>
			<p class="small">241 million active users</p>
		</div>
		<div class="second">
			<span class="icon">
				<i class="fa fa-facebook-official" aria-hidden="true"></i>
				<p>FACEBOOK</p>
			</span>
			<p class="number">1,000,000</p>
			<p class="middle">links shared every second</p>
			<p class="small">1+ billion active users</p>			
		</div>
		<div class="third">
			<span class="icon">
				<i class="fa fa-instagram" aria-hidden="true"></i>
				<p>INSTAGRAM</p>
			</span>
			<p class="number">60,000,000</p>
			<p class="middle">pictures uploaded every day</p>
			<p class="small">200 million active users</p>			
		</div>
		<div class="three-row-padder">&nbsp;</div>
	</div>
</div>
';
}
//generate abouts sub menu
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
		<div class="three-row-padder">&nbsp;</div>
		<div class="initial alt-size-b">
			<img src="'.get_stylesheet_directory_uri().'/images/how-1.png" alt="How We Do This Graph Icon">
			<p class="head-title">Patendted Relationship<br/>Network Analytics</p>
			<p class="text">By smartly spanning out across relationships within a specific topic or group, Dunami allows you to know who is (and is not) a meaningful part of a topic of discussion and find the entire network of those who matter of social activity.</p>
		</div>
		<div class="alt-size-b second">
			<img src="'.get_stylesheet_directory_uri().'/images/how-3.png" alt="How We Do This Graph Icon">
			<p class="head-title">Proprietary Influencer<br/>Mathematics</p>
			<p class="text">Dunami breaks through the boundaries of influence measurement to allow you to precisely score the people who are most important within this entire network</p>			
		</div>
		<div class="alt-size-b third">
			<img src="'.get_stylesheet_directory_uri().'/images/how-2.png" alt="How We Do This Graph Icon">
			<p class="head-title">Behavioral Attribute<br />Modeling</p>
			<p class="text">Dinami helps you figure out the interests, activties, demographics, group affiliations, etc., of these people through the application of artifical intelligence learning algorithms.</p>		
		</div>
		<div class="three-row-padder">&nbsp;</div><div class="clearfix"></div>		
	</div>
</div>
';
}
add_shortcode('dunami-leadership','generate_dunami_leadership');
function generate_dunami_leadership(){
	return '<div class="clearfix">&nbsp;</div>
<div class="leardership">
	<div class="row-a">
		
		<div class="leader"><img src="'.get_stylesheet_directory_uri().'/images/leadership-1.png" alt="Tony Marhsall\'s Photo"><h2>Tony Marshall</h2><p>President and Chief Technical Officer</p></div>
		<div class="leader"><img src="'.get_stylesheet_directory_uri().'/images/leadership-2.png" alt="Pat Butler\'s Photo"><h2>Pat Butler</h2><p>Chief Executive Officer & Chief Scientist</p></div>
		<div class="leader"><img src="'.get_stylesheet_directory_uri().'/images/leadership-3.png" alt="Andrew Woglom\'s Photo"><h2>Andrew Woglom</h2><p>Chief Financial Officer</p></div>
		
		<div class="clearfix">&nbsp;</div>
	</div>
	<div class="clearfix">&nbsp;</div>
	<div class="row-b">
		
		<div class="leader"><img src="'.get_stylesheet_directory_uri().'/images/leadership-4.png" alt="Steve Davis\'s Photo"><h2>Steve Davis</h2><p>Chief Business Development Officer</p></div>
		<div class="leader"><img src="'.get_stylesheet_directory_uri().'/images/leadership-5.png" alt="Mark Schwalm\'s Photo"><h2>Mark Schwalm</h2><p>Executive Vice-President</p></div>
		
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

            <div class="brand-management item active">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>brand Managment</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>
            </div>


            <div class="item public-relations">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Public Relations</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>
            </div>            
            <div class="item corporate">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Corporate Communications</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>                
            </div>
            <div class="item ignore-irrelevant">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Corporate Research</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>
            </div>
            <div class="item" id="hands-accross-table">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Advertising Agencies</h2>
					<p>The Dunami Platform has the ability not only find, but also focus on, key networks and their associated leaders, activist, and influencers. These influencers are the very unieqe and specific, individuals likely to drive the next brand event, critical action or brewing crisis.</p><a class="dunami-effect btn btn-default reverse" href="#" title="">Learn More</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>                
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
		<div class="title"><div><span>The Power<br />of Dunami\'s <br />Platform<span></div></div>	        
	    <div class="triangle"></div>
	    <ol class="carousel-indicators">	   	    
		        <li data-target="#dunami-platform" data-slide-to="0" class="indicator active">Brand<br />Managment</li>
		        <li data-target="#dunami-platform" data-slide-to="1" class="indicator">Public<br /> Relations</li>
		        <li data-target="#dunami-platform" data-slide-to="2" class="indicator">Corporate<br />Communications</li>
		        <li data-target="#dunami-platform" data-slide-to="3" class="indicator">Corporate<br />Research</li>
		        <li data-target="#dunami-platform" data-slide-to="4" class="indicator">Advertising<br />Agencies</li>
	    </ol>
	    <div class="space">&nbsp;</div>
    </div>
</div>
';
}


add_shortcode('brand-management','generate_brand_management');
function generate_brand_management(){
return '
<div id="managment-solutions" class="dunami-carousel-custom carousel slide" data-ride="carousel">
    <div class="carousel-outer">
        <!-- Wrapper for slides -->
        <div class="carousel-inner">

            <div class="item brand-awareness active">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Brand Loyalty</h2>
					<p>The Dunami gives you the power to identify and specifically message to those who are loyal to your brand. Connect, engage and build relationships with those best poised to become your brand ambassadors</p><a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>
            </div>


            <div class="item brand-awareness">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Brand Awareness</h2>
					<p>Dunami allows real-time analyzing of who is aware of your brand and interacting with it via social media and other avenues.</p><a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>
            </div>
            <div class="item brand-awareness">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Brand Messaging</h2>
					<p>Understanding the real impact on your brand of specific brand messages. Dunami allows you to collect feedback and better target key ideas to the right people.</p><a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>                
            </div>
            <div class="item brand-awareness">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Brand Positioning & Orientation</h2>
					<p>The Dunami gives you the power to identify and specifically message to those who are loyal to your brand. Connect, engage and build relationships with those best poised to become your brand ambassadors</p><a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>                
            </div>            
        </div>
        <!-- Controls -->
        <div class="controls">
	        <a class="" href="#managment-solutions" data-slide="prev">
	            <span class="pull-left glyphicon glyphicon-chevron-left"></span>
	        </a>
	        <a class="" href="#managment-solutions" data-slide="next">
	            <span class="pull-right glyphicon glyphicon-chevron-right"></span>
	        </a>
	        <div class=clearfix"></div>
		</div>        
    </div>    
    <!-- Indicators -->

    
	<div class="indicator-row">
		<div class="title"><div><span>Dunami Brand <br />Managment<br />Solutions</span></div></div>
		<div class="triangle"></div>	        
	    <ol class="carousel-indicators">	   	    
		        <li data-target="#managment-solutions" data-slide-to="0" class="indicator active">Brand <br />Loyalty</li>
		        <li data-target="#managment-solutions" data-slide-to="1" class="indicator">Brand<br />Awareness</li>
		        <li data-target="#managment-solutions" data-slide-to="2" class="indicator">Brand<br />Messaging</li>
		        <li data-target="#managment-solutions" data-slide-to="3" class="indicator">Brand<br />Positioning<br />& Orientation</li>
	    </ol>
	    <div class="space">&nbsp;</div>
    </div>
</div>
';
}
//menu on brand-management page
add_shortcode('brandmanagement-menu','generate_brand_management_menu');
function generate_brand_management_menu(){
return '
<div id="brandmanagement-menu" class="dunami-overview-menu carousel slide" data-ride="carousel" data-interval="false">
    <!-- Indicators -->
	<div class="indicator-row">
	    <div class="space">&nbsp;</div>
	    <ol class="carousel-indicators">	   	    
		        <li data-target="#brandmanagement-menu" data-slide-to="0" class="non-indicator active"><span>Overview</span></li>
		        <li data-target="#brandmanagement-menu" data-slide-to="1" class="non-indicator"><span>Solutions</span></li>
		        <li data-target="#brandmanagement-menu" data-slide-to="2" class="non-indicator"><span>Benefits</span></li>
		        <li data-target="#brandmanagement-menu" data-slide-to="3" class="non-indicator"><span>Client Stories</span></li>
		        <li data-target="#brandmanagement-menu" data-slide-to="4" class="non-indicator"><span>Resources</span></li>
	    </ol>
	    <div class="space">&nbsp;</div>
    </div>    
    <div class="carousel-outer">
        <!-- Wrapper for slides -->
        <div class="carousel-inner wrap">
            <div class="item active">
            	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/brand-management-ico2.png" alt="Brand Managment Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Know More than Ever before. Know it in real time.<br> Assess your brand in the marketplace with powerful tools<br /> to analyze, measure and react.</h2></div>
	            </div>
        	</div>
            <div class="item">
            	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/brand-management-ico2.png" alt="Brand Managment Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Know More than Ever before. Know it in real time.<br> Assess your brand in the marketplace with powerful tools<br /> to analyze, measure and react.</h2></div>
	            </div>
            </div>
            <div class="item">
            	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/brand-management-ico2.png" alt="Brand Managment Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Know More than Ever before. Know it in real time.<br> Assess your brand in the marketplace with powerful tools<br /> to analyze, measure and react.</h2></div>
	            </div>
            </div>
            <div class="item">
		    	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/brand-management-ico2.png" alt="Brand Managment Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Know More than Ever before. Know it in real time.<br> Assess your brand in the marketplace with powerful tools<br /> to analyze, measure and react.</h2></div>
	            </div>
            </div>
            <div class="item">
			   	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/brand-management-ico2.png" alt="Brand Managment Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Know More than Ever before. Know it in real time.<br> Assess your brand in the marketplace with powerful tools<br /> to analyze, measure and react.</h2></div>
	            </div>
            </div>
        </div>
        <!-- Controls -->
    </div>    

</div>
';
}
//menu on about/public-relations
add_shortcode('pr-menu','generate_pr_menu');
function generate_pr_menu(){
return '
<div id="pr-menu" class="dunami-overview-menu carousel slide" data-ride="carousel" data-interval="false">
    <!-- Indicators -->
	<div class="indicator-row">
	    <div class="space">&nbsp;</div>
	    <ol class="carousel-indicators">	   	    
		        <li data-target="#pr-menu" data-slide-to="0" class="indicator active"><span>Overview</span></li>
		        <li data-target="#pr-menu" data-slide-to="1" class="indicator"><span>Solutions</span></li>
		        <li data-target="#pr-menu" data-slide-to="2" class="indicator"><span>Benefits</span></li>
		        <li data-target="#pr-menu" data-slide-to="3" class="indicator"><span>Client Stories</span></li>
		        <li data-target="#pr-menu" data-slide-to="4" class="indicator"><span>Resources</span></li>
	    </ol>
	    <div class="space">&nbsp;</div>
    </div>    
    <div class="carousel-outer">
        <!-- Wrapper for slides -->
        <div class="carousel-inner wrap">
            <div class="item active">
            	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/pr-ico-1.png" alt="Public Relations Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Leverage social media and key audiences to best promote and defend the organizations brand and reputation and grow positive brand awareness.</h2></div>
	            </div>
        	</div>
            <div class="item">
            	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/pr-ico-1.png" alt="Public Relations Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Leverage social media and key audiences to best promote and defend the organizations brand and reputation and grow positive brand awareness.</h2></div>
	            </div>
            </div>
            <div class="item">
            	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/pr-ico-1.png" alt="Public Relations Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Leverage social media and key audiences to best promote and defend the organizations brand and reputation and grow positive brand awareness.</h2></div>
	            </div>
            </div>
            <div class="item">
		    	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/pr-ico-1.png" alt="Public Relations Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Leverage social media and key audiences to best promote and defend the organizations brand and reputation and grow positive brand awareness.</h2></div>
	            </div>
            </div>
            <div class="item">
			   	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/pr-ico-1.png" alt="Public Relations Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Leverage social media and key audiences to best promote and defend the organizations brand and reputation and grow positive brand awareness.</h2></div>
	            </div>
            </div>
        </div>
        <!-- Controls -->
    </div>    

</div>
';
}
//Public Relations Solutions Slider
add_shortcode('public-relations','generate_pr');
function generate_pr(){
return '
<div id="public-relations" class="dunami-carousel-custom carousel slide" data-ride="carousel">
    <div class="carousel-outer">
        <!-- Wrapper for slides -->
        <div class="carousel-inner">

            <div class="item brand-awareness active">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Proactive Micro Targeting</h2>
					<p>Dunami allows for precise micro targeting of very<br  specific key audience networks with tailored messages.</p><a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>
            </div>


            <div class="item brand-awareness">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Reputation Managment</h2>
					<p>Dunami can help identify the most influential voice to ensure positive brand awareness in the marketplace.</p>
					<a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>
            </div>
            <div class="item brand-awareness">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Crisis Intervention</h2>
					<p>Quickly engage potential crisis situations by delivering predetermined messages to key constituent groups though Dunami network identification and monitoring</p>
					<a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>                
            </div>
            <div class="item brand-awareness">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Message Development & Testing</h2>
					<p>Create and test specific message on narrow audiences before broader distribution. Collect feedback and understand the impact of various messages.</p>
					<a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>                
            </div>
            <div class="item brand-awareness">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Focus Group Opportunities</h2>
					<p>Uniquely identify specific sets of brand influencer’s and use as focus groups on new products, services or messages.</p>
					<a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>                
            </div>            
        </div>
        <!-- Controls -->
        <div class="controls">
	        <a class="" href="#public-relations" data-slide="prev">
	            <span class="pull-left glyphicon glyphicon-chevron-left"></span>
	        </a>
	        <a class="" href="#public-relations" data-slide="next">
	            <span class="pull-right glyphicon glyphicon-chevron-right"></span>
	        </a>
	        <div class=clearfix"></div>
		</div>        
    </div>    
    <!-- Indicators -->

    
	<div class="indicator-row">
		<div class="title"><div><span>Dunami <br />Public Relations <br />Solutions</span></div></div>	        
	    <div class="triangle"></div>
	    <ol class="carousel-indicators">	   	    
		        <li data-target="#public-relations" data-slide-to="0" class="indicator active">Proactive<br /> Micro<br /> Targeting</li>
		        <li data-target="#public-relations" data-slide-to="1" class="indicator">Reputation<br /> Managment</li>
		        <li data-target="#public-relations" data-slide-to="2" class="indicator">Crisis<br /> Intervention</li>
		        <li data-target="#public-relations" data-slide-to="3" class="indicator">Message<br /> Development<br /> & Testing</li>
		        <li data-target="#public-relations" data-slide-to="4" class="indicator">Focus Group<br />Opportunities</li>
	    </ol>
	    <div class="space">&nbsp;</div>
    </div>
</div>
';
}
//menu on corp-research page
add_shortcode('corporate-research-menu','generate_research_menu');
function generate_research_menu(){
return '
<div id="corporate-research-menu" class="dunami-overview-menu carousel slide" data-ride="carousel" data-interval="false">
    <!-- Indicators -->
	<div class="indicator-row">
	    <div class="space">&nbsp;</div>
	    <ol class="carousel-indicators">	   	    
		        <li data-target="#corporate-research-menu" data-slide-to="0" class="indicator active"><span>Overview</span></li>
		        <li data-target="#corporate-research-menu" data-slide-to="1" class="indicator"><span>Solutions</span></li>
		        <li data-target="#corporate-research-menu" data-slide-to="2" class="indicator"><span>Benefits</span></li>
		        <li data-target="#corporate-research-menu" data-slide-to="3" class="indicator"><span>Client Stories</span></li>
		        <li data-target="#corporate-research-menu" data-slide-to="4" class="indicator"><span>Resources</span></li>
	    </ol>
	    <div class="space">&nbsp;</div>
    </div>    
    <div class="carousel-outer">
        <!-- Wrapper for slides -->
        <div class="carousel-inner wrap">
            <div class="item active">
            	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/corp-research-2.png" alt="Brand Managment Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Know More than Ever before. Know it in real time.<br> Assess your brand in the marketplace with powerful tools<br /> to analyze, measure and react.</h2></div>
	            </div>
        	</div>
            <div class="item">
            	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/corp-research-2.png" alt="Brand Managment Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Know More than Ever before. Know it in real time.<br> Assess your brand in the marketplace with powerful tools<br /> to analyze, measure and react.</h2></div>
	            </div>
            </div>
            <div class="item">
            	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/corp-research-2.png" alt="Brand Managment Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Know More than Ever before. Know it in real time.<br> Assess your brand in the marketplace with powerful tools<br /> to analyze, measure and react.</h2></div>
	            </div>
            </div>
            <div class="item">
		    	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/corp-research-2.png" alt="Brand Managment Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Know More than Ever before. Know it in real time.<br> Assess your brand in the marketplace with powerful tools<br /> to analyze, measure and react.</h2></div>
	            </div>
            </div>
            <div class="item">
			   	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/corp-research-2.png" alt="Brand Managment Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Know More than Ever before. Know it in real time.<br> Assess your brand in the marketplace with powerful tools<br /> to analyze, measure and react.</h2></div>
	            </div>
            </div>
        </div>
        <!-- Controls -->
    </div>    

</div>
';
}


//menu on corp-research page
add_shortcode('corporate-communications-menu','generate_communications_menu');
function generate_communications_menu(){
return '
<div id="corporate-communications-menu" class="dunami-overview-menu carousel slide" data-ride="carousel" data-interval="false">
    <!-- Indicators -->
	<div class="indicator-row">
	    <div class="space">&nbsp;</div>
	    <ol class="carousel-indicators">	   	    
		        <li data-target="#corporate-communications-menu" data-slide-to="0" class="indicator active"><span>Overview</span></li>
		        <li data-target="#corporate-communications-menu" data-slide-to="1" class="indicator"><span>Solutions</span></li>
		        <li data-target="#corporate-communications-menu" data-slide-to="2" class="indicator"><span>Benefits</span></li>
		        <li data-target="#corporate-communications-menu" data-slide-to="3" class="indicator"><span>Client Stories</span></li>
		        <li data-target="#corporate-communications-menu" data-slide-to="4" class="indicator"><span>Resources</span></li>
	    </ol>
	    <div class="space">&nbsp;</div>
    </div>    
    <div class="carousel-outer">
        <!-- Wrapper for slides -->
        <div class="carousel-inner wrap">
            <div class="item active">
            	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/corp-comm-ico-2.png" alt="corporate Communications Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Know More than Ever before. Know it in real time.<br> Assess your brand in the marketplace with powerful tools<br /> to analyze, measure and react.</h2></div>
	            </div>
        	</div>
            <div class="item">
            	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/corp-comm-ico-2.png" alt="corporate Communications Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Know More than Ever before. Know it in real time.<br> Assess your brand in the marketplace with powerful tools<br /> to analyze, measure and react.</h2></div>
	            </div>
            </div>
            <div class="item">
            	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/corp-comm-ico-2.png" alt="corporate Communications Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Know More than Ever before. Know it in real time.<br> Assess your brand in the marketplace with powerful tools<br /> to analyze, measure and react.</h2></div>
	            </div>
            </div>
            <div class="item">
		    	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/corp-comm-ico-2.png" alt="corporate Communications Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Know More than Ever before. Know it in real time.<br> Assess your brand in the marketplace with powerful tools<br /> to analyze, measure and react.</h2></div>
	            </div>
            </div>
            <div class="item">
			   	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/corp-comm-ico-2.png" alt="corporate Communications Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Know More than Ever before. Know it in real time.<br> Assess your brand in the marketplace with powerful tools<br /> to analyze, measure and react.</h2></div>
	            </div>
            </div>
        </div>
        <!-- Controls -->
    </div>    

</div>
';
}

//menu on corp-research page
add_shortcode('advertising-agencies-menu','generate_agencies_menu');
function generate_agencies_menu(){
return '
<div id="agencies-menu" class="dunami-overview-menu carousel slide" data-ride="carousel" data-interval="false">
    <!-- Indicators -->
	<div class="indicator-row">
	    <div class="space">&nbsp;</div>
	    <ol class="carousel-indicators">	   	    
		        <li data-target="#agencies-menu" data-slide-to="0" class="indicator active"><span>Overview</span></li>
		        <li data-target="#agencies-menu" data-slide-to="1" class="indicator"><span>Solutions</span></li>
		        <li data-target="#agencies-menu" data-slide-to="2" class="indicator"><span>Benefits</span></li>
		        <li data-target="#agencies-menu" data-slide-to="3" class="indicator"><span>Client Stories</span></li>
		        <li data-target="#agencies-menu" data-slide-to="4" class="indicator"><span>Resources</span></li>
	    </ol>
	    <div class="space">&nbsp;</div>
    </div>    
    <div class="carousel-outer">
        <!-- Wrapper for slides -->
        <div class="carousel-inner wrap">
            <div class="item active">
            	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/agencies-ico-2.png" alt="corporate Communications Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Utilize Dunami’s powerful suite of tools to better server<br> clients and get the most value out of social media engagement.<br> Use Dunami to gain unique understanding about client’s target consumers and marketplace perceptions.</h2></div>
	            </div>
        	</div>
            <div class="item">
            	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/agencies-ico-2.png" alt="corporate Communications Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Utilize Dunami’s powerful suite of tools to better server<br> clients and get the most value out of social media engagement.<br> Use Dunami to gain unique understanding about client’s target consumers and marketplace perceptions.</h2></div>
	            </div>
            </div>
            <div class="item">
            	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/agencies-ico-2.png" alt="corporate Communications Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Utilize Dunami’s powerful suite of tools to better server<br> clients and get the most value out of social media engagement.<br> Use Dunami to gain unique understanding about client’s target consumers and marketplace perceptions.</h2></div>
	            </div>
            </div>
            <div class="item">
		    	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/agencies-ico-2.png" alt="corporate Communications Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Utilize Dunami’s powerful suite of tools to better server<br> clients and get the most value out of social media engagement.<br> Use Dunami to gain unique understanding about client’s target consumers and marketplace perceptions.</h2></div>
	            </div>
            </div>
            <div class="item">
			   	<div class="col-md-2 center-image tabler">
            		<div><img src="'.get_stylesheet_directory_uri().'/images/agencies-ico-2.png" alt="corporate Communications Icon" /></div>
            	</div>
	            <div class="col-md-10 left-text tabler"><div><h2>
	            	Utilize Dunami’s powerful suite of tools to better server<br> clients and get the most value out of social media engagement.<br> Use Dunami to gain unique understanding about client’s target consumers and marketplace perceptions.</h2></div>
	            </div>
            </div>
        </div>
        <!-- Controls -->
    </div>    

</div>
';
}
//advet solultions
//Public Relations Solutions Slider
add_shortcode('advertising-solutions','generate_ad_solutions');
function generate_ad_solutions(){
return '
<div id="advertising-solutions" class="dunami-carousel-custom carousel slide" data-ride="carousel">
    <div class="carousel-outer">
        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item brand-awareness active">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Proactive Micro Targeting</h2>
					<p>Dunami allows for precise micro targeting of very<br  specific key audience networks with tailored messages.</p><a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>
            </div>


            <div class="item brand-awareness">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Client Reputation Managment</h2>
					<p>Dunami can help identify the most influential voice to ensure positive brand awareness in the marketplace.</p>
					<a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>
            </div>
            <div class="item brand-awareness">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Proactive Crisis Intervention</h2>
					<p>Quickly engage potential crisis situations by delivering predetermined messages to key constituent groups though Dunami network identification and monitoring</p>
					<a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>                
            </div>
            <div class="item brand-awareness">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Creation of Targeted Messages</h2>
					<p>Create and test specific message on narrow audiences before broader distribution. Collect feedback and understand the impact of various messages.</p>
					<a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>                
            </div>
            <div class="item brand-awareness">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Better Marketplace Understanding</h2>
					<p>Uniquely identify specific sets of brand influencer’s and use as focus groups on new products, services or messages.</p>
					<a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>                
            </div>            
        </div>
        <!-- Controls -->
        <div class="controls">
	        <a class="" href="#advertising-solutions" data-slide="prev">
	            <span class="pull-left glyphicon glyphicon-chevron-left"></span>
	        </a>
	        <a class="" href="#advertising-solutions" data-slide="next">
	            <span class="pull-right glyphicon glyphicon-chevron-right"></span>
	        </a>
	        <div class=clearfix"></div>
		</div>        
    </div>    
    <!-- Indicators -->

    
	<div class="indicator-row">
		<div class="title"><div><span>Dunami <br />Advertising <br />Solutions</span></div></div>
		<div class="triangle"></div>
	    <ol class="carousel-indicators">	   	    
		        <li data-target="#advertising-solutions" data-slide-to="0" class="indicator active">Proactive<br /> Micro<br /> Targeting</li>
		        <li data-target="#advertising-solutions" data-slide-to="1" class="indicator">Client <br>Reputation<br /> Managment</li>
		        <li data-target="#advertising-solutions" data-slide-to="2" class="indicator">Proactive<br>Crisis<br /> Intervention</li>
		        <li data-target="#advertising-solutions" data-slide-to="3" class="indicator">Creations<br>of Targeted<br> Messages</li>
		        <li data-target="#advertising-solutions" data-slide-to="4" class="indicator">Better<br>Marketplace<br>Understanding</li>
	    </ol>
	    <div class="space">&nbsp;</div>
    </div>
</div>
';
}
add_shortcode('corporate-communications','generate_corporate_communications');
function generate_corporate_communications(){
return '
<div id="managment-solutions" class="dunami-carousel-custom carousel slide" data-ride="carousel">
    <div class="carousel-outer">
        <!-- Wrapper for slides -->
        <div class="carousel-inner">

            <div class="item brand-awareness active">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Brand Loyalty</h2>
					<p>The Dunami gives you the power to identify and specifically message to those who are loyal to your brand. Connect, engage and build relationships with those best poised to become your brand ambassadors</p><a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>
            </div>


            <div class="item brand-awareness">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Brand Awareness</h2>
					<p>Dunami allows real-time analyzing of who is aware of your brand and interacting with it via social media and other avenues.</p><a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>
            </div>
            <div class="item brand-awareness">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Brand Messaging</h2>
					<p>Understanding the real impact on your brand of specific brand messages. Dunami allows you to collect feedback and better target key ideas to the right people.</p><a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>                
            </div>
            <div class="item brand-awareness">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Brand Positioning & Orientation</h2>
					<p>The Dunami gives you the power to identify and specifically message to those who are loyal to your brand. Connect, engage and build relationships with those best poised to become your brand ambassadors</p><a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>                
            </div>            
        </div>
        <!-- Controls -->
        <div class="controls">
	        <a class="" href="#managment-solutions" data-slide="prev">
	            <span class="pull-left glyphicon glyphicon-chevron-left"></span>
	        </a>
	        <a class="" href="#managment-solutions" data-slide="next">
	            <span class="pull-right glyphicon glyphicon-chevron-right"></span>
	        </a>
	        <div class=clearfix"></div>
		</div>        
    </div>    
    <!-- Indicators -->

    
	<div class="indicator-row">
		<div class="title"><div><span>Dunami Corporate<br />Communications<br />Solutions</span></div></div>
		<div class="triangle"></div>	        
	    <ol class="carousel-indicators">	   	    
		        <li data-target="#managment-solutions" data-slide-to="0" class="indicator active">Brand <br />Loyalty</li>
		        <li data-target="#managment-solutions" data-slide-to="1" class="indicator">Brand<br />Awareness</li>
		        <li data-target="#managment-solutions" data-slide-to="2" class="indicator">Brand<br />Messaging</li>
		        <li data-target="#managment-solutions" data-slide-to="3" class="indicator">Brand<br />Positioning<br />& Orientation</li>
	    </ol>
	    <div class="space">&nbsp;</div>
    </div>
</div>
';
}

add_shortcode('corporateresearch','generate_corporate_research');
function generate_corporate_research(){
return '
<div id="managment-solutions" class="dunami-carousel-custom carousel slide" data-ride="carousel">
    <div class="carousel-outer">
        <!-- Wrapper for slides -->
        <div class="carousel-inner">

            <div class="item brand-awareness active">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Brand Loyalty</h2>
					<p>The Dunami gives you the power to identify and specifically message to those who are loyal to your brand. Connect, engage and build relationships with those best poised to become your brand ambassadors</p><a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>
            </div>


            <div class="item brand-awareness">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Brand Awareness</h2>
					<p>Dunami allows real-time analyzing of who is aware of your brand and interacting with it via social media and other avenues.</p><a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>
            </div>
            <div class="item brand-awareness">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Brand Messaging</h2>
					<p>Understanding the real impact on your brand of specific brand messages. Dunami allows you to collect feedback and better target key ideas to the right people.</p><a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>                
            </div>
            <div class="item brand-awareness">
                <div class="col-md-3 padder">&nbsp;</div>
                <div class="text col-md-4">
					<h2>Brand Positioning & Orientation</h2>
					<p>The Dunami gives you the power to identify and specifically message to those who are loyal to your brand. Connect, engage and build relationships with those best poised to become your brand ambassadors</p><a class="dunami-effect btn btn-default brand-management" href="//dunami.staging.wpengine.com/contact/" title="">Contact Us</a>
                </div>                
                <div class="zoom-picture col-md-5"><img src="'.get_stylesheet_directory_uri().'/images/zoom-picture.png" alt="Macbook and iMac showing the Dunami Platform" /></div><div class="clearfix"></div>                
            </div>            
        </div>
        <!-- Controls -->
        <div class="controls">
	        <a class="" href="#managment-solutions" data-slide="prev">
	            <span class="pull-left glyphicon glyphicon-chevron-left"></span>
	        </a>
	        <a class="" href="#managment-solutions" data-slide="next">
	            <span class="pull-right glyphicon glyphicon-chevron-right"></span>
	        </a>
	        <div class=clearfix"></div>
		</div>        
    </div>    
    <!-- Indicators -->

    
	<div class="indicator-row">
		<div class="title"><div><span>Dunami Corporate<br />Research Solutions</span></div></div>
		<div class="triangle"></div>	        
	    <ol class="carousel-indicators">	   	    
		        <li data-target="#managment-solutions" data-slide-to="0" class="indicator active">Brand <br />Loyalty</li>
		        <li data-target="#managment-solutions" data-slide-to="1" class="indicator">Brand<br />Awareness</li>
		        <li data-target="#managment-solutions" data-slide-to="2" class="indicator">Brand<br />Messaging</li>
		        <li data-target="#managment-solutions" data-slide-to="3" class="indicator">Brand<br />Positioning<br />& Orientation</li>
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

