<?php
/**
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package js_wordpress
 *
 * Template Name: Album
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="album-text-header text-important"><?php echo get_the_title(); ?></div>
			<?php
				$title = get_the_slug();
				$title = '/'.$title.'/';
				$handle = opendir(dirname(realpath(__FILE__)).$title.'thumbs/');
		  		$images = array();
		        while($file = readdir($handle)){
		        	if( preg_match('/\.(jpg|jpeg|png|gif)(?:[\?\#].*)?$/i', $file) ) {
		        		$images[] = $file;
					}
		        }
		        sort($images);
		        foreach ($images as $image) {
		        	echo "<div class='img-container'><a href='".get_template_directory_uri().$title.$image."' alt='' data-lightbox='album'><img src='".get_template_directory_uri().$title."thumbs/".$image."' class='img img-rounded'></a></div>";
		        }
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
