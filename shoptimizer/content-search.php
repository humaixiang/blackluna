<?php
/**
 * Template used to display standard search results.
 *
 * @package shoptimizer
 */

?>

<?php
while ( have_posts() ) :
	the_post();
	?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( get_post_type() === 'product' ) { ?>
		<a href="<?php the_permalink(); ?>" rel="bookmark" class="image-result">
			<?php the_post_thumbnail(); ?>
		</a>
	<?php }
	?>

	<div class="search-results-content">
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>   

		<?php
			$shoptimizer_excerpt = get_the_excerpt();
			$shoptimizer_excerpt = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $shoptimizer_excerpt);
		?>
		<?php
			echo shoptimizer_safe_html( $shoptimizer_excerpt );
		?>

	</div>

</article><!-- #post-## -->

<?php endwhile; ?>
