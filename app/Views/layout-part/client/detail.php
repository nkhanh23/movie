<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('client/header');
$favClass = $movieIsFavorited ? 'is-favorited' : '';

// echo '<pre>';
// print_r($movieDetail);
// echo '</pre>';
// die();
?>

<body class="font-display text-white overflow-x-hidden min-h-screen relative">
    <div class="absolute inset-0 z-0 overflow-hidden">
        <div class="absolute -top-1/4 -left-1/4 size-1/2 rounded-full bg-primary/10 blur-3xl animate-[spin_20s_linear_infinite]"></div>
        <div class="absolute -bottom-1/4 -right-1/4 size-1/2 rounded-full bg-secondary/10 blur-3xl animate-[spin_25s_linear_infinite_reverse]"></div>
    </div>

    <div class="relative min-h-screen w-full flex-col px-4 pb-4 sm:px-6 sm:pb-6 md:px-8 md:pb-8 pt-0">


        <div class="movie-hero-container">
            <div class="hero-image-wrapper">
                <img src="<?php echo $movieDetail['thumbnail']; ?>" alt="<?php echo $movieDetail['tittle']; ?>"
                    class="hero-img">
            </div>

            <div class="hero-gradient-overlay"></div>
        </div>

        <div class="relative z-20 mx-auto max-w-7xl">
            <main class="mt-32 grid grid-cols-1 lg:grid-cols-3 lg:gap-8">
                <!-- Movie Poster -->
                <div class="lg:col-span-1 flex justify-center items-start">
                    <div class="glow-border w-72 mx-auto sticky top-8">
                        <div class="w-full bg-center bg-no-repeat bg-cover flex flex-col justify-end overflow-hidden aspect-[2/3] rounded-xl"
                            data-alt="<?php echo $movieDetail['tittle']; ?>"
                            style='background-image: url("<?php echo $movieDetail['poster_url']; ?>");'></div>
                        <div class="flex items-center gap-2">
                        </div>
                    </div>
                </div>

                <!-- Movie Info -->
                <div class="lg:col-span-2 mt-8 lg:mt-0 flex flex-col gap-6">
                    <div class="glass-panel p-6 rounded-xl">
                        <h1 class="text-white tracking-light text-4xl md:text-5xl font-bold leading-tight">
                            <?php echo $movieDetail['tittle']; ?></h1>

                        <div class="movie-meta-tags">
                            <div class="glass-tag tag-imdb">
                                IMDb <span><?php echo $movieDetail['imdb_rating']; ?></span>
                            </div>

                            <div class="glass-tag tag-age">T16</div>

                            <div class="glass-tag tag-info">
                                <?php echo $movieDetail['release_year']; ?>
                            </div>

                            <div class="glass-tag tag-info">
                                <?php echo convertMinutesToHours($movieDetail['duration']); ?>
                            </div>
                        </div>

                        <?php
                        $genres = explode(',', $movieDetail['genre_name']);
                        ?>
                        <div class="movie-genres">
                            <?php foreach ($genres as $genre) { ?>
                                <a href="#" class="genre-pill">
                                    <?php echo trim($genre); ?>
                                </a>
                            <?php } ?>
                        </div>

                        <p class="text-white/80 mt-4 text-base leading-relaxed">
                            <?php echo $movieDetail['description']; ?></p>
                        <div class="flex flex-wrap gap-3 mt-6">
                            <button onclick="window.location.href='<?php echo _HOST_URL; ?>/watch?id=<?php echo $movieDetail['id']; ?>'"
                                class="button-glow flex flex-1 sm:flex-none min-w-[84px] items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] transition-transform hover:scale-105">
                                <span class="material-symbols-outlined mr-2">play_arrow</span>
                                <span class="truncate">Xem Ngay</span>
                            </button>
                            <button
                                class="button-glow flex flex-1 sm:flex-none min-w-[84px] items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-white/10 hover:bg-white/20 text-white text-base font-bold leading-normal tracking-[0.015em] transition-transform hover:scale-105 js-favorite-btn <?= $favClass ?>"
                                data-movie-id="<?php echo $movieDetail['id']; ?>">
                                <span class="material-symbols-outlined mr-2">favorite</span>
                                <span class="truncate">Thêm vào yêu thích</span>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-4">
                            <div class="relative inline-block text-left" id="season-dropdown-container">
                                <?php
                                $isSeries = ($movieDetail['type_id'] == 2);
                                $hasSeasons = ($isSeries && !empty($seasonDetail));
                                //Phim bộ có season
                                if ($hasSeasons): ?>
                                    <?php
                                    $currentDisplayName = "Chọn Phần";
                                    if (!empty($seasonDetail)) {
                                        foreach ($seasonDetail as $item) {
                                            if ($item['id'] == $currentSeasonId) {
                                                $currentDisplayName = $item['name'];
                                                break;
                                            }
                                        }
                                    }
                                    ?>
                                    <button onclick="toggleSeasonDropdown()"
                                        class="flex items-center gap-2 text-white font-bold text-lg hover:text-primary transition-colors group focus:outline-none">
                                        <span class="material-symbols-outlined text-primary">sort</span>
                                        <span id="current-season-text"><?php echo $currentDisplayName; ?></span>
                                        <span id="season-arrow"
                                            class="material-symbols-outlined text-sm transition-transform duration-300">expand_more</span>
                                    </button>

                                    <div id="season-list"
                                        class="hidden absolute left-0 z-50 mt-2 w-56 origin-top-left rounded-xl bg-[#202331]/95 backdrop-blur-xl border border-white/10 shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none transform transition-all duration-200">
                                        <div class="py-1">
                                            <?php foreach ($seasonDetail as $item): ?>
                                                <?php
                                                $isActive = ($item['id'] == $currentSeasonId);
                                                $textClass = $isActive ? 'text-primary font-bold bg-white/5' : 'text-gray-300 hover:bg-white/5';
                                                $iconClass = $isActive ? '' : 'invisible';
                                                ?>
                                                <a href="javascript:void(0)"
                                                    onclick="selectSeason(<?php echo $item['id']; ?>, '<?php echo $item['name']; ?>', this)"
                                                    class="season-item flex items-center justify-between px-4 py-3 text-sm transition-colors <?php echo $textClass; ?>">
                                                    <span><?php echo $item['name']; ?></span>
                                                    <span
                                                        class="season-check material-symbols-outlined text-base <?php echo $iconClass; ?>">check</span>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                <?php else: ?>
                                    <!-- Phim lẻ -->
                                    <div
                                        class="flex items-center gap-2 text-white font-bold text-lg select-none cursor-default">
                                        <span class="material-symbols-outlined text-primary">video_library</span>
                                        <span>
                                            <?php echo $isSeries ? 'Danh sách tập' : 'Danh sách phát'; ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="flex-1 max-h-[450px] overflow-y-auto custom-scroll pr-1">
                                <!-- Phim bộ  -->
                                <?php

                                // Phim bộ (type_id = 2) → Grid layout (bất kể có season hay không)
                                // Phim lẻ (type_id != 2) → List layout
                                $layoutClass = ($movieDetail['type_id'] == 2)
                                    ? 'grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 gap-2'
                                    : 'flex flex-col gap-3';
                                ?>

                                <div id="episode-list" class="<?php echo $layoutClass; ?>">

                                    <?php if (!empty($episodeDetail)): ?>

                                        <?php if ($isSeries): ?>
                                            <?php foreach ($episodeDetail as $item): ?>
                                                <a href="<?php echo _HOST_URL; ?>/watch?id=<?php echo $movieDetail['id']; ?>&episode_id=<?php echo $item['id']; ?>"
                                                    class="group relative flex items-center justify-center py-2.5 px-2 rounded-lg bg-[#282B3A] border border-white/5 hover:bg-primary hover:border-primary hover:text-[#191B24] transition-all duration-300 text-gray-300 hover:shadow-[0_0_15px_rgba(255,216,117,0.3)]">

                                                    <span class="text-sm font-semibold truncate">
                                                        <?php echo $item['name']; ?>
                                                    </span>
                                                </a>
                                            <?php endforeach; ?>

                                        <?php else: ?>
                                            <?php foreach ($episodeDetail as $item): ?>
                                                <a href="<?php echo _HOST_URL; ?>/watch?id=<?php echo $movieDetail['id']; ?>&episode_id=<?php echo $item['id']; ?>"
                                                    class="group relative flex items-center gap-3 p-2 rounded-xl bg-[#282B3A] border border-white/5 hover:bg-[#2F3346] hover:border-primary/50 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">

                                                    <div class="relative w-[70px] h-[95px] shrink-0 rounded-lg overflow-hidden border border-white/5">
                                                        <img src="<?php echo $movieDetail['thumbnail']; ?>"
                                                            alt="<?php echo $movieDetail['tittle']; ?>" loading="lazy"
                                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                                        <div class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition-colors"></div>
                                                        <span class="material-symbols-outlined absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-primary opacity-0 scale-0 group-hover:opacity-100 group-hover:scale-100 transition-all duration-300 text-3xl drop-shadow-md">
                                                            play_circle
                                                        </span>
                                                    </div>

                                                    <div class="flex-1 min-w-0 py-1">
                                                        <div class="flex items-center gap-2 mb-1.5">
                                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-primary/10 text-primary border border-primary/20">
                                                                <span class="material-symbols-outlined text-[12px]">closed_caption</span>
                                                                <?php echo !empty($item['voice_type']) ? $item['voice_type'] : 'Vietsub'; ?>
                                                            </span>
                                                        </div>
                                                        <h4 class="text-white font-bold text-sm leading-tight truncate group-hover:text-primary transition-colors mb-1">
                                                            <?php echo $movieDetail['tittle']; ?>
                                                        </h4>
                                                        <div class="flex items-center justify-between mt-1">
                                                            <span class="text-xs text-gray-400 font-medium">
                                                                Server: <span class="text-white"><?php echo $item['name']; ?></span>
                                                            </span>
                                                            <span onclick="window.location.href='<?php echo _HOST_URL; ?>/watch?id=<?php echo $movieDetail['id']; ?>&episode_id=<?php echo $item['id']; ?>'"
                                                                class="text-[11px] bg-white/5 text-gray-300 px-2 py-1 rounded group-hover:bg-primary group-hover:text-[#191B24] transition-colors font-bold flex items-center gap-1 cursor-pointer">
                                                                Xem ngay <span class="material-symbols-outlined text-[10px]">arrow_forward</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-6">
                            <h3 class="text-white text-xl font-bold px-2">Diễn viên</h3>
                            <div class="glass-panel p-4 rounded-xl flex-1 max-h-[300px] overflow-y-auto custom-scroll">
                                <div class="space-y-1">
                                    <?php foreach ($getCastByMovieId as $item): ?>

                                        <div onclick="event.preventDefault(); window.location.href='<?php echo _HOST_URL; ?>/dien_vien/chi_tiet?id=<?php echo $item['id'] ?>';" class="group flex items-center gap-3 p-2 rounded-lg transition-all duration-300 hover:bg-white/10 hover:shadow-lg cursor-pointer hover:scale-[1.02]">

                                            <img class="size-12 rounded-full object-cover border border-white/10 group-hover:border-primary transition-colors duration-300"
                                                src="<?php echo $item['avatar'] ?>"
                                                alt="<?php echo $item['name'] ?>" />

                                            <div>
                                                <p class="text-white font-medium text-sm group-hover:text-primary transition-colors duration-300">
                                                    <?php echo $item['name'] ?>
                                                </p>

                                                <?php if (!empty($item['character_name'])): ?>
                                                    <p class="text-gray-400 text-xs group-hover:text-gray-300 transition-colors duration-300">
                                                        trong vai <?php echo $item['character_name'] ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <div class="mt-12 grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- SIMILAR MOVIES -->
                <div class="lg:col-span-4 flex flex-col gap-6 order-2 lg:order-1">
                    <h3 class="text-white text-xl font-bold pl-2 border-l-4 border-primary">Similar Movies</h3>

                    <div class="flex flex-col gap-4">
                        <?php foreach ($similarMovies as $movie): ?>
                            <div onclick="event.preventDefault(); window.location.href='<?php echo _HOST_URL; ?>/detail?id=<?php echo $movie['id'] ?>';"
                                class="flex gap-4 group cursor-pointer p-2 -mx-2 rounded-lg hover:bg-white/5 transition-colors items-center">
                                <div class="w-20 shrink-0 aspect-[2/3] rounded-lg overflow-hidden relative shadow-lg">
                                    <img src="<?php echo $movie['poster_url']; ?>" alt="<?php echo $movie['tittle']; ?>"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h4
                                        class="text-white font-bold text-base leading-tight group-hover:text-primary transition-colors line-clamp-2">
                                        <?php echo $movie['tittle']; ?></h4>
                                    <p class="text-white/40 text-xs mt-1"><?php echo $movie['original_tittle']; ?></p>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span
                                            class="text-primary text-xs font-bold bg-primary/10 border border-primary/20 px-1.5 py-0.5 rounded"><?php echo $movie['imdb_rating']; ?></span>
                                        <span class="text-white/60 text-xs font-medium"><?php echo $movie['release_year']; ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Review & Comments -->
                <?php
                $data = [
                    'idMovie' => $idMovie,
                    'comments' => $comments,
                    'listComments' => $listComments,
                    'totalComments' => $totalComments,
                ];
                layoutPart('client/comment', $data); ?>
            </div>
            </main>

        </div>
    </div>
