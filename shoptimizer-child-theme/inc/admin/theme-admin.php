<?php
// 注册管理菜单
add_action('admin_menu', function() {
    add_menu_page(
        '主题轮播管理',
        '轮播设置',
        'manage_options',
        'theme-slider-settings',
        'theme_slider_admin_page',
        'dashicons-images-alt2'
    );
});

// 管理页面框架
function theme_slider_admin_page() {
    if (!current_user_can('manage_options')) return;
    ?>
    <div class="wrap">
        <h1>双端轮播图管理</h1>
        
        <!-- PC端设置 -->
        <div class="slider-config-box">
            <h2>PC端轮播图设置</h2>
            <form method="post" action="options.php">
                <?php
                settings_fields('theme_pc_group');
                do_settings_sections('theme-pc-slider');
                submit_button('保存PC配置');
                ?>
            </form>
        </div>

        <!-- 移动端设置 -->
        <div class="slider-config-box">
            <h2>移动端轮播图设置</h2>
            <form method="post" action="options.php">
                <?php
                settings_fields('theme_mobile_group');
                do_settings_sections('theme-mobile-slider');
                submit_button('保存移动端配置');
                ?>
            </form>
        </div>
    </div>
    
    <style>
    .slider-config-box {
        background: #fff;
        padding: 20px;
        margin: 20px 0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .slider-preview { display: flex; flex-wrap: wrap; gap: 10px; }
    .preview-item { position: relative; }
    .remove-slider {
        position: absolute;
        right: 5px;
        top: 5px;
        background: red;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        text-align: center;
        line-height: 18px;
        text-decoration: none;
    }
    </style>
    <?php
}
