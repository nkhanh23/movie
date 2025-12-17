<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('admin/header');
layout('admin/sidebar');
?>

<section id="crawler-view" class="content-section active" style="padding: 30px;">
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2>🚀 Crawler Phim Từ KKPhim API</h2>
            <p style="color: #666; margin: 5px 0 0 0;">
                Trang hiện tại: <strong id="current-page" style="color: #e63946; font-size: 20px;">1</strong>
            </p>
        </div>
        <button id="stop-btn" onclick="stopCrawler()"
            class="btn btn-danger"
            style="background: #dc2626; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
            ⏸ DỪNG LẠI
        </button>
    </div>

    <div id="status-card" class="card" style="text-align: center; padding: 30px; background: #e0f2fe; border: 2px solid #0284c7; border-radius: 12px; margin-bottom: 20px;">
        <h3 style="color: #0c4a6e; margin: 0;">⏳ Đang khởi động crawler...</h3>
    </div>

    <!-- Kết quả crawl -->
    <div class="card" style="background: #1e293b; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; min-height: 300px;">
        <div id="crawl-output" style="font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.8; max-height: 600px; overflow-y: auto; color: #e2e8f0;">
            <!-- Kết quả sẽ được thêm vào đây -->
        </div>
    </div>
</section>

<style>
    /* Style cho output crawler */
    #crawl-output div {
        border-bottom: 1px solid #334155;
        padding: 8px 0;
    }

    #crawl-output strong {
        color: #60a5fa;
        font-weight: bold;
    }

    #crawl-output span[style*="color:blue"] {
        color: #3b82f6 !important;
    }

    #crawl-output span[style*="color:green"] {
        color: #22c55e !important;
    }

    #crawl-output span[style*="color:orange"] {
        color: #f97316 !important;
    }

    #crawl-output span[style*="color:#666"] {
        color: #94a3b8 !important;
    }

    #crawl-output span[style*="font-weight:bold"] {
        color: #fbbf24 !important;
    }
</style>

<script>
    let currentPage = 1;
    let isCrawling = true;

    async function crawlPage(page) {
        if (!isCrawling) return;

        // Update page number
        document.getElementById('current-page').textContent = page;

        // Update status
        const statusCard = document.getElementById('status-card');
        statusCard.innerHTML = `<h3 style="color: #0c4a6e; margin: 0;">🔄 Đang crawl trang ${page}...</h3>`;
        statusCard.style.background = '#e0f2fe';
        statusCard.style.borderColor = '#0284c7';

        try {
            const response = await fetch('<?php echo _HOST_URL; ?>/admin/crawler/sync-api?page=' + page);
            const data = await response.json();

            if (data.success) {
                // Hiển thị kết quả
                const outputDiv = document.getElementById('crawl-output');
                outputDiv.innerHTML += data.output;

                // Auto scroll
                outputDiv.scrollTop = outputDiv.scrollHeight;

                // Update status
                statusCard.innerHTML = `<h3 style="color: #155724; margin: 0;">✅ Hoàn thành trang ${page}</h3>`;
                statusCard.style.background = '#d4edda';
                statusCard.style.borderColor = '#28a745';

                // Nếu còn trang, crawl tiếp sau 1 giây
                if (data.hasMore && isCrawling) {
                    currentPage++;
                    setTimeout(() => crawlPage(currentPage), 1000);
                } else {
                    // Hoàn tất
                    statusCard.innerHTML = `
                    <h1 style="color: #0c5460; font-size: 28px; margin-bottom: 12px;">🎉 HOÀN TẤT!</h1>
                    <p style="color: #0c5460; margin-bottom: 20px;">Đã crawl hết ${page} trang.</p>
                    <a href="<?php echo _HOST_URL; ?>/admin/film/list" class="btn btn-primary" 
                       style="display: inline-block; background: #2563eb; color: white; padding: 10px 24px; text-decoration: none; border-radius: 6px; margin-right: 10px;">
                        📋 Xem danh sách phim
                    </a>
                    <a href="<?php echo _HOST_URL; ?>/admin/crawler" class="btn btn-secondary"
                       style="display: inline-block; background: #6c757d; color: white; padding: 10px 24px; text-decoration: none; border-radius: 6px;">
                        🔄 Crawl lại
                    </a>
                `;
                    statusCard.style.background = '#d1ecf1';
                    statusCard.style.borderColor = '#0c5460';
                    isCrawling = false;
                }
            }
        } catch (error) {
            statusCard.innerHTML = `<h3 style="color: #dc2626; margin: 0;">❌ Lỗi: ${error.message}</h3>`;
            statusCard.style.background = '#fee2e2';
            statusCard.style.borderColor = '#dc2626';
            isCrawling = false;
        }
    }

    function stopCrawler() {
        isCrawling = false;
        const statusCard = document.getElementById('status-card');
        statusCard.innerHTML = `
        <h1 style="color: #dc2626; font-size: 28px; margin-bottom: 12px;">⚠ ĐÃ DỪNG!</h1>
        <p style="color: #666; margin-bottom: 20px;">Crawler đã dừng tại trang ${currentPage}.</p>
        <a href="<?php echo _HOST_URL; ?>/admin/crawler" class="btn btn-primary"
           style="display: inline-block; background: #2563eb; color: white; padding: 10px 24px; text-decoration: none; border-radius: 6px;">
            🔄 Bắt đầu lại
        </a>
    `;
        statusCard.style.background = '#fff3cd';
        statusCard.style.borderColor = '#ffc107';
    }

    // Bắt đầu crawl khi trang load
    window.addEventListener('load', () => {
        crawlPage(currentPage);
    });
</script>