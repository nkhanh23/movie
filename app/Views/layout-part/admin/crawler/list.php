<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('admin/header');
layout('admin/sidebar');
?>

<section id="crawler-view" class="content-section active" style="padding: 30px;">
    <div class="page-header" style="margin-bottom: 20px;">
        <h2>Crawler Phim Từ PhimAPI</h2>
        <p style="color: #666; margin: 5px 0 0 0;">Thu thập phim tự động từ API với TMDB enrichment</p>
    </div>

    <!-- Last Crawled Page Info -->
    <?php
    $crawlerController = new CrawlerController();
    $lastPage = $crawlerController->getLastCrawledPage();
    ?>

    <?php if ($lastPage > 1): ?>
        <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong style="color: #92400e;">📌 Trang cao nhất đã crawl:</strong>
                    <span style="color: #d97706; font-size: 18px; font-weight: bold; margin-left: 10px;">Trang <?php echo $lastPage; ?></span>
                    <small style="display: block; color: #78350f; margin-top: 5px;">
                        Gợi ý: Tiếp tục từ trang <?php echo $lastPage + 1; ?>
                    </small>
                </div>
                <button onclick="continueFromLast(<?php echo $lastPage + 1; ?>)"
                    style="background: #f59e0b; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
                    ▶️ Tiếp tục từ trang <?php echo $lastPage + 1; ?>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Control Panel -->
    <div class="card" style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <!-- Tabs -->
        <div style="display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 2px solid #e5e7eb;">
            <button onclick="switchTab('search')" id="tab-search" class="tab-btn active">
                🔍 Tìm kiếm phim
            </button>
            <button onclick="switchTab('auto')" id="tab-auto" class="tab-btn">
                📄 Crawl tự động
            </button>
        </div>

        <!-- Search Tab -->
        <div id="search-panel" class="tab-panel">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">
                        🔍 Nhập tên phim
                    </label>
                    <input type="text" id="search-keyword" placeholder="VD: Avatar, One Piece..."
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">
                        🌐 Nguồn API
                    </label>
                    <select id="search-api-source" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        <option value="phimapi">PhimAPI</option>
                        <option value="ophim">OPhim</option>
                    </select>
                </div>
            </div>
            <button onclick="searchMovies()" id="search-btn"
                style="width: 100%; background: #3b82f6; color: white; padding: 12px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 16px;">
                🔍 Tìm kiếm
            </button>

            <!-- Search Results Table -->
            <div id="search-results" style="display: none; margin-top: 20px;">
                <!-- Results will be inserted here -->
            </div>
        </div>

        <!-- Auto Crawl Tab -->
        <div id="auto-panel" class="tab-panel" style="display: none;">
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">
                        📄 Trang bắt đầu
                    </label>
                    <input type="number" id="start-page" value="1" min="1"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">
                        📊 Số trang crawl
                    </label>
                    <input type="number" id="page-limit" value="999" min="1"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    <small style="color: #666;">999 = crawl tất cả</small>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">
                        ⚡ Phim/trang
                    </label>
                    <select id="movies-per-page" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="0">Tất cả (~24 phim)</option>
                        <option value="5" selected>5 phim (Hosting Free)</option>
                        <option value="10">10 phim</option>
                        <option value="15">15 phim</option>
                    </select>
                    <small style="color: #f59e0b;">⚠️ Hosting free nên chọn 5</small>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">
                        🌐 Nguồn API
                    </label>
                    <select id="api-source" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="phimapi">PhimAPI (phimapi.com)</option>
                        <option value="ophim">OPhim (ophim1.com)</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">
                        🎬 Loại danh sách
                    </label>
                    <select id="list-type" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="phim-moi-cap-nhat">📺 Phim mới cập nhật</option>
                        <option value="phim-chieu-rap">🎬 Phim chiếu rạp</option>
                        <option value="phim-bo">📺 Phim bộ</option>
                        <option value="phim-le">🎞️ Phim lẻ</option>
                        <option value="hoat-hinh">🎨 Hoạt hình</option>
                        <option value="tv-shows">📡 TV Shows</option>
                        <option value="phim-vietsub">🇻🇳 Phim Vietsub</option>
                    </select>
                    <small style="color: #10b981;">Chọn loại phim cần crawl</small>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">
                        🔄 Hướng crawl
                    </label>
                    <select id="crawl-direction" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="asc">⬆️ Tăng dần (1 → n)</option>
                        <option value="desc">⬇️ Giảm dần (n → 1)</option>
                    </select>
                    <small style="color: #3b82f6;">Chiều crawl các trang</small>
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button onclick="startCrawler()" id="start-btn"
                    style="flex: 1; background: #10b981; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 16px;">
                    🚀 BẮT ĐẦU CRAWL
                </button>
                <button onclick="stopCrawler()" id="stop-btn" disabled
                    style="flex: 1; background: #dc2626; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 16px; opacity: 0.5;">
                    ⏸ DỪNG LẠI
                </button>
            </div>
        </div>
    </div>

    <!-- Status Card -->
    <div id="status-card" class="card" style="text-align: center; padding: 30px; background: #f3f4f6; border: 2px solid #d1d5db; border-radius: 12px; margin-bottom: 20px;">
        <h3 style="color: #6b7280; margin: 0;">⏳ Nhấn "BẮT ĐẦU CRAWL" để bắt đầu...</h3>
    </div>

    <!-- Progress Info -->
    <div id="progress-info" style="display: none; background: #eff6ff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #3b82f6;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <strong style="color: #1e40af;">Trang hiện tại:</strong>
                <span id="current-page" style="color: #e63946; font-size: 20px; font-weight: bold;">0</span>
                <span style="color: #666;"> / </span>
                <span id="total-pages" style="color: #10b981; font-size: 16px; font-weight: bold;">0</span>
            </div>
            <div>
                <strong style="color: #1e40af;">Phim đã crawl:</strong>
                <span id="movie-count" style="color: #10b981; font-size: 20px; font-weight: bold;">0</span>
            </div>
        </div>
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

    input:disabled {
        background: #f3f4f6;
        cursor: not-allowed;
    }

    /* Tab styles */
    .tab-btn {
        padding: 12px 24px;
        background: white;
        border: none;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        font-weight: bold;
        color: #666;
        transition: all 0.3s;
    }

    .tab-btn.active {
        color: #3b82f6;
        border-bottom-color: #3b82f6;
    }

    .tab-btn:hover {
        color: #3b82f6;
    }

    /* Crawl selected button */
    .crawl-selected-btn {
        background: #10b981;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
    }

    .crawl-selected-btn:disabled {
        background: #9ca3af;
        cursor: not-allowed;
    }
