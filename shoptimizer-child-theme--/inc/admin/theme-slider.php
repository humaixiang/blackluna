<?php
/**
 * 轮播图功能实现
 */
// 注册设置项
add_action('admin_init', function() {
    register_setting('theme_banner_group', 'theme_banner_images', [
        'type' => 'array',
        'sanitize_callback' => 'theme_sanitize_banner_images'
    ]);

    add_settings_section(
        'banner_section',
        '轮播图管理',
        'banner_section_callback',
        'theme-slider-settings'
    );

    add_settings_field(
        'banner_images',
        '上传图片',
        'banner_images_callback',
        'theme-slider-settings',
        'banner_section'
    );
});

// 数据消毒
function theme_sanitize_banner_images($input) {
    // 处理空值
    if (empty($input)) return [];

    // 处理JSON字符串
    if (is_string($input)) {
        $input = json_decode($input, true);
        if (json_last_error() !== JSON_ERROR_NONE) return [];
    }

    // 过滤验证
    return array_unique(array_filter((array)$input, function($url) {
        $url = esc_url_raw($url);
        return filter_var($url, FILTER_VALIDATE_URL)
            && preg_match('/\.(jpe?g|png|gif)(\?.*)?$/i', $url);
    }));
}

// 轮播图页面HTML
function theme_slider_page_html() {
    if (!current_user_can('manage_options')) return; // 权限检查
    ?>
    <div class="wrap">
        <h1>轮播图管理</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('theme_banner_group');
            do_settings_sections('theme-slider-settings');
            submit_button('保存设置');
            ?>
        </form>
    </div>
    <?php
}

// 设置项说明
function banner_section_callback() {
    echo '<p>点击下方按钮上传和管理轮播图片</p>';
}

// 图片上传界面
function banner_images_callback() {
    wp_enqueue_media(); // 必须加载媒体库
    // 安全获取数组数据（关键修改）
    $images = get_option('theme_banner_images', []);
    $images = is_array($images) ? $images : []; // 二次保障
    ?>
    <div class="banner-uploader">
        <input type="button" class="button button-primary upload-banner" value="上传图片">
        <div class="banner-preview" style="margin-top:20px;">
            <?php foreach ($images as $url) : ?>
                <div class="preview-item" style="display:inline-block; margin:5px; position:relative;">
                    <img src="<?php echo esc_url($url); ?>" style="width:150px; height:auto; border:2px solid #ddd;">
                    <a href="#" class="remove-banner"
                       data-url="<?php echo esc_url($url); ?>"
                       style="position:absolute; top:-10px; right:-10px; background:red; color:white; border-radius:50%; width:20px; height:20px; text-align:center; line-height:20px; text-decoration:none;">×</a>
                </div>
            <?php endforeach; ?>
        </div>
        <input type="hidden" id="theme_banner_images" name="theme_banner_images" value="<?php echo esc_attr(json_encode($images)); ?>">
    </div>

    <!-- 交互脚本 -->
    <script>
        jQuery(function($) {
            let frame;

            // 打开媒体库
            $('.upload-banner').off('click').on('click', function(e) {
                e.preventDefault();

                // 关键修复：先创建实例再打开
                frame = wp.media({
                    title: '选择轮播图',
                    multiple: true,
                    library: { type: 'image' },
                    button: { text: '使用选中图片' }
                });

                frame.on('select', function() {
                    const attachments = frame.state().get('selection').toJSON();
                    const newUrls = attachments.map(att => att.url);
                    const existing = JSON.parse($('#theme_banner_images').val() || '[]');

                    // 合并去重
                    const merged = [...new Set([...existing, ...newUrls])];
                    $('#theme_banner_images').val(JSON.stringify(merged));

                    // 刷新预览
                    $('.banner-preview').empty();
                    merged.forEach(url => {
                        $('.banner-preview').append(`
						<div class="preview-item" style="display:inline-block; margin:5px; position:relative;">
							<img src="${url}" style="width:150px; height:auto; border:2px solid #ddd;">
							<a href="#" class="remove-banner" data-url="${url}"
							style="position:absolute; top:-10px; right:-10px; background:red; color:white; border-radius:50%; width:20px; height:20px; text-align:center; line-height:20px; text-decoration:none;">×</a>
						</div>
					`);
                    });
                });

                // 必须调用open()才会显示媒体库
                frame.open(); // <-- 这个调用必须保留
            });


            // 删除图片（无需刷新）
            $('.banner-preview').on('click', '.remove-banner', function(e) {
                e.preventDefault();
                const url = $(this).data('url');
                const existing = JSON.parse($('#theme_banner_images').val() || '[]');
                const newVal = existing.filter(u => u !== url);

                $('#theme_banner_images').val(JSON.stringify(newVal));
                $(this).closest('.preview-item').fadeOut(300, function() {
                    $(this).remove();
                });

                // AJAX实时保存
                $.post(ajaxurl, {
                    action: 'save_banner_images',
                    images: newVal,
                    _wpnonce: '<?php echo wp_create_nonce('banner_nonce'); ?>'
                });
            });
        });
    </script>
    <?php
}

// AJAX保存处理
add_action('wp_ajax_save_banner_images', 'save_banner_images');
function save_banner_images() {
    check_ajax_referer('banner_nonce', '_wpnonce');
    if (!current_user_can('manage_options')) wp_send_json_error('权限不足');
    update_option('theme_banner_images', $_POST['images'] ?? []);
    wp_send_json_success();
}