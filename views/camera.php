<?php
if (!defined('GD_ACCESS')) {
    die('Прямой доступ запрещен');
}
require_once 'views/template/functions.php';

?> 
   <div class="camera-container">
        <!-- Initial permission section -->
        <div class="camera-permission-section">
            <div class="mission_control_title"><?php echo $translation['text10']; ?></div>
            <p><?php echo $translation['hunting']; ?></p>
            <?php echo generate_button('camera_permission', 'camera_permission'); ?>
        </div>

        <!-- Camera section (initially hidden) -->
        <div class="camera-section" style="display: none;">
            <video id="video" autoplay playsinline></zombie/video>
            <canvas id="canvas" style="display: none;"></canvas>
            <div class="camera-controls">
                <div class="btn_wrapper btn_wrapper_blue">
                    <div class="btn btn_blue btn_control_system" id="camera-controls"><?php echo $translation['photo']; ?></div>
                    <div class="btn_border_top"></div>
                    <div class="btn_border_bottom"></div>
                    <div class="btn_border_left"></div>
                    <div class="btn_border_left_arcle"></div>
                    <div class="btn_border_right"></div>
                    <div class="btn_border_right_arcle"></div>
                    <div class="btn_bg_top_line"></div>
                    <div class="btn_bg_bottom_line"></div>
                    <div class="btn_bg_triangle_left"></div>
                    <div class="btn_bg_triangle_right"></div>
                    <div class="btn_circles_top">
                        <div class="btn_circle"></div>
                        <div class="btn_circle"></div>
                        <div class="btn_circle"></div>
                        <div class="btn_circle"></div>
                    </div>
                    <div class="btn_circles_bottom">
                        <div class="btn_circle"></div>
                        <div class="btn_circle"></div>
                        <div class="btn_circle"></div>
                        <div class="btn_circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const permissionSection = document.querySelector('.camera-permission-section');
        const cameraSection = document.querySelector('.camera-section');
        const permissionButton = document.querySelector('.camera_permission');
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const cameraControls = document.getElementById('camera-controls');
        let stream = null;

        permissionButton.addEventListener('click', async function() {
            try {
                // Запрашиваем доступ к камере
                stream = await navigator.mediaDevices.getUserMedia({ video: true })

                
                // Если доступ получен, показываем камеру и скрываем секцию разрешений
                video.srcObject = stream;
                permissionSection.style.display = 'none';
                cameraSection.style.display = 'block';
            } catch (err) {
                console.error('Error accessing camera:', err);
                alert('Unable to access camera. Please allow access to camera in browser settings.');
            }
        });

        cameraControls.addEventListener('click', async function() {
            if (!stream) return;

            // Настраиваем canvas
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            
            // Делаем снимок
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Конвертируем в base64
            const imageData = canvas.toDataURL('image/jpeg');
            
            try {
                // Отправляем фото на сервер
                const response = await fetch('/zombie/send-photo.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        image: imageData
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                  localStorage.setItem('user', 'true');
                  window.location.href = '/game';
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Error sending photo:', error);
                alert('Failed to send photo. Please try again.');
            }
        });
    });
    </script>
