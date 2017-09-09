<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Smart Thermostat Guide Theme' );
define( 'CHILD_THEME_URL', 'http://www.smartthermostatguide.com' );
define( 'CHILD_THEME_VERSION', '1.1.13' );

//* Enqueue Google Fonts
add_action( 'wp_enqueue_scripts', 'genesis_sample_google_fonts' );
function genesis_sample_google_fonts() {
	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700', array(), CHILD_THEME_VERSION );
}

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

// Add support for custom header
add_theme_support( 'genesis-custom-header', array(
	'width' => 688, //1152 original
	'height' => 166 //120 original
) );

// Cache busting for child theme's style.css
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {

    $parent_style = 'parent-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}

//Add a clickable image as the header
/*
function inject_clickable_header(){ ?>
<div id="title-area">
<a href="http://smartthermostatguide.com/"><img src="http://smartthermostatguide.com/wp-content/uploads/2014/12/stg_banner_2014.png"/></a>
</div>
<?php }
add_action('genesis_header','inject_clickable_header');
*/

/* Top pick thermostat link for current month and current year - added early 2015, amazon link updated 9/2016 */
function clickable_header() {
    ?>
    <div id="titleAndDescription" class="titleArea"><h1 class="no-bottom-margin"><a href="<?php echo get_bloginfo ( 'url' ); ?>"><?php echo get_bloginfo ( 'name' ); ?></a></h1>
    <p><?php echo get_bloginfo ( 'description' ); ?></p></div>
    <span id="topPickBanner">
    Top smart thermostat pick for <?php echo date('F Y');?>: <b>Ecobee3 Wi-Fi Thermostat</b>
    (<a href="http://smartthermostatguide.com/ecobee3-smart-thermostat-review/">Review</a>
    | <a href="http://amzn.to/2cfeAQZ">Shop</a>)</span>

    <!-- <a href="http://smartthermostatguide.com/"><img src="http://smartthermostatguide.com/wp-content/uploads/2014/12/stg_banner_2014.png"></a>-->
    <?php
}

add_action('genesis_site_title','clickable_header' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

/* Add support for site-specific styles in editor */
add_editor_style();

/**********************************
 *
 * Replace Header Site Title with Inline Logo
 *
 * Fixes Genesis bug - when using static front page and blog page (admin reading settings) Home page is <p> tag and Blog page is <h1> tag
 *
 * Replaces "is_home" with "is_front_page" to correctly display Home page wit <h1> tag and Blog page with <p> tag
 *
 * @author AlphaBlossom / Tony Eppright
 * @link http://www.alphablossom.com/a-better-wordpress-genesis-responsive-logo-header/
 *
 * @edited by Sridhar Katakam
 * @link http://www.sridharkatakam.com/use-inline-logo-instead-background-image-genesis/
 *
************************************/

add_filter( 'genesis_seo_title', 'custom_header_inline_logo', 10, 3 );
function custom_header_inline_logo( $title, $inside, $wrap ) {

	$logo = '<img src="' . get_stylesheet_directory_uri() . '/images/logo.png" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" width="300" height="60" />';

	$inside = sprintf( '<a href="%s" title="%s">%s</a>', trailingslashit( home_url() ), esc_attr( get_bloginfo( 'name' ) ), $logo );

	// Determine which wrapping tags to use - changed is_home to is_front_page to fix Genesis bug
	$wrap = is_front_page() && 'title' === genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : 'p';

	// A little fallback, in case an SEO plugin is active - changed is_home to is_front_page to fix Genesis bug
	$wrap = is_front_page() && ! genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : $wrap;

	// And finally, $wrap in h1 if HTML5 & semantic headings enabled
	$wrap = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h1' : $wrap;

	return sprintf( '<%1$s %2$s>%3$s</%1$s>', $wrap, genesis_attr( 'site-title' ), $inside );

}

// Remove the site description
//remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

// Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'custom_scripts_styles_mobile_responsive' );
function custom_scripts_styles_mobile_responsive() {

	wp_enqueue_script( 'responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_style( 'dashicons' );
}

add_action('genesis_site_title','clickable_header' );
remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

// Customize the previous page link
add_filter ( 'genesis_prev_link_text' , 'sp_previous_page_link' );
function sp_previous_page_link ( $text ) {
	return g_ent( '&laquo; ' ) . __( 'Previous Page', CHILD_DOMAIN );
}

// Customize the next page link
add_filter ( 'genesis_next_link_text' , 'sp_next_page_link' );
function sp_next_page_link ( $text ) {
	return __( 'Next Page', CHILD_DOMAIN ) . g_ent( ' &raquo; ' );
}

/**
 * Remove Genesis Page Templates
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/remove-genesis-page-templates
 *
 * @param array $page_templates
 * @return array
 */
function be_remove_genesis_page_templates( $page_templates ) {
	unset( $page_templates['page_archive.php'] );
	unset( $page_templates['page_blog.php'] );
	return $page_templates;
}
add_filter( 'theme_page_templates', 'be_remove_genesis_page_templates' );

/*Use excerpts in category view */
add_action( 'genesis_before', 'child_conditional_actions' );
function child_conditional_actions() {
    if( is_category() ) {
        //put your actions here
        remove_action( 'genesis_post_content', 'genesis_do_post_content' );
		remove_action( 'genesis_before_post_content', 'genesis_post_info' );
        /*remove_action( 'genesis_post_content', 'genesis_do_post_image' );*/
        add_action( 'genesis_post_content', 'the_excerpt' );

    }
}


/* 4/30/2016 - Adding AmazonCPM (with Adsense fallback) to the bottom of every post */
add_filter( 'the_content', 'my_content_filter' );

function my_content_filter( $content ) {
   if ( is_single() || is_page() ) {
      $content .= '
      <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- STG_bottom_of_every_post_responsive -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-1407541373557710"
     data-ad-slot="9101222385"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script><br>';
   }
   return $content;
}

/* Custom styles for STG.com in the editor added 9/2017 */
/* From this tutorial: http://www.wpbeginner.com/wp-tutorials/how-to-add-custom-styles-to-wordpress-visual-editor/ */
function wpb_mce_buttons_2($buttons) {
    array_unshift($buttons, 'styleselect');
    return $buttons;
}
add_filter('mce_buttons_2', 'wpb_mce_buttons_2');

/*
* Callback function to filter the MCE settings
*/

function my_mce_before_init_insert_formats( $init_array ) {

// Define the style_formats array

    $style_formats = array(
/*
* Each array child is a format with it's own settings
* Notice that each array has title, block, classes, and wrapper arguments
* Title is the label which will be visible in Formats menu
* Block defines whether it is a span, div, selector, or inline style
* Classes allows you to define CSS classes
* Wrapper whether or not to add a new block-level element around any selected elements
*/
        array(
            'title' => 'Pale Green Quote Box',
            'block' => 'span',
            'classes' => 'pale-green-quote-box',
            'wrapper' => true,

        ),
        array(
            'title' => 'Amazon Button',
            'block' => 'span',
            'classes' => 'amazon-button',
            'wrapper' => true,
        ),
    );
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode( $style_formats );

    return $init_array;

}
// Attach callback to 'tiny_mce_before_init'
add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' );