</body>
<script>
    // -----------------------------------------------------------------------
    // 1. CÁC HÀM XỬ LÝ GIAO DIỆN (UI)
    // -----------------------------------------------------------------------

    function toggleSeasonDropdown() {
        const dropdown = document.getElementById('season-list');
        const arrow = document.getElementById('season-arrow');

        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            dropdown.classList.add('block', 'animate-fade-in-up');
            arrow.style.transform = 'rotate(180deg)';
        } else {
            dropdown.classList.add('hidden');
            dropdown.classList.remove('block');
            arrow.style.transform = 'rotate(0deg)';
        }
    }

    // Sự kiện: Click ra ngoài thì tự đóng menu
    window.addEventListener('click', function(e) {
        const container = document.getElementById('season-dropdown-container');
        // Kiểm tra nếu container tồn tại (phòng trường hợp xem phim lẻ không có nút này)
        if (container && !container.contains(e.target)) {
            const dropdown = document.getElementById('season-list');
            const arrow = document.getElementById('season-arrow');
            if (dropdown && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }
    });

    // -----------------------------------------------------------------------
    // 2. LOGIC CHỌN SEASON & GỌI API (AJAX)
    // -----------------------------------------------------------------------

    function selectSeason(seasonId, seasonName, element) {
        // A. Cập nhật text hiển thị
        const textElement = document.getElementById('current-season-text');
        if (textElement) textElement.innerText = seasonName;

        // B. Đóng menu
        toggleSeasonDropdown();

        // C. Cập nhật style cho item trong menu
        const allItems = document.querySelectorAll('.season-item');
        allItems.forEach(item => {
            const checkIcon = item.querySelector('.season-check');
            item.classList.remove('text-primary', 'font-bold', 'bg-white/5');
            item.classList.add('text-gray-300', 'hover:bg-white/5');
            if (checkIcon) checkIcon.classList.add('invisible');
        });

        if (element) {
            element.classList.remove('text-gray-300', 'hover:bg-white/5');
            element.classList.add('text-primary', 'font-bold', 'bg-white/5');
            const activeCheck = element.querySelector('.season-check');
            if (activeCheck) activeCheck.classList.remove('invisible');
        }

        // D. Gọi Ajax load lại danh sách tập phim
        loadEpisodes(seasonId);
    }
    // -----------------------------------------------------------------------
    // 3. LOGIC LẤY DANH SÁCH TẬP PHIM (AJAX)
    // -----------------------------------------------------------------------
    function loadEpisodes(seasonId) {
        const listContainer = document.getElementById('episode-list');
        if (!listContainer) return;

        // 1. Hiện thông báo đang tải (dùng col-span-full để căn giữa khung grid)
        listContainer.innerHTML =
            '<div class="col-span-full text-white/50 text-sm py-8 text-center">Đang tải danh sách tập...</div>';

        // 2. Gọi fetch (DÙNG ĐÚNG LINK BẠN YÊU CẦU)
        fetch('./api/get-episodes?season_id=' + seasonId)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Lỗi kết nối server');
                }
                return response.json();
            })
            .then(data => {
                // 3. Xóa nội dung loading
                listContainer.innerHTML = '';

                if (data && data.length > 0) {
                    let html = '';
                    const hostUrl = '<?php echo _HOST_URL; ?>';
                    data.forEach(ep => {
                        // --- Tạo HTML nút bấm Grid ---
                        // Lưu ý: Sửa đường dẫn href theo đúng logic routing của bạn
                        // Ví dụ: ?mod=client&act=watch&id=...
                        html += `
                            <a href="${hostUrl}/watch?id=${ep.id}"
                               class="group relative flex items-center justify-center py-2.5 px-2 rounded-lg bg-[#282B3A] border border-white/5 hover:bg-primary hover:border-primary hover:text-[#191B24] transition-all duration-300 text-gray-300 hover:shadow-[0_0_15px_rgba(255,216,117,0.3)]">
                                
                                <span class="text-sm font-semibold truncate">
                                    ${ep.name}
                                </span>
                            </a>
                        `;
                    });
                    listContainer.innerHTML = html;
                } else {
                    listContainer.innerHTML =
                        '<div class="col-span-full text-gray-400 text-sm py-4 text-center">Chưa có tập phim nào.</div>';
                }
            })
            .catch(err => {
                console.error(err);
                listContainer.innerHTML =
                    '<div class="col-span-full text-red-400 text-sm py-4 text-center">Lỗi tải dữ liệu. Vui lòng thử lại.</div>';
            });
    }
