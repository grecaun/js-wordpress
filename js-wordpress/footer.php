<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/js_wordpresss/template-files/#template-partials
 *
 * @package js_wordpress
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<div class="copyright text-important">&copy; <?php echo get_theme_mod('js_wp_copyright' , 'Your Name Here');?></div>
            <div class="footer-menu text-important"><?php wp_nav_menu(array('menu'=>'footer')); ?></div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
<script type="text/javascript" src=<?php echo "'".get_template_directory_uri()."/js/lightbox.js'"; ?>></script>
</body>
</html>