</style>

<script>
    // Set base URL for external JS file
    window.BASE_URL = '<?php echo _HOST_URL; ?>';

    let currentPage = 1;
    let isCrawling = false;
    let movieCount = 0;
    let pageLimit = 999;
    let searchQuery = '';

    function continueFromLast(nextPage) {
        // Switch to auto tab first
        switchTab('auto');
        document.getElementById('start-page').value = nextPage;
        document.getElementById('page-limit').value = 999;
        startCrawler();
    }

    let crawlDirection = 'asc'; // 'asc' = tăng dần, 'desc' = giảm dần
    let pagesProcessed = 0;

    function startCrawler() {
        // Lấy giá trị từ form
        const startPage = parseInt(document.getElementById('start-page').value) || 1;
        pageLimit = parseInt(document.getElementById('page-limit').value) || 999;
        crawlDirection = document.getElementById('crawl-direction').value;

        currentPage = startPage;
        isCrawling = true;
        movieCount = 0;
        pagesProcessed = 0;

        // Update UI
        document.getElementById('start-btn').disabled = true;
        document.getElementById('stop-btn').disabled = false;
        document.getElementById('stop-btn').style.opacity = '1';
        document.getElementById('start-btn').style.opacity = '0.5';

        // Disable inputs
        document.getElementById('start-page').disabled = true;
        document.getElementById('page-limit').disabled = true;
        document.getElementById('api-source').disabled = true;
        document.getElementById('movies-per-page').disabled = true;
        document.getElementById('list-type').disabled = true;
        document.getElementById('crawl-direction').disabled = true;

        // Clear output
        document.getElementById('crawl-output').innerHTML = '';

        // Show progress
        document.getElementById('progress-info').style.display = 'block';
        document.getElementById('total-pages').textContent = pageLimit;

        // Start crawling
        crawlPage(currentPage);
    }

    async function crawlPage(page, retryCount = 0) {
        if (!isCrawling) return;

        const MAX_RETRIES = 3;
        const RETRY_DELAY = 3000; // 3 giây

        // Check limit - đếm số trang đã xử lý thay vì so sánh page
        if (pagesProcessed >= pageLimit) {
            finishCrawler('Đã đạt giới hạn số trang!');
            return;
        }

        // Check minimum page for desc direction
        if (crawlDirection === 'desc' && page < 1) {
            finishCrawler('Đã crawl đến trang 1!');
            return;
        }

        // Update page number
        document.getElementById('current-page').textContent = page;

        // Update status
        const statusCard = document.getElementById('status-card');
        const retryText = retryCount > 0 ? ` (thử lần ${retryCount + 1})` : '';
        statusCard.innerHTML = `<h3 style="color: #0c4a6e; margin: 0;">🔄 Đang crawl trang ${page}...${retryText}</h3>`;
        statusCard.style.background = '#e0f2fe';
        statusCard.style.borderColor = '#0284c7';

        try {
            let url = '<?php echo _HOST_URL; ?>/admin/crawler/sync-api?page=' + page;
            if (searchQuery) {
                url += '&search=' + encodeURIComponent(searchQuery);
            }
            // Add API source parameter
            const apiSource = document.getElementById('api-source').value;
            url += '&source=' + apiSource;

            // Add list type parameter
            const listType = document.getElementById('list-type').value;
            url += '&list_type=' + listType;

            // Add limit parameter
            const moviesPerPage = document.getElementById('movies-per-page').value;
            if (moviesPerPage > 0) {
                url += '&limit=' + moviesPerPage;
            }

            // Sử dụng AbortController để timeout
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 120000); // 2 phút timeout

            const response = await fetch(url, {
                signal: controller.signal
            });
            clearTimeout(timeoutId);

            const rawText = await response.text();

            // Try to parse JSON
            let data;
            try {
                data = JSON.parse(rawText);
            } catch (parseError) {
                console.error('JSON parse error. Raw response:', rawText.substring(0, 500));

                // Retry if possible
                if (retryCount < MAX_RETRIES) {
                    statusCard.innerHTML = `<h3 style="color: #f59e0b; margin: 0;">⚠️ Lỗi parse JSON trang ${page}, đang thử lại (${retryCount + 1}/${MAX_RETRIES})...</h3>`;
                    statusCard.style.background = '#fef3c7';
                    if (isCrawling) {
                        setTimeout(() => crawlPage(page, retryCount + 1), RETRY_DELAY);
                    }
                    return;
                } else {
                    // Skip page after max retries
                    statusCard.innerHTML = `<h3 style="color: #dc2626; margin: 0;">❌ Bỏ qua trang ${page} sau ${MAX_RETRIES} lần thử</h3>`;
                    if (isCrawling) {
                        pagesProcessed++;
                        currentPage = crawlDirection === 'asc' ? currentPage + 1 : currentPage - 1;
                        setTimeout(() => crawlPage(currentPage, 0), 2000);
                    }
                    return;
                }
            }

            if (data.success) {
                // Hiển thị kết quả
                const outputDiv = document.getElementById('crawl-output');
                outputDiv.innerHTML += data.output;

                // Count movies (count [NEW] and [UPDATE] tags)
                const matches = data.output.match(/\[(NEW|UPDATE)\]/g);
                if (matches) {
                    movieCount += matches.length;
                    document.getElementById('movie-count').textContent = movieCount;
                }

                // Auto scroll
                outputDiv.scrollTop = outputDiv.scrollHeight;

                // Update status
                statusCard.innerHTML = `<h3 style="color: #155724; margin: 0;">✅ Hoàn thành trang ${page}</h3>`;
                statusCard.style.background = '#d4edda';
                statusCard.style.borderColor = '#28a745';

                // Nếu còn trang, crawl tiếp sau 1.5 giây
                pagesProcessed++;
                const hasMorePages = crawlDirection === 'asc' ? data.hasMore : (currentPage > 1);
                if (hasMorePages && isCrawling) {
                    currentPage = crawlDirection === 'asc' ? currentPage + 1 : currentPage - 1;
                    setTimeout(() => crawlPage(currentPage, 0), 1500);
                } else {
                    finishCrawler('Đã crawl hết tất cả!');
                }
            } else {
                // API trả về success: false
                if (retryCount < MAX_RETRIES) {
                    statusCard.innerHTML = `<h3 style="color: #f59e0b; margin: 0;">⚠️ API error trang ${page}, đang thử lại (${retryCount + 1}/${MAX_RETRIES})...</h3>`;
                    if (isCrawling) {
                        setTimeout(() => crawlPage(page, retryCount + 1), RETRY_DELAY);
                    }
                } else {
                    pagesProcessed++;
                    currentPage = crawlDirection === 'asc' ? currentPage + 1 : currentPage - 1;
                    setTimeout(() => crawlPage(currentPage, 0), 2000);
                }
            }
        } catch (error) {
            console.error('Fetch error:', error);

            // Retry on network errors
            if (retryCount < MAX_RETRIES) {
                statusCard.innerHTML = `<h3 style="color: #f59e0b; margin: 0;">⚠️ Lỗi mạng trang ${page}: ${error.message}, đang thử lại (${retryCount + 1}/${MAX_RETRIES})...</h3>`;
                statusCard.style.background = '#fef3c7';
                if (isCrawling) {
                    setTimeout(() => crawlPage(page, retryCount + 1), RETRY_DELAY);
                }
            } else {
                // Skip page after max retries
                statusCard.innerHTML = `<h3 style="color: #dc2626; margin: 0;">❌ Bỏ qua trang ${page}, chuyển sang trang tiếp</h3>`;
                if (isCrawling) {
                    pagesProcessed++;
                    currentPage = crawlDirection === 'asc' ? currentPage + 1 : currentPage - 1;
                    setTimeout(() => crawlPage(currentPage, 0), 2000);
                }
            }
        }
    }

    function stopCrawler() {
        isCrawling = false;
        const statusCard = document.getElementById('status-card');
        statusCard.innerHTML = `
        <h1 style="color: #dc2626; font-size: 28px; margin-bottom: 12px;">⚠ ĐÃ DỪNG!</h1>
        <p style="color: #666; margin-bottom: 20px;">Crawler đã dừng tại trang ${currentPage}. Đã crawl ${movieCount} phim.</p>
    `;
        statusCard.style.background = '#fff3cd';
        statusCard.style.borderColor = '#ffc107';
        resetUI();
    }

    function finishCrawler(message) {
        isCrawling = false;
        const statusCard = document.getElementById('status-card');
        const totalPages = currentPage - parseInt(document.getElementById('start-page').value) + 1;
        statusCard.innerHTML = `
            <h1 style="color: #0c5460; font-size: 28px; margin-bottom: 12px;">🎉 HOÀN TẤT!</h1>
            <p style="color: #0c5460; margin-bottom: 20px;">${message}<br/>Đã crawl ${totalPages} trang, tổng ${movieCount} phim.</p>
            <a href="<?php echo _HOST_URL; ?>/admin/film/list" class="btn btn-primary" 
               style="display: inline-block; background: #2563eb; color: white; padding: 10px 24px; text-decoration: none; border-radius: 6px; margin-right: 10px;">
                � Xem danh sách phim
            </a>
            <a href="<?php echo _HOST_URL; ?>/admin/crawler" class="btn btn-secondary"
               style="display: inline-block; background: #6c757d; color: white; padding: 10px 24px; text-decoration: none; border-radius: 6px;">
                🔄 Crawl lại
            </a>
        `;
        statusCard.style.background = '#d1ecf1';
        statusCard.style.borderColor = '#0c5460';
        resetUI();
    }

    function resetUI() {
        document.getElementById('start-btn').disabled = false;
        document.getElementById('stop-btn').disabled = true;
        document.getElementById('stop-btn').style.opacity = '0.5';
        document.getElementById('start-btn').style.opacity = '1';

        document.getElementById('start-page').disabled = false;
        document.getElementById('page-limit').disabled = false;
        document.getElementById('api-source').disabled = false;
        document.getElementById('movies-per-page').disabled = false;
        document.getElementById('list-type').disabled = false;
        document.getElementById('crawl-direction').disabled = false;
    }
</script>

<!-- Include search-select-crawl functionality -->
<script src="<?php echo _HOST_URL; ?>/public/assets/admin/js/crawler-search.js"></script>

<?php layout('admin/footer'); ?>