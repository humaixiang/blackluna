<?php
/**
 * Shoptimizer WooCommerce hooks
 *
 * @package shoptimizer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Styles
 *
 * @see  shoptimizer_woocommerce_scripts()
 */

/**
 * Layout - Remove Default WooCommerce Actions
 * -----------------------------------------
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

/**
 * Global Layout Actions
 * -------------------
 */
add_action( 'woocommerce_before_main_content', 'shoptimizer_before_content', 10 );
add_action( 'woocommerce_after_main_content', 'shoptimizer_after_content', 10 );
add_action( 'shoptimizer_content_top', 'shoptimizer_shop_messages', 15 );
add_action( 'shoptimizer_content_top', 'shoptimizer_breadcrumbs', 5 );

/**
 * Product Loop Layout
 * ------------------
 */
// Sorting and Filtering
add_action( 'woocommerce_before_shop_loop', 'shoptimizer_sorting_wrapper', 9 );
add_action( 'woocommerce_before_shop_loop', 'shoptimizer_sorting_wrapper_close', 31 );
add_action( 'woocommerce_before_shop_loop', 'shoptimizer_product_columns_wrapper', 40 );

// Pagination and Results
add_action( 'woocommerce_before_shop_loop', 'shoptimizer_woocommerce_pagination', 30 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 30 );

// End Wrappers
add_action( 'woocommerce_after_shop_loop', 'shoptimizer_sorting_wrapper_end', 9 );
add_action( 'woocommerce_after_shop_loop', 'shoptimizer_sorting_wrapper_close', 31 );
add_action( 'woocommerce_after_shop_loop', 'shoptimizer_product_columns_wrapper_close', 40 );

/**
 * Sorting and Results Count Display
 * -------------------------------
 */
$shoptimizer_layout_woocommerce_display_sorting = shoptimizer_get_option( 'shoptimizer_layout_woocommerce_display_sorting' );
if ( true === $shoptimizer_layout_woocommerce_display_sorting ) {
	add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
	add_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
}

$shoptimizer_layout_woocommerce_display_count = shoptimizer_get_option( 'shoptimizer_layout_woocommerce_display_count' );
if ( true === $shoptimizer_layout_woocommerce_display_count ) {
	add_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	add_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
}

/**
 * Product Display Settings
 * ----------------------
 */
$shoptimizer_layout_woocommerce_cta_display = shoptimizer_get_option( 'shoptimizer_layout_woocommerce_cta_display' );
if ( 'no-cta' === $shoptimizer_layout_woocommerce_cta_display ) {
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
}

/**
 * Single Product Layout
 * -------------------
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

$shoptimizer_layout_woocommerce_meta_display = shoptimizer_get_option( 'shoptimizer_layout_woocommerce_meta_display' );
if ( true === $shoptimizer_layout_woocommerce_meta_display ) {
	add_action( 'woocommerce_after_single_product_summary', 'woocommerce_template_single_meta', 15 );
}

/**
 * Related Products Display
 * ----------------------
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

$shoptimizer_layout_woocommerce_related_display = shoptimizer_get_option( 'shoptimizer_layout_woocommerce_related_display' );
if ( true === $shoptimizer_layout_woocommerce_related_display ) {
	add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
}

/**
 * Product Badge and Rating Display
 * -----------------------------
 */
add_action( 'woocommerce_before_shop_loop_item_title', 'shoptimizer_shop_out_of_stock', 8 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

$shoptimizer_layout_woocommerce_display_badge = shoptimizer_get_option( 'shoptimizer_layout_woocommerce_display_badge' );
if ( true === $shoptimizer_layout_woocommerce_display_badge ) {
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
}

$shoptimizer_layout_woocommerce_display_rating = shoptimizer_get_option( 'shoptimizer_layout_woocommerce_display_rating' );
if ( true === $shoptimizer_layout_woocommerce_display_rating ) {
	add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 6 );
}

/**
 * Sticky Add to Cart
 * ----------------
 */
add_action( 'woocommerce_before_single_product_summary', 'shoptimizer_sticky_single_add_to_cart', 30 );

/**
 * Header Cart and Search
 * --------------------
 */
$shoptimizer_layout_woocommerce_enable_sidebar_cart = shoptimizer_get_option( 'shoptimizer_layout_woocommerce_enable_sidebar_cart' );
if ( true === $shoptimizer_layout_woocommerce_enable_sidebar_cart ) {
	add_action( 'shoptimizer_before_site', 'shoptimizer_header_cart_drawer', 5 );
}

$shoptimizer_layout_search_display = shoptimizer_get_option( 'shoptimizer_layout_search_display' );
if ( 'disable' !== $shoptimizer_layout_search_display ) {
	add_action( 'shoptimizer_header', 'shoptimizer_product_search', 25 );
}

add_action( 'shoptimizer_header', 'shoptimizer_header_cart', 50 );
add_action( 'shoptimizer_navigation', 'shoptimizer_header_cart', 60 );

/**
 * Cart Fragment Support
 * -------------------
 */
if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.3', '>=' ) ) {
	add_filter( 'woocommerce_add_to_cart_fragments', 'shoptimizer_cart_link_fragment' );
} else {
	add_filter( 'add_to_cart_fragments', 'shoptimizer_cart_link_fragment' );
}

/**
 * Mobile Search Display
 * -------------------
 */
$shoptimizer_search_mobile = shoptimizer_get_option( 'shoptimizer_search_mobile' );
$shoptimizer_search_mobile_position = shoptimizer_get_option( 'shoptimizer_search_mobile_position' );

if ( 'enable' === $shoptimizer_search_mobile ) {
	if ( 'within-navigation' === $shoptimizer_search_mobile_position ) {
		add_action( 'shoptimizer_navigation', 'shoptimizer_product_search', 45 );
	}
}

/**
 * Checkout Coupon Position
 * ----------------------
 */
$shoptimizer_checkout_coupon_position = shoptimizer_get_option( 'shoptimizer_checkout_coupon_position' );
if ( 'bottom' === $shoptimizer_checkout_coupon_position ) {
	/**
	 * Checkout Page - Reorder the coupon code form so that it appears at the bottom of the page.
	 */
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
	add_action( 'woocommerce_after_checkout_form', 'woocommerce_checkout_coupon_form' );
	add_action( 'woocommerce_after_checkout_form', 'shoptimizer_coupon_wrapper_start', 5 );
	add_action( 'woocommerce_after_checkout_form', 'shoptimizer_coupon_wrapper_end', 99 );
}

// Legacy WooCommerce columns filter.
if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.3', '<' ) ) {
	add_filter( 'loop_shop_columns', 'shoptimizer_loop_columns' );
}