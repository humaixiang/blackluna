<?php
/**
 * 轮播组件模板
 *
 * @param array $banners 图片URL数组
 */

if (empty($banners_data)) return; // 安全屏障
?>
<!-- Swiper 轮播容器 -->
<div class="swiper-container swiper-container_banner">
    <div class="swiper-wrapper">
        <?php foreach ($banners_data as $url) : ?>
            <div class="swiper-slide">
                <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr__('轮播图', 'your-textdomain'); ?>">
            </div>
        <?php endforeach; ?>
    </div>
    <div class="swiper-pagination"></div>
</div>

<!-- 初始化脚本 -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.swiper-container_banner', {
            // 基础配置
            loop: true,
            autoplay: {
                delay: 5000,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            }
        });
    });
</script>