</script>
<!-- POST COMMENT -->
<script>
    function postComment(event) {
        event.preventDefault(); // Chặn việc load lại trang

        // Lấy nội dung
        const contentInput = document.getElementById('commentContent');
        const content = contentInput.value.trim();

        if (!content) {
            alert("Vui lòng nhập nội dung bình luận!");
            return;
        }

        // Hiệu ứng nút bấm đang xử lý
        const btnSubmit = event.target.querySelector('button[type="submit"]');
        const oldText = btnSubmit.innerHTML;
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = 'Đang gửi...';

        // Chuẩn bị dữ liệu gửi đi
        const formData = new FormData(document.getElementById('commentForm'));

        // Gọi API
        fetch('/movie/api/post-comment', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // 1. Xóa nội dung trong ô nhập
                    contentInput.value = '';

                    // 2. Chèn comment mới vào đầu danh sách (dùng JS render HTML)
                    const list = document.getElementById('commentList');

                    const newCommentHTML = `
    <div class="flex gap-4 group animate-[fade-in_0.5s]" id="comment-${data.data.id}">
        <div class="size-10 rounded-full border border-white/10 shrink-0 overflow-hidden">
             <img src="${data.data.avatar}" class="w-full h-full object-cover">
        </div>
        <div class="flex-1 comment-body">
            <div class="flex items-center justify-between mb-1">
                <h4 class="text-white font-bold text-sm">${data.data.fullname}</h4> 
                
                <div class="flex items-center gap-3">
                    <span class="text-white/40 text-xs">Vừa xong</span>
                </div>
            </div>
            
            <div class="flex text-yellow-500 text-[14px] mb-2">
                <span class="material-symbols-outlined text-[16px]">star</span>
                <span class="material-symbols-outlined text-[16px]">star</span>
                <span class="material-symbols-outlined text-[16px]">star</span>
                <span class="material-symbols-outlined text-[16px]">star</span>
                <span class="material-symbols-outlined text-[16px]">star</span>
            </div>

            <p class="text-white/80 text-sm leading-relaxed">${data.data.content}</p>
            
            <div class="flex gap-4 mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="text-white/40 hover:text-primary text-xs flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">thumb_up</span> Helpful
                </button>
                <button class="btn-reply text-white/40 hover:text-white text-xs flex items-center gap-1"
                        data-id="${data.data.id}"
                        data-name="${data.data.fullname}"
                        data-level="0">
                    Reply
                </button>
                <button onclick="deleteComment(${data.data.id})" class="text-white/20 hover:text-red-500 text-xs flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">delete</span> Xóa
                </button>
            </div>
        </div>
    </div>
`;
                    // Chèn vào đầu danh sách comment
                    document.getElementById('comment-list').insertAdjacentHTML('afterbegin', newCommentHTML);

                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert('Có lỗi xảy ra. Vui lòng thử lại sau.');
            })
            .finally(() => {
                // Mở lại nút bấm
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = oldText;
            });
    }
