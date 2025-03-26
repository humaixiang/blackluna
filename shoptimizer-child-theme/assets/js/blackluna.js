document.addEventListener('DOMContentLoaded', function() {
        var mySwiper_pc = new Swiper('.swiper-pc-banner', {
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
        var mySwiper_wap = new Swiper('.swiper-mobile-banner', {
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