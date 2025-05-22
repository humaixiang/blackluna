<?php
/**
 * Shoptimizer child theme functions
 *
 * @package shoptimizer
 */
// 先从URL上去除Category

// add_filter('category_link','remove_category_link');
// add_filter('term_link','remove_category_link');

// function remove_category_link($link){
// 	return str_replace('/collections/','/',$link);
// }

// // 重写下去除后的规则让去除后访问仍然访问category

// add_action('init','rewrite_category_link');

// function rewrite_category_link(){
// 	//所有链接是所有分类访问的链接，而不是其他链接
// 	$terms = get_terms(array(
// 		'taxonomy' => 'collections',
// 		'hide_empty' => false
// 	));
// 	foreach($terms as $term){
// 		add_rewrite_rule('^'.$term->slug.'/?$','index.php?collections='.$term->slug,'top');
// 	}
// 	flush_rewrite_rules(false);
// }

//移除头部用户头像
// add_action('shoptimizer_header', 'remove_header_account');
// function remove_header_account() {
//     remove_action( 'shoptimizer_header', 'shoptimizer_secondary_navigation', 30 );
// }
//移除头部导航右侧购物车按钮
// 定义主题目录常量
define('THEME_DIR', get_stylesheet_directory());
define('THEME_URI', get_stylesheet_directory_uri());

// 加载 Swiper 资源
add_action('wp_enqueue_scripts', 'load_swiper_resources');
function load_swiper_resources() {
    // 统一使用主题文件路径函数
    $theme_uri = get_theme_file_uri();
    $theme_version = wp_get_theme()->get('Version');

    // CSS 加载
    wp_enqueue_style(
        'swiper-css',
        $theme_uri . '/assets/css/blackluna.css',
        [],
        $theme_version
    );

    wp_enqueue_style(
        'blackluna-css',
        $theme_uri . '/assets/css/swiper.min.css',
        [],
        $theme_version
    );

    // JS 加载
    wp_enqueue_script(
        'swiper-js',
        $theme_uri . '/assets/js/swiper.min.js',
        ['jquery'],
        $theme_version,
        true
    );
    wp_enqueue_script(
        'blackluna-js',
        $theme_uri . '/assets/js/blackluna.js',
        ['jquery'],
        $theme_version,
        true
    );
}
$inc_files = [
    'inc/admin/theme-admin.php', // 后台菜单
    'inc/admin/theme-slider.php' // 轮播图功能
];

foreach ($inc_files as $file) {
    if (locate_template($file, true, true) === '') {
        trigger_error(sprintf('找不到文件: %s', $file), E_USER_ERROR);
    }
}


 add_action('shoptimizer_navigation', 'remove_header_account');
 function remove_header_account() {
    remove_action( 'shoptimizer_navigation', 'shoptimizer_header_cart', 60 );
 }

 define( 'WC_MAX_LINKED_VARIATIONS', 1000 ); // ‌:ml-citation{ref="2" data="citationList"}


function custom_wc_ajax_variation_threshold( $qty, $product ) {
    return 200;
}
add_filter( 'woocommerce_ajax_variation_threshold', 'custom_wc_ajax_variation_threshold', 10, 2 );

add_filter('woocommerce_dropdown_variation_attribute_options_html', 'customize_out_of_stock_attributes', 99, 2);
function customize_out_of_stock_attributes($html, $args) {
    // 移除disabled属性和特定CSS类
    $html = str_replace(['disabled="disabled"', 'cgkit-disabled'], '', $html);
    return $html;
}
