<?php
/**
 * 双端轮播模板
 * 
 * @param array $banner_config {
 *     @type array  $pc     PC端轮播数据
 *     @type array  $mobile 移动端数据
 *     @type string $device 当前设备类型
 * }
 */
$config = get_query_var('banner_config', []);
extract($config); // 解构数组为变量
?>

<!-- PC端轮播 -->
<div class="swiper-container swiper-pc-banner" data-device="pc">
    <div class="swiper-wrapper">
        <?php foreach ($pc as $item) : 
            $url = is_array($item) ? ($item['url'] ?? '') : $item;
            if(empty($url)) continue;
        ?>
            <div class="swiper-slide">
                <img src="<?php echo esc_url($url); ?>" 
                     alt="<?php echo esc_attr($item['alt'] ?? __('电脑端轮播图', 'textdomain')); ?>"
                     loading="lazy">
            </div>
        <?php endforeach; ?>
    </div>
    <?php if(count($pc) > 1) : ?>
        <div class="swiper-pagination"></div>
    <?php endif; ?>
</div>

<!-- 移动端轮播 -->
<div class="swiper-container swiper-mobile-banner" data-device="mobile">
    <div class="swiper-wrapper">
        <?php foreach ($mobile as $item) : 
            $url = is_array($item) ? ($item['url'] ?? '') : $item;
            if(empty($url)) continue;
        ?>
            <div class="swiper-slide">
                <img src="<?php echo esc_url($url); ?>" 
                     alt="<?php echo esc_attr($item['alt'] ?? __('手机端轮播图', 'textdomain')); ?>"
                     loading="lazy">
            </div>
        <?php endforeach; ?>
    </div>
    <?php if(count($mobile) > 1) : ?>
        <div class="swiper-pagination"></div>
    <?php endif; ?>
</div>

