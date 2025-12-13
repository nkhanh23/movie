<div id="intro-splash" class="intro-splash">
    <div class="splash-video-container">
        <video id="splash-video" playsinline muted>
            <source src="<?php echo _HOST_URL_PUBLIC; ?>/video/logo_animation_5.mp4" type="video/mp4">
        </video>
    </div>
</div>

<style>
    .intro-splash {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: #000;
        z-index: 99999;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: opacity 0.5s ease-out, visibility 0.5s;
        /* Giảm thời gian fade xuống 0.5s cho nhanh */
    }

    .intro-splash.hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    .splash-video-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .splash-video-container video {
        width: 100%;
        height: 100%;
        /* KEY FIX: cover giúp video phủ kín màn hình bất chấp tỷ lệ khung hình */
        object-fit: cover;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const splash = document.getElementById('intro-splash');
        const video = document.getElementById('splash-video');

        // Thời gian hiệu ứng mờ dần khi tắt (0.5 giây)
        const fadeDuration = 500;

        // Kiểm tra session
        const hasSeenIntro = sessionStorage.getItem('has_seen_intro');

        if (!hasSeenIntro) {
            // 1. Cố gắng chạy video
            video.play().catch(e => {
                // Nếu trình duyệt chặn autoplay -> Tắt luôn intro ngay lập tức để không làm phiền user
                console.log("Autoplay blocked - Closing intro");
                closeSplash();
            });

            // 2. [ĐÃ SỬA] Khi video chạy xong -> Tắt NGAY LẬP TỨC
            video.addEventListener('ended', function() {
                closeSplash(); // Gọi trực tiếp, không dùng setTimeout nữa
            });

        } else {
            // Đã xem -> Ẩn ngay
            splash.style.display = 'none';
        }

        function closeSplash() {
            if (splash.classList.contains('hidden')) return;

            splash.classList.add('hidden');
            // Dừng video ngay lập tức (nếu đang chạy)
            video.pause();

            sessionStorage.setItem('has_seen_intro', 'true');

            // Xóa khỏi DOM sau khi hiệu ứng mờ kết thúc
            setTimeout(() => {
                splash.remove();
            }, fadeDuration);
        }
    });
</script>