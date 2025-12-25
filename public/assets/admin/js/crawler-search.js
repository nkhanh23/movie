// Tab switching
function switchTab(tab) {
    if (tab === 'search') {
        document.getElementById('search-panel').style.display = 'block';
        document.getElementById('auto-panel').style.display = 'none';
        document.getElementById('tab-search').classList.add('active');
        document.getElementById('tab-auto').classList.remove('active');
    } else {
        document.getElementById('search-panel').style.display = 'none';
        document.getElementById('auto-panel').style.display = 'block';
        document.getElementById('tab-search').classList.remove('active');
        document.getElementById('tab-auto').classList.add('active');
    }
}

// Search movies
async function searchMovies() {
    const keyword = document.getElementById('search-keyword').value.trim();
    const apiSource = document.getElementById('search-api-source').value;

    if (!keyword) {
        alert('Vui lòng nhập từ khóa tìm kiếm!');
        return;
    }

    const searchBtn = document.getElementById('search-btn');
    searchBtn.disabled = true;
    searchBtn.textContent = '🔄 Đang tìm...';

    try {
        const response = await fetch(`${window.BASE_URL}/admin/crawler/searchMovies?keyword=${encodeURIComponent(keyword)}&source=${apiSource}`);
        const data = await response.json();

        if (data.success && data.movies.length > 0) {
            displaySearchResults(data.movies, apiSource);
        } else {
            document.getElementById('search-results').innerHTML = `
                <div style="text-align: center; padding: 40px; color: #666;">
                    ❌ Không tìm thấy phim nào với từ khóa "${keyword}"
                </div>
            `;
            document.getElementById('search-results').style.display = 'block';
        }
    } catch (error) {
        alert('Lỗi khi tìm kiếm: ' + error.message);
    } finally {
        searchBtn.disabled = false;
        searchBtn.textContent = '🔍 Tìm kiếm';
    }
}

// Display search results
function displaySearchResults(movies, apiSource) {
    const imgBase = 'https://phimimg.com/';

    let html = `
        <div style="background: #1e293b; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="color: #e2e8f0;">
                    <input type="checkbox" id="select-all" onchange="toggleSelectAll(this)" style="margin-right: 10px;">
                    <label for="select-all" style="font-weight: bold; cursor: pointer;">Chọn tất cả</label>
                    <span style="margin-left: 20px; color: #94a3b8;">Tìm thấy ${movies.length} kết quả</span>
                </div>
                <button onclick="crawlSelected()" id="crawl-selected-btn" class="crawl-selected-btn" disabled>
                    🚀 Crawl <span id="selected-count">0</span> phim đã chọn
                </button>
            </div>
        </div>
        <table style="width: 100%; border-collapse: collapse; background: #1e293b; border-radius: 8px; overflow: hidden;">
            <thead>
                <tr style="background: #334155;">
                    <th style="padding: 12px; text-align: left; width: 50px; color: #e2e8f0;">Chọn</th>
                    <th style="padding: 12px; text-align: left; width: 80px; color: #e2e8f0;">Poster</th>
                    <th style="padding: 12px; text-align: left; color: #e2e8f0;">Tên phim</th>
                    <th style="padding: 12px; text-align: left; width: 80px; color: #e2e8f0;">Năm</th>
                    <th style="padding: 12px; text-align: left; width: 120px; color: #e2e8f0;">Loại</th>
                    <th style="padding: 12px; text-align: left; width: 80px; color: #e2e8f0;">Chất lượng</th>
                </tr>
            </thead>
            <tbody>
    `;

    movies.forEach((movie, index) => {
        const typeMap = {
            'single': 'Phim Lẻ',
            'series': 'Phim Bộ',
            'hoathinh': 'Hoạt Hình',
            'tvshows': 'TV Shows'
        };

        const posterUrl = movie.poster_url ? imgBase + movie.poster_url : '';

        html += `
            <tr style="border-bottom: 1px solid #334155;">
                <td style="padding: 12px;">
                    <input type="checkbox" class="movie-checkbox" value="${movie.slug}" onchange="updateSelectedCount()" data-index="${index}">
                </td>
                <td style="padding: 12px;">
                    ${posterUrl ? `<img src="${posterUrl}" alt="${movie.name}" style="width: 60px; height: 80px; object-fit: cover; border-radius: 4px;">` : '<span style="color: #64748b;">N/A</span>'}
                </td>
                <td style="padding: 12px;">
                    <div style="color: #f1f5f9; font-weight: bold;">${movie.name}</div>
                    <div style="color: #94a3b8; font-size: 12px;">${movie.origin_name || ''}</div>
                </td>
                <td style="padding: 12px; color: #cbd5e1;">${movie.year || 'N/A'}</td>
                <td style="padding: 12px; color: #cbd5e1;">${typeMap[movie.type] || movie.type}</td>
                <td style="padding: 12px;">
                    <span style="background: ${movie.quality === 'FHD' ? '#10b981' : movie.quality === 'CAM' ? '#f59e0b' : '#3b82f6'}; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px;">${movie.quality || 'N/A'}</span>
                </td>
            </tr>
        `;
    });

    html += `
            </tbody>
        </table>
    `;

    const resultsDiv = document.getElementById('search-results');
    resultsDiv.innerHTML = html;
    resultsDiv.style.display = 'block';
    resultsDiv.setAttribute('data-api-source', apiSource);
}

