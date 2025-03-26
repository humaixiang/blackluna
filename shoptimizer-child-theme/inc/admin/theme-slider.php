<?php
// 注册设置项
require_once(__DIR__.'/theme-admin.php');


// 核心功能类
class Theme_Slider_Manager {
    public function __construct() {
        $this->init_hooks();
    }

    private function init_hooks() {
        // 注册设置项
        add_action('admin_init', [$this, 'register_settings']);
        
        // 加载脚本
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // AJAX处理
        add_action('wp_ajax_save_slider_images', [$this, 'save_slider_images']);
    }

    // 注册双端设置项
    public function register_settings() {
        // ====================== PC端配置 ======================
        register_setting(
            'theme_pc_group', 
            'theme_pc_slider_images',
            [
                'type' => 'array',
                'sanitize_callback' => function($input) {
                    return $this->sanitize_slider_images($input, 'pc');
                }
            ]
        );

        add_settings_section(
            'pc_slider_section',
            '', 
            '', 
            'theme-pc-slider'  // 页面slug
        );

        add_settings_field(
            'pc_slider_images',  // 字段ID
            '上传图片',          // 字段标题
            [$this, 'pc_slider_callback'], // 回调方法
            'theme-pc-slider',   // 页面slug
            'pc_slider_section'  // 区块ID
        );

        // ====================== 移动端配置 ====================== 
        register_setting(
            'theme_mobile_group',  
            'theme_mobile_slider_images', 
            [
                'type' => 'array',
                'sanitize_callback' => function($input) {
                    return $this->sanitize_slider_images($input, 'mobile'); 
                }
            ]
        );

        add_settings_section(
            'mobile_slider_section',  
            '', 
            '', 
            'theme-mobile-slider'     
        );

        add_settings_field(
            'mobile_slider_images',  
            '上传图片',               
            [$this, 'mobile_slider_callback'], 
            'theme-mobile-slider',    
            'mobile_slider_section'   
        );


        

        register_setting(
            'theme_mobile_group',
            'theme_mobile_slider_images',
            [
                'type' => 'array',
                'sanitize_callback' => function($input) {
                    return $this->sanitize_slider_images($input, 'mobile');
                }
            ]
        );

        add_settings_field(
            'mobile_slider_images',
            '上传图片',
            [$this, 'mobile_slider_callback'],
            'theme-mobile-slider',
            'mobile_slider_section'
        );
    }

    // 通用过滤函数
    private function sanitize_slider_images($input, $device) {
        // 转换旧数据格式
        if (!is_array($input)) {
            $input = json_decode(wp_unslash($input), true) ?: [];
        }
    
        // 结构化清洗数据 
        return array_map(function($item) use ($device) {
            return [
                'url' => esc_url_raw($item['url']),
                'device' => sanitize_text_field($device),
                'time' => time()
            ];
        }, $input);
    }


    // 前端组件回调
    public function pc_slider_callback() {
        $this->device_slider_callback('pc');
    }

    public function mobile_slider_callback() {
        $this->device_slider_callback('mobile');
    }

    private function device_slider_callback($device) {
        $option_name = "theme_{$device}_slider_images";
        
        // 修复点1：强制数组转换 + 安全过滤
        $raw_images = get_option($option_name, []);
        $images = is_array($raw_images) ? $raw_images : [];
        $images = array_filter($images, function($item) {
            return isset($item['url']) && !empty($item['url']);
        });
    
        // 修复点2：正确的数组结构遍历 
        ?>
        <div class="slider-uploader" data-device="<?php echo esc_attr($device); ?>">
            <input type="hidden" 
                   name="<?php echo esc_attr($option_name); ?>" 
                   value="<?php echo esc_attr(json_encode($images)); ?>">
    
            <div class="slider-preview">
                <?php foreach ($images as $image) : 
                    $url = isset($image['url']) ? $image['url'] : '';
                    $device = isset($image['device']) ? $image['device'] : '';
                ?>
                    <div class="preview-item">
                        <img src="<?php echo esc_url($url); ?>" style="height:80px;">
                        <a href="#" 
                           class="remove-slider" 
                           data-url="<?php echo esc_url($url); ?>"
                           data-device="<?php echo esc_attr($device); ?>">×</a>
                    </div>
                <?php endforeach; ?>
            </div>
    
            <button type="button" class="button upload-slider">
                <?php esc_html_e('选择图片'); ?>
            </button>
        </div>
        <?php
    }
    

    // 加载脚本
    public function enqueue_assets($hook) {
        if ($hook !== 'toplevel_page_theme-slider-settings') return;
        
        wp_enqueue_media();
        wp_enqueue_script('theme-slider-js', 
            get_stylesheet_directory_uri() . '/assets/js/slider.js',  // 使用子主题路径
            ['jquery'], 
            filemtime(get_stylesheet_directory() . '/assets/js/slider.js'), // 子主题物理路径
            true
        );
        
        wp_localize_script('theme-slider-js', 'themeSlider', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('slider_nonce'),
            'labels'  => [
                'select' => __('选择轮播图片'),
                'remove' => __('移除')
            ]
        ]);
    }

    // AJAX保存处理
    public function save_slider_images() {
        check_ajax_referer('slider_nonce', '_ajax_nonce'); // 参数名修正
    
        if (!current_user_can('manage_options')) {
            wp_send_json_error('权限不足', 403);
        }
    
        $device = sanitize_text_field($_POST['device'] ?? '');
        $raw_images = json_decode(wp_unslash($_POST['images']), true) ?: [];
    
        // 结构化存储 
        $valid_images = array_map(function($img) use ($device) {
            return [
                'url' => esc_url_raw($img['url']),
                'device' => $device,
                'time' => time()
            ];
        }, $raw_images);
    
        // 强制刷新option缓存 
        wp_cache_delete("theme_{$device}_slider_images", 'options');
        update_option("theme_{$device}_slider_images", $valid_images, false);
    
        // 返回新预览数据
        ob_start();
        foreach ($valid_images as $img) {
            echo '<div class="preview-item">',
                 '<img src="'.esc_url($img['url']).'" style="height:80px;">',
                 '<a href="#" class="remove-slider" data-url="'.esc_attr($img['url']).'">×</a>',
                 '</div>';
        }
        wp_send_json_success(['preview' => ob_get_clean()]);
    }
}

new Theme_Slider_Manager();
