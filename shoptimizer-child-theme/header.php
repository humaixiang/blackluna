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
		// 在<body>开始标签后插入以下修改后的代码
		$pc_banners = array_filter((array)get_option('theme_pc_slider_images', []));
		$pc_links = array_filter((array)get_option('theme_pc_slider_links', []));
		$mobile_banners = array_filter((array)get_option('theme_mobile_slider_images', []));
		$mobile_links = array_filter((array)get_option('theme_mobile_slider_links', []));

		// 当任一设备有数据时加载组件
		if (!empty($pc_banners) ||!empty($mobile_banners)) :
			// 合并数据并结构化传递
			$banner_args = [
				'pc'     => $pc_banners,
				'pc_links' => $pc_links,
				'mobile' => $mobile_banners,
				'mobile_links' => $mobile_links,
				'device' => wp_is_mobile()? 'mobile' : 'pc'
			];
			
			// 传递参数给模板文件
			set_query_var('banner_config', $banner_args);
			get_template_part('template-parts/banner-slider');
			
			// 清理查询变量
			wp_reset_query();
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