</script>
<!-- showAllComments -->
<script>
    function showAllComments() {
        // 1. Lấy tất cả các comment đang bị ẩn
        const hiddenComments = document.querySelectorAll('.comment-item.hidden');

        // 2. Lặp qua và xóa class 'hidden'
        hiddenComments.forEach(function(item) {
            item.classList.remove('hidden');
            // Thêm hiệu ứng fade-in nhẹ cho mượt
            item.classList.add('animate-[fade-in_0.5s]');
        });

        // 3. Ẩn nút Load More đi sau khi đã hiện hết
        const btn = document.getElementById('btnLoadMore');
        if (btn) {
            btn.style.display = 'none';
        }
    }
</script>
<!-- deleteComment -->
<script>
    function deleteComment(id) {
        if (!confirm('Bạn có chắc chắn muốn xóa bình luận này không?')) return;

        // Gọi API xóa (Đảm bảo URL API của bạn đúng)
        // Ví dụ dùng FormData
        const formData = new FormData();
        formData.append('comment_id', id);

        fetch('/movie/api/delete-comment', { // Thay bằng đường dẫn API xóa thực tế của bạn
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {

                    // --- XỬ LÝ GIAO DIỆN (FIX LỖI KHOẢNG TRẮNG) ---
                    const commentItem = document.getElementById('comment-' + id);

                    if (data.action === 'hide' && commentItem) {
                        // TRƯỜNG HỢP: ADMIN ẨN BÌNH LUẬN (Soft Delete)
                        const contentDiv = commentItem.querySelector('.text-white\\/80'); // Tìm div nội dung
                        if (contentDiv) {
                            contentDiv.innerText = data.new_content; // Cập nhật text
                            contentDiv.classList.remove('text-white/80'); // Xóa màu trắng
                            contentDiv.classList.add('text-red-500', 'italic'); // Thêm màu đỏ
                        }
                        return; // Kết thúc, không xóa phần tử
                    }

                    if (commentItem) {
                        // 1. Lấy cấp độ của bình luận (Level)
                        const level = parseInt(commentItem.getAttribute('data-level')) || 0;

                        if (level === 0) {
                            // 2. NẾU LÀ CHA (Level 0):
                            // Tìm thẻ bao ngoài (wrapper) gần nhất để xóa toàn bộ luồng (gồm cả <hr> và comment con)
                            const wrapper = commentItem.closest('.comment-thread-wrapper');
                            if (wrapper) {
                                wrapper.remove(); // Xóa sạch cả cụm
                            } else {
                                commentItem.remove(); // Dự phòng nếu không tìm thấy wrapper
                            }
                        } else {
                            // 3. NẾU LÀ CON (Level > 0):
                            // Tìm và xóa tất cả con cháu (các thẻ tiếp theo có level > level hiện tại)
                            let nextSibling = commentItem.nextElementSibling;
                            while (nextSibling) {
                                // Kiểm tra xem sibling có phải là comment item không
                                if (nextSibling.classList.contains('comment-item')) {
                                    const nextLevel = parseInt(nextSibling.getAttribute('data-level')) || 0;

                                    if (nextLevel > level) {
                                        // Nếu level con > level cha -> Là con cháu -> Xóa
                                        const nodeToRemove = nextSibling;
                                        nextSibling = nextSibling.nextElementSibling; // Move next trước khi xóa
                                        nodeToRemove.remove();
                                    } else {
                                        // Gặp level ngang bằng hoặc nhỏ hơn -> Hết nhánh con -> Dừng
                                        break;
                                    }
                                } else {
                                    // Gặp element khác (ví dụ hr, div khác...) -> Dừng hoặc bỏ qua
                                    // Trong structure này, siblings đều là comment-item, nếu gặp cái khác thì break cho an toàn
                                    break;
                                }
                            }

                            // Xóa chính nó
                            commentItem.remove();
                        }
                    }

                } else {
                    alert(data.message || 'Có lỗi xảy ra khi xóa.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi kết nối server.');
            });
    }
</script>
<!-- REPLY COMMENT -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Lấy Movie ID từ URL (để gửi kèm khi reply)
        // Giả sử URL là ...&id=123
        const urlParams = new URLSearchParams(window.location.search);
        const currentMovieId = urlParams.get('id');

        // 2. Event Delegation cho nút Reply và Cancel
        document.body.addEventListener('click', function(e) {

            // --- XỬ LÝ NÚT REPLY ---
            const btn = e.target.closest('.btn-reply');
            if (btn) {
                e.preventDefault();
                const commentId = btn.getAttribute('data-id');
                const parentName = btn.getAttribute('data-name');
                const parentLevel = parseInt(btn.getAttribute('data-level')) || 0;

                const commentItem = document.getElementById('comment-' + commentId);
                const contentBody = commentItem.querySelector('.comment-body');
                const existingForm = contentBody.querySelector('.reply-form-wrapper');

                if (existingForm) {
                    existingForm.remove();
                } else {
                    // HTML Form: Thêm onsubmit và hidden input movie_id
                    const replyFormHtml = `
                    <div class="reply-form-wrapper mt-4 animate-fade-in-down ml-2">
                        <form onsubmit="handleReplySubmit(event, this)" class="flex flex-col gap-2">
                            <input type="hidden" name="parent_id" value="${commentId}">
                            <input type="hidden" name="movie_id" value="${currentMovieId}">
                            <input type="hidden" name="reply_to_name" value="${parentName}"> <textarea name="content" rows="2" 
                                class="w-full bg-white/5 border border-white/10 rounded-lg p-3 text-sm text-white focus:outline-none focus:border-primary placeholder-white/20 transition-all"
                                placeholder="Trả lời ${parentName}..."></textarea>
                            
                            <div class="flex justify-end gap-2">
                                <button type="button" class="btn-cancel-reply text-xs text-white/40 hover:text-white px-3 py-1">Hủy</button>
                                <button type="submit" class="bg-primary hover:bg-primary/80 text-white text-xs font-bold px-4 py-2 rounded transition-colors">
                                    Gửi trả lời
                                </button>
                            </div>
                        </form>
                    </div>
                `;

                    contentBody.insertAdjacentHTML('beforeend', replyFormHtml);

                    const textarea = contentBody.querySelector('textarea');
                    if (textarea) textarea.focus();
                }
            }

            // --- XỬ LÝ NÚT HỦY ---
            if (e.target && e.target.classList.contains('btn-cancel-reply')) {
                e.preventDefault();
                const formWrapper = e.target.closest('.reply-form-wrapper');
                if (formWrapper) {
                    formWrapper.remove();
                }
            }
        });
    });

    // 3. Hàm xử lý gửi form (AJAX)
    function handleReplySubmit(e, form) {
        e.preventDefault();

        const formData = new FormData(form);
        const btnSubmit = form.querySelector('button[type="submit"]');
        const parentId = formData.get('parent_id');
        const replyToName = formData.get('reply_to_name');

        // Kiểm tra nội dung rỗng
        const content = formData.get('content').trim();
        if (!content) {
            alert('Vui lòng nhập nội dung bình luận!');
            return;
        }

        // UI Loading
        btnSubmit.disabled = true;
        btnSubmit.innerText = 'Đang gửi...';

        fetch('/movie/api/reply-comment', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    // Xóa form
                    form.closest('.reply-form-wrapper').remove();
                    // Vẽ comment mới
                    renderReplyItem(res.data, parentId, replyToName);
                } else {
                    alert(res.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi kết nối server: ' + err.message);
            })
            .finally(() => {
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.innerText = 'Gửi trả lời';
                }
            });
    }

    // 4. Hàm vẽ giao diện Reply mới
    function renderReplyItem(data, parentId, parentName = null) {
        // Tìm đúng comment cha để chèn vào sau nó
        // Lưu ý: Nếu muốn reply nằm thụt vào, dùng margin-left (ml-12)
        const parentComment = document.getElementById('comment-' + parentId);

        // Lấy level của cha từ thuộc tính data-level
        let parentLevel = 0;
        if (parentComment.hasAttribute('data-level')) {
            parentLevel = parseInt(parentComment.getAttribute('data-level'));
        }
        const newLevel = parentLevel + 1;

        // Tạo Style thụt lề dựa trên Level mới
        // Mỗi cấp thụt vào 48px (tương đương 3rem hoặc class ml-12)
        const marginLeftPx = newLevel * 48;
        const styleIndent = `margin-left: ${marginLeftPx}px`;

        const nameTag = parentName ?
            `<span class="inline-flex items-center bg-white/10 hover:bg-white/10 backdrop-blur-md border border-white/10 px-2 py-0.5 rounded text-white font-bold text-xs mr-1 transition-colors cursor-pointer">@${parentName}</span>` :
            '';

        const html = `
        <div class="flex gap-4 group animate-fade-in-down comment-item mt-4 pl-4 border-l-2 border-white/10" 
             id="comment-${data.id}" 
             data-level="${newLevel}"
             style="${styleIndent}"> 
            
            <img src="${data.avartar || 'https://i.pravatar.cc/150?u=default'}" class="size-8 rounded-full border border-white/10 shrink-0 object-cover">
            
            <div class="flex-1 comment-body">
                <div class="flex items-center gap-2 mb-1">
                    <h4 class="text-white font-bold text-xs">${data.fullname}</h4>
                    <span class="text-white/40 text-[10px]">Vừa xong</span>
                </div>
                
                <div class="text-white/80 text-sm leading-relaxed">
                    ${nameTag}${data.content}
                </div>

                <div class="flex gap-4 mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button class="text-white/40 hover:text-primary text-xs flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">thumb_up</span> Helpful
                    </button>
                    <button class="btn-reply text-white/40 hover:text-white text-xs flex items-center gap-1"
                            data-id="${data.id}"
                            data-name="${data.fullname}"
                            data-level="${newLevel}">
                        Reply
                    </button>

                    <button onclick="deleteComment(${data.id})" class="text-white/20 hover:text-red-500 text-xs flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]">delete</span> Xóa
                    </button>
                </div>
            </div>
        </div>
        `;
        // Insert trực tiếp sau comment cha
        parentComment.insertAdjacentHTML('afterend', html);
    }

    function loadMoreComments() {
        // 1. Lấy tất cả các luồng đang bị ẩn
        const hiddenThreads = document.querySelectorAll('.comment-hidden-thread');

        // 2. Hiển thị 10 luồng tiếp theo
        let count = 0;
        hiddenThreads.forEach(thread => {
            if (count < 10) {
                thread.classList.remove('hidden', 'comment-hidden-thread'); // Xóa class ẩn
                thread.classList.add('animate-fade-in-up'); // Thêm hiệu ứng hiện ra cho đẹp
                count++;
            }
        });

        // 3. Cập nhật số lượng còn lại trên nút bấm
        const remaining = document.querySelectorAll('.comment-hidden-thread').length;
        const countSpan = document.getElementById('remainingCount');

        if (remaining > 0) {
            if (countSpan) countSpan.innerText = remaining;
        } else {
            // Nếu hết comment ẩn rồi thì xóa nút Load more
            const btnContainer = document.getElementById('loadMoreContainer');
            if (btnContainer) btnContainer.remove();
        }
    }

    function toggleLike(commentId, btnElement) {
        // Chặn spam click
        if (btnElement.classList.contains('processing')) return;
        btnElement.classList.add('processing');

        const formData = new FormData();
        formData.append('comment_id', commentId);

        fetch('/movie/api/like-comment', { // Đảm bảo route này trỏ đúng vào function likeCommentApi trong Controller
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const icon = btnElement.querySelector('.material-symbols-outlined');
                    const textSpan = btnElement.querySelector('.like-text');
                    const countSpan = btnElement.querySelector('.like-count');

                    if (data.action === 'liked') {
                        // Cập nhật giao diện: Đã Like
                        btnElement.classList.remove('text-white/40');
                        btnElement.classList.add('text-primary');
                        textSpan.innerText = 'Liked';
                    } else {
                        // Cập nhật giao diện: Bỏ Like
                        btnElement.classList.add('text-white/40');
                        btnElement.classList.remove('text-primary');
                        textSpan.innerText = 'Helpful';
                    }

                    // Cập nhật số lượng
                    if (data.likes > 0) {
                        countSpan.innerText = '(' + data.likes + ')';
                        countSpan.classList.remove('hidden');
                    } else {
                        countSpan.classList.add('hidden');
                    }
                } else {
                    alert(data.message);
                    // Nếu lỗi chưa đăng nhập -> chuyển hướng login (tùy chọn)
                    if (data.message.includes('đăng nhập')) {
                        window.location.href = '/login';
                    }
                }
            })
            .catch(err => {
                console.error(err);
            })
            .finally(() => {
                btnElement.classList.remove('processing');
            });
    }
</script>
<!-- FOOTER -->
<?php layout('client/footer') ?>

</html>