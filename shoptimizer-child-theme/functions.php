<?php
/**
 * Shoptimizer child theme functions
 *
 * @package shoptimizer
 */
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
        'swiper-css', // 唯一且语义明确的句柄
        $theme_uri . '/assets/css/swiper.min.css',
        [], // 无依赖
        $theme_version // 直接获取主题版本
    );

    // JS 加载
    wp_enqueue_script(
        'swiper-js',
        $theme_uri . '/assets/js/swiper.min.js',
        ['jquery'], // 显式声明依赖（根据 Swiper 版本调整）
        $theme_version,
        true // 底部加载
    );
}


function shoptimizer_child_enqueue_rtl() {
    $parent_base_dir = 'shoptimizer';
    if ( is_rtl() ) {
        wp_enqueue_style( 'shoptimizer-rtl', get_template_directory_uri() . '/rtl.css', array(), wp_get_theme( $parent_base_dir ) ? wp_get_theme( $parent_base_dir )->get( 'Version' ) : '' );
    }
}
add_action( 'wp_enqueue_scripts', 'shoptimizer_child_enqueue_rtl', 999 );

// 安全加载模块文件
$inc_files = [
    // 'inc/enqueue-assets.php',    // 资源加载
    'inc/admin/theme-admin.php', // 后台菜单
    'inc/admin/theme-slider.php' // 轮播图功能
];

foreach ($inc_files as $file) {
    if (locate_template($file, true, true) === '') {
        trigger_error(sprintf('找不到文件: %s', $file), E_USER_ERROR);
    }
}






