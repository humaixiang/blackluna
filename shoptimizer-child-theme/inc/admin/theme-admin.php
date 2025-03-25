<?php
/**
 * 注册后台菜单
 */

add_action('admin_menu', function() {
    // 主菜单
    add_menu_page(
        '主题设置',
        '主题设置',
        'manage_options',
        'theme-settings',
        'theme_main_page_callback',
        'dashicons-images-alt',
        30
    );

    // 轮播图子菜单
    add_submenu_page(
        'theme-settings',
        '轮播图管理',
        '轮播图管理',
        'manage_options',
        'theme-slider-settings',
        'theme_slider_page_html'
    );

    // 删除冗余菜单
    remove_submenu_page('theme-settings', 'theme-settings');
});

// 主页面回调
function theme_main_page_callback() {
    echo '<h1>主题核心设置</h1>';
}
