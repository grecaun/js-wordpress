<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/js_wordpresss/template-files/#template-partials
 *
 * @package js_wordpress
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</script>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site js_wordpress-body">
    <?php

        if (get_option('js_wp_use_small_header','1') == '1') {
    ?>
            <style type="text/css">.js_wordpress-header{background-image: url(<?php header_image(); ?>);margin: 0px auto;width:768px;height:278px;border-top-right-radius: 4px;border-top-left-radius: 4px;}@media (max-width: 767px) {.js_wordpress-header{background-image: url(<?php echo get_theme_mod('js_wp_small_header',get_template_directory_uri() . '/images/header-small.jpg') ?>);width: 170px;height: 197px;background-repeat: normal;}}</style>
            <div class="js_wordpress-header js_wordpress-headerimg"></div>
    <?php
        } else  {
    ?>
            <img alt="" src="<?php header_image(); ?>" class="js_wordpress-headerimg">
    <?php
        }
    ?>
    <header id="masthead" class="site-header" role="banner">
		<nav class="navbar navbar-default js_wordpress-navbar" role="navigation">
          <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle js_wordpress-nav-toggle" data-toggle="collapse" data-target="#js_wordpress-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="<?php echo home_url(); ?>">Home</a>
            </div>
                <?php
                    wp_nav_menu( array(
                        'menu'              => 'primary',
                        'theme_location'    => 'primary',
                        'depth'             => 3,
                        'container'         => 'div',
                        'container_class'   => 'collapse navbar-collapse',
                        'container_id'      => 'js_wordpress-navbar-collapse',
                        'menu_class'        => 'nav navbar-nav navbar-right js_wordpress-navbar-ul',
                        'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                        'walker'            => new wp_bootstrap_navwalker())
                    );
                ?>
           </div>
        </nav>
	</header><!-- #masthead -->

	<div id="content" class="site-content bg-color-one">