// Toggle select all
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.movie-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    updateSelectedCount();
}

// Update selected count
function updateSelectedCount() {
    const selected = document.querySelectorAll('.movie-checkbox:checked').length;
    document.getElementById('selected-count').textContent = selected;
    document.getElementById('crawl-selected-btn').disabled = selected === 0;
}

// Crawl selected movies
async function crawlSelected() {
    const checkboxes = document.querySelectorAll('.movie-checkbox:checked');
    const slugs = Array.from(checkboxes).map(cb => cb.value);
    const apiSource = document.getElementById('search-results').getAttribute('data-api-source');

    if (slugs.length === 0) {
        alert('Vui lòng chọn ít nhất 1 phim!');
        return;
    }

    // Hide search results, show crawl output
    document.getElementById('search-results').style.display = 'none';
    document.getElementById('progress-info').style.display = 'block';
    document.getElementById('crawl-output').innerHTML = '';

    // Update status
    const statusCard = document.getElementById('status-card');
    statusCard.innerHTML = `<h3 style="color: #0c4a6e; margin: 0;">🔄 Đang crawl ${slugs.length} phim đã chọn...</h3>`;
    statusCard.style.background = '#e0f2fe';
    statusCard.style.borderColor = '#0284c7';

    try {
        const formData = new FormData();
        formData.append('slugs', JSON.stringify(slugs));

        const response = await fetch(`${window.BASE_URL}/admin/crawler/sync-api?source=${apiSource}`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            const outputDiv = document.getElementById('crawl-output');
            outputDiv.innerHTML = data.output;
            outputDiv.scrollTop = outputDiv.scrollHeight;

            // Update status to complete
            statusCard.innerHTML = `
                <h1 style="color: #0c5460; font-size: 28px; margin-bottom: 12px;">🎉 HOÀN TẤT!</h1>
                <p style="color: #0c5460; margin-bottom: 20px;">Đã crawl thành công ${slugs.length} phim!</p>
                <a href="${window.BASE_URL}/admin/film/list" class="btn btn-primary" 
                   style="display: inline-block; background: #2563eb; color: white; padding: 10px 24px; text-decoration: none; border-radius: 6px; margin-right: 10px;">
                    📋 Xem danh sách phim
                </a>
                <button onclick="location.reload()" class="btn btn-secondary"
                   style="display: inline-block; background: #6c757d; color: white; padding: 10px 24px; border: none; border-radius: 6px; cursor: pointer;">
                    🔄 Tìm kiếm lại
                </button>
            `;
            statusCard.style.background = '#d1ecf1';
            statusCard.style.borderColor = '#0c5460';
        }
    } catch (error) {
        statusCard.innerHTML = `<h3 style="color: #dc2626; margin: 0;">❌ Lỗi: ${error.message}</h3>`;
        statusCard.style.background = '#fee2e2';
        statusCard.style.borderColor = '#dc2626';
    }
}
