<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
	<?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package js_wordpress
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses js_wordpress_header_style()
 */
function js_wordpress_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'js_wordpress_custom_header_args', array(
		'default-image'          => get_template_directory_uri() . '/images/header.jpg',
		'header-text'			 => false,
		'width'                  => 768,
		'height'                 => 278,
		'flex-height'            => true,
		'flex-width'			 => true,
	) ) );
}
add_action( 'after_setup_theme', 'js_wordpress_custom_header_setup' );

