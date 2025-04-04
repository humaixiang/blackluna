<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package shoptimizer
 */

$shoptimizer_layout_archives_sidebar = '';
$shoptimizer_layout_archives_sidebar = shoptimizer_get_option( 'shoptimizer_layout_archives_sidebar' );

$shoptimizer_layout_blog = '';
$shoptimizer_layout_blog = shoptimizer_get_option( 'shoptimizer_layout_blog' );

$shoptimizer_layout_blog_title = '';
$shoptimizer_layout_blog_title = shoptimizer_get_option( 'shoptimizer_layout_blog_title' );

get_header(); ?>

	<div id="primary" class="content-area">
		
		<header class="entry-header title">
		    <?php if ( true === $shoptimizer_layout_blog_title ) { ?>
		        <h1 class="blog-title"><?php single_post_title(); ?></h1>
		    <?php } else { ?>
		        <h1 class="blog-title hidden"><?php single_post_title(); ?></h1>
		    <?php } ?>
		</header><!-- .entry-header -->

		<main id="main" class="site-main <?php echo shoptimizer_safe_html( $shoptimizer_layout_blog ); ?>">	

		<?php
		if ( have_posts() ) : ?>

			<?php get_template_part( 'loop' );

		else :

			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->


	<?php if ( 'no-archives-sidebar' !== $shoptimizer_layout_archives_sidebar ) { ?>
	<div id="secondary" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-posts' ); ?>
	</div><!-- #secondary -->
	<?php } ?>

<?php
get_footer();
