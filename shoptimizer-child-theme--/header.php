<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package shoptimizer
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="height=device-height, width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>


<?php do_action( 'shoptimizer_before' ); ?>



<?php if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
} ?>

<div id="page" class="hfeed site">

	<?php
	do_action( 'shoptimizer_before_site' );
	do_action( 'shoptimizer_before_header' );
	?>

	<?php do_action( 'shoptimizer_topbar' ); ?>

	<header id="masthead" class="site-header">

		<div class="menu-overlay"></div>

		<div class="main-header col-full">

			<?php
			/**
			 * Functions hooked into shoptimizer_header action
			 *
			 * @hooked shoptimizer_site_branding                    - 20
			 * @hooked shoptimizer_secondary_navigation             - 30
			 * @hooked shoptimizer_product_search                   - 40
			 */
			do_action( 'shoptimizer_header' );
			?>

		</div>


	</header><!-- #masthead -->

	
	<div class="col-full-nav">

	<?php
	/**
	 * Functions hooked into shoptimizer_header action
	 *
	 * @hooked shoptimizer_primary_navigation_wrapper       - 42
	 * @hooked shoptimizer_primary_navigation               - 50
	 * @hooked shoptimizer_header_cart                      - 60
	 * @hooked shoptimizer_primary_navigation_wrapper_close - 68
	 */
	do_action( 'shoptimizer_navigation' );
	?>

	</div>

	<?php
	// 在 <body> 开始标签后插入
	$banners = get_option('theme_banner_images'); // 获取数据

	// 条件加载组件
	if (!empty($banners)) :
		// 传递参数给模板文件
		set_query_var('banners_data', $banners);
		get_template_part('template-parts/banner-slider');
		wp_reset_query(); // 清除临时数据
	endif;
	?>


	<?php
	/**
	 * Functions hooked in to shoptimizer_before_content
	 *
	 * @hooked shoptimizer_header_widget_region - 10
	 */
	do_action( 'shoptimizer_before_content' );
	?>

	<div id="content" class="site-content" tabindex="-1">

		<div class="shoptimizer-archive">

		<div class="archive-header">
			<div class="col-full">
				<?php
				/**
				 * Functions hooked in to shoptimizer_content_top
				 *
				 * @hooked woocommerce_breadcrumb - 10
				 */
				do_action( 'shoptimizer_content_top' );
				?>
			</div>
		</div>

		<div class="col-full">
