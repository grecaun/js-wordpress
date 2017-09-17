<?php
/**
 * js_wordpress Theme Customizer
 *
 * @package js_wordpress
 */

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function js_wordpress_customize_preview_js() {
	wp_enqueue_script( 'js_wordpress_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'js_wordpress_customize_preview_js' );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function js_wordpress_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    // main theme color 1
    $txtcolors[] = array(
        'slug'=>'theme_color_1',
        'default' => '#5b5b5b',
        'label' => 'Main Theme Color',
        'section' => 'colors',
    );

    // secondary theme color
    $txtcolors[] = array(
        'slug'=>'theme_color_2',
        'default' => '#3c3c3c',
        'label' => 'Secondary Theme Color',
        'section' => 'colors',
    );

    $txtcolors[] = array(
        'slug' => 'theme_color_highlight',
        'default' => '#000000',
        'label' => 'Navbar Highlight',
        'section' => 'colors',
    );

    // content theme color
    $txtcolors[] = array(
        'slug'=>'theme_color_3',
        'default' => '#FCFCFC',
        'label' => 'Content Area Background Color',
        'section' => 'colors',
    );

    // main color ( site title, h1, h2, h4. h6, widget headings, nav links, footer headings )
    $txtcolors[] = array(
        'slug'=>'text_color_1',
        'default' => '#5b5b5b',
        'label' => 'Main Text Color',
        'section' => 'colors',
    );

    // secondary color ( site description, sidebar headings, h3, h5, nav links on hover )
    $txtcolors[] = array(
        'slug'=>'text_color_2',
        'default' => '#000000',
        'label' => 'Secondary Text Color',
        'section' => 'colors',
    );

    $txtcolors[] = array(
        'slug' => 'nav_text',
        'default' => '#FFFFFF',
        'label' => 'Navbar Text Color',
        'section' => 'colors',
    );

    $txtcolors[] = array(
        'slug' => 'hover_nav_text',
        'default' => '#A2F1FA',
        'label' => 'Navbar Text Color (on hover)',
        'section' => 'colors',
    );

    // link color
    $txtcolors[] = array(
        'slug'=>'link_color',
        'default' => '#484b6d',
        'label' => 'Link Color',
        'section' => 'colors',
    );

    // link color ( hover, active )
    $txtcolors[] = array(
        'slug'=>'hover_link_color',
        'default' => '#2b8bb5',
        'label' => 'Link Color (on hover)',
        'section' => 'colors',
    );

    foreach ($txtcolors as $txtcolor) {
        // settings
        $wp_customize->add_setting(
            $txtcolor['slug'], array(
                'default' => $txtcolor['default'],
                'type' => 'option',
                'capability' => 'edit_theme_options',
            )
        );
        // controls
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                $txtcolor['slug'],
                array(
                    'label' => $txtcolor['label'],
                    'section' => $txtcolor['section'],
                    'settings' => $txtcolor['slug'],
                )
            )
        );
    }

    $wp_customize->add_section('js_wp_settings',
        array(
            'title' => 'Theme Options',
            'priority' => 25,
        )
    );

    $wp_customize->add_setting('js_wp_width',
        array(
            'default' => 768,
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'js_wordpress_sanitize_number',
        )
    );

    $wp_customize->add_control('js_wp_width',
        array(
            'type' => 'number',
            'section' => 'js_wp_settings',
            'settings' => 'js_wp_width',
            'label' => __( 'Page Width' ),
        )
    );

    $wp_customize->add_setting('js_wp_side_width',
        array(
            'default' => 215,
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'js_wordpress_sanitize_number',
        )
    );

    $wp_customize->add_control('js_wp_side_width',
        array(
            'type' => 'number',
            'section' => 'js_wp_settings',
            'settings' => 'js_wp_side_width',
            'label' => __( 'Sidebar Width' ),
        )
    );

    $wp_customize->add_setting('js_wp_show_side',
        array(
            'type'=>'option',
            'default' => '1',
            'capability' => 'edit_theme_options',
        )
    );

    $wp_customize->add_control('js_wp_show_side',
        array(
            'type'=>'checkbox',
            'section'=>'js_wp_settings',
            'label' => __( 'Show Sidebar?' ),
            'std' => '1',
        )
    );

    $wp_customize->add_setting('js_wp_copyright',
        array(
            'default' => 'Your Name Here',
            'capability' => 'edit_theme_options',
        )
    );

    $wp_customize->add_control('js_wp_copyright',
        array(
            'type' => 'text',
            'section' => 'js_wp_settings',
            'label' => __( 'Owner Information' ),
        )
    );

    $wp_customize->add_setting('js_wp_right',
        array(
            'type'=>'option',
            'default' => '1',
            'capability' => 'edit_theme_options',
        )
    );

    $wp_customize->add_control('js_wp_right',
        array(
            'type'=>'checkbox',
            'section'=>'js_wp_settings',
            'label' => __( 'Place Widget Area to the Right?' ),
            'std' => '1',
        )
    );

    $wp_customize->add_setting('js_wp_small_header',
        array(
            'default'    => get_template_directory_uri() . '/images/header-small.jpg',
            'capability' => 'edit_theme_options',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'js_wp_small_header',
            array(
                'label' => __( 'Small Header for Mobile Viewing', 'js_wordpress' ),
                'section' => 'header_image',
                'settings' => 'js_wp_small_header',
            )
        )
    );

    $wp_customize->add_setting('js_wp_use_small_header',
        array(
            'type'=>'option',
            'default' => '1',
            'capability' => 'edit_theme_options',
        )
    );

    $wp_customize->add_control('js_wp_use_small_header',
        array(
            'type'=>'checkbox',
            'section'=>'header_image',
            'label' => __( 'Use Small Header for Mobile?' ),
            'std' => '1',
        )
    );
}
add_action( 'customize_register', 'js_wordpress_customize_register' );

