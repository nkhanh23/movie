<div id="intro-splash" class="intro-splash">
    <div class="splash-content">
        <img id="splash-gif"
            src="<?php echo _HOST_URL_PUBLIC; ?>/img/intro_splash.gif"
            alt="Loading..." />
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
    }

    .intro-splash.hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    .splash-content {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .splash-content img {
        /* object-fit: cover nếu bạn muốn GIF tràn màn hình như video cũ 
           hoặc contain nếu muốn hiện nguyên khung hình GIF */
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const splash = document.getElementById('intro-splash');
        const fadeDuration = 500; // Khớp với transition CSS

        // Kiểm tra session để tránh lặp lại intro trong một phiên làm việc
        const hasSeenIntro = sessionStorage.getItem('has_seen_intro');

        if (!hasSeenIntro) {
            const gifDuration = 3000;

            setTimeout(() => {
                closeSplash();
            }, gifDuration);

        } else {
            // Đã xem rồi thì ẩn ngay lập tức
            splash.style.display = 'none';
            splash.remove();
        }

        function closeSplash() {
            if (!splash || splash.classList.contains('hidden')) return;

            splash.classList.add('hidden');
            sessionStorage.setItem('has_seen_intro', 'true');

            // Xóa khỏi DOM sau khi hiệu ứng mờ kết thúc để nhẹ máy
            setTimeout(() => {
                splash.remove();
            }, fadeDuration);
        }
    });
</script>