jQuery(function($) {
    let mediaFrame;
    let currentUploader; // 跟踪当前操作的容器
    let currentDeviceType; // 跟踪当前设备类型

    // 统一初始化媒体库
    function initMediaFrame() {
        mediaFrame = wp.media({
            title: themeSlider.labels.select,
            multiple: true,
            library: { type: 'image' }
        });

        mediaFrame.on('select', function() {
            const attachments = mediaFrame.state().get('selection').toJSON();
            const $input = currentUploader.find('input[type="hidden"]');
            
            // 结构化图片数据
            const images = attachments.map(attach => ({
                url: attach.url,
                device: currentDeviceType,
                time: Date.now()
            }));

            // 更新本地存储和预览
            $input.val(JSON.stringify(images));
            updatePreview(currentUploader, images);
        });
    }

    // 通用预览更新方法
    function updatePreview($container, images) {
        $container.find('.slider-preview').html(
            images.map(img => `
                <div class="preview-item">
                    <img src="${img.url}" style="height:80px;">
                    <a href="#" class="remove-slider" 
                       data-url="${img.url}" 
                       data-device="${img.device}">×</a>
                </div>
            `).join('')
        );
    }

    // 上传按钮事件（整合版）
    $('.wrap').on('click', '.upload-slider', function(e) {
        e.preventDefault();
        currentUploader = $(this).closest('.slider-uploader');
        currentDeviceType = currentUploader.data('device');
        
        if (!mediaFrame) initMediaFrame();
        mediaFrame.open();
    });

    // 删除事件（增强验证）
    $('.slider-uploader').on('click', '.remove-slider', function(e) {
        e.preventDefault();
        const $item = $(this).closest('.preview-item');
        const $container = $(this).closest('.slider-uploader');
        const targetDevice = $container.data('device');
        const targetUrl = $(this).data('url');

        const $input = $container.find('input');
        let images = JSON.parse($input.val() || '[]');
        
        // 双重验证过滤
        images = images.filter(img => 
            !(img.url === targetUrl && img.device === targetDevice)
        );
        
        $input.val(JSON.stringify(images));
        $item.remove();
    });

    // 表单提交（完整版）
    $('form').submit(function(e) {
        e.preventDefault();
        const $form = $(this);
        const $container = $form.find('.slider-uploader');
        const $spinner = $form.find('.spinner');
        const deviceType = $container.data('device');

        // 验证参数
        if (!['pc', 'mobile'].includes(deviceType)) {
            alert('设备类型错误');
            return;
        }

        $spinner.addClass('is-active');
        
        $.post(themeSlider.ajaxurl, {
            action: 'save_slider_images',
            _ajax_nonce: themeSlider.nonce,
            device: deviceType,
            images: $container.find('input').val()
        }).done(function(res) {
            // 动态更新服务器端返回的预览
            if(res.data?.preview) {
                $container.find('.slider-preview').html(res.data.preview);
            }
            alert('保存成功');
        }).fail(function(err) {
            console.error('保存失败:', err);
            alert('保存失败: ' + err.responseText);
        }).always(() => $spinner.removeClass('is-active'));
    });
});