function js_wordpress_sanitize_number( $number, $setting ) {
    return absint( $number );
}

function js_wordpress_customize_colors() {
    $theme_color_1 = get_option( 'theme_color_1', '#5b5b5b' );
    $theme_color_2 = get_option( 'theme_color_2', '#3c3c3c' );
    $theme_color_3 = get_option( 'theme_color_3', '#FCFCFC' );
    $theme_color_highlight = get_option( 'theme_color_highlight', '#000000' );
    $text_color_1 = get_option( 'text_color_1', '#5b5b5b' );
    $text_color_2 = get_option( 'text_color_2', '#000000' );
    $nav_text = get_option( 'nav_text', '#FFFFFF' );
    $hover_nav_text = get_option( 'hover_nav_text', '#A2F1FA' );
    $link_color = get_option( 'link_color', '#484b6d' );
    $hover_link_color = get_option( 'hover_link_color', '#2b8bb5' );
    $theme_width = get_theme_mod('js_wp_width', 768);
    $side_width = get_theme_mod('js_wp_side_width', 215);
    $show_side = get_option('js_wp_show_side', '0');
    $on_right = get_option('js_wp_right', '0');
?>
<style type="text/css">
.text-important {
    color:  <?php echo $text_color_1; ?>;
}
.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
    color:  <?php echo $text_color_1; ?>;
}
.js_wordpress-body {
    max-width: <?php echo $theme_width; ?>px;
}
.site-content {
    color: <?php echo $text_color_2; ?>;
    background-color: <?php echo $theme_color_3; ?>;
}
.content-area {
    width: <?php if ($show_side == 1) echo ($theme_width - $side_width - 2); else echo $theme_width; ?>px;
    float: <?php echo ($on_right == '1' ? 'left' :  'right'); ?>;
    background-color: <?php echo $theme_color_3; ?>;
}
@media (max-width: 768px) {
    .content-area {
        width: 100%;
    }
}
.widget-area {
    <?php if ($show_side == '') echo "display:none !important;"; ?>
    max-width: <?php echo $side_width; ?>px;
    float: <?php echo ($on_right == '1' ? 'right' :  'left'); ?>;
    <?php echo ($on_right == '1' ? 'margin-left' :  'margin-right'); ?>: 2px;
    background-color: <?php echo $theme_color_3;?>;
}
.site-footer {
    background-color: <?php echo $theme_color_1; ?>;
}
.footer-menu,.copyright,.footer-menu li a  {
    color: <?php echo $nav_text; ?>;
}
.footer-menu li a: hover {
    color: <?php echo $hover_nav_text; ?>;
}
.entry-title {
    color: <?php echo $text_color_1; ?> !important;
}
.navbar-brand {
    color: <?php echo $nav_text; ?> !important;
}
.navbar-brand:hover,
.navbar-brand:focus {
    color: <?php echo $hover_nav_text; ?> !important;
    background-color: <?php echo $theme_color_highlight; ?> !important;
}
.js_wordpress-navbar,
.js_wordpress-nav-toggle {
    background-color: <?php echo $theme_color_1; ?>;
    border-bottom-color: <?php echo $theme_color_2; ?>;
}
.js_wordpress-navbar-ul > li > .dropdown-menu {
    background-color: <?php echo $theme_color_1; ?>;
}
.js_wordpress-navbar-ul > li > .dropdown-menu > li > a,
.js_wordpress-navbar-ul > li > a {
    color: <?php echo $nav_text; ?> !important;
}
.js_wordpress-navbar-ul > li > a:hover,
.js_wordpress-navbar-ul > li > a:focus,
.js_wordpress-navbar-ul > .active > a,
.js_wordpress-navbar-ul > .open > a,
.js_wordpress-navbar-ul > li > .dropdown-menu > li > a:hover,
.js_wordpress-navbar-ul > li > .dropdown-menu > li > a:focus,
.js_wordpress-navbar-ul > li > .dropdown-menu > .active > a,
.js_wordpress-navbar-ul > .active > a {
  color:<?php echo $hover_nav_text; ?> !important;
  background-color:<?php echo $theme_color_highlight; ?> !important;
}
.menu > li > a:hover,
.menu > li > a:focus {
    color:<?php echo $hover_nav_text; ?>;
}
.album-text-header {
    color: <?php echo $text_color_1; ?> !important;
}
a {
    color: <?php echo $link_color; ?>;
}
a:hover, a:focus {
    color: <?php echo $hover_link_color; ?>;
}
</style>
<?php
}
add_action( 'wp_head', 'js_wordpress_customize_colors');