// --- GLOBAL HELPER FUNCTIONS ---
// (Đặt ở ngoài để có thể gọi từ HTML hoặc dùng chung)

// 1. Hàm tạo Slug từ tên
function createSlug(string) {
    if(!string) return '';
    return string.toLowerCase()
        .normalize('NFD') // chuyển ký tự có dấu thành tổ hợp
        .replace(/[\u0300-\u036f]/g, '') // xoá dấu
        .replace(/đ/g, 'd') // thay đ -> d
        .replace(/[^a-z0-9\s-]/g, '') // xoá ký tự đặc biệt
        .trim()
        .replace(/\s+/g, '-') // thay khoảng trắng -> -
        .replace(/-+/g, '-'); // bỏ trùng dấu -
}

// 2. Hàm bật tắt dropdown (cho Custom Multi-select)
window.toggleDropdown = function(id) {
    var content = document.querySelector('#' + id + ' .dropdown-content');
    if(content) content.classList.toggle('show');
}

// 3. Đóng dropdown khi click ra ngoài
window.onclick = function(event) {
    if (!event.target.closest('.dropdown-btn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

// --- MAIN LOGIC ---
function initApp() {
    // --- KHAI BÁO BIẾN CƠ BẢN (GIỮ NGUYÊN CODE CŨ CỦA BẠN) ---
    const sidebarToggleBtn = document.getElementById('sidebar-toggle');
    const appContainer = document.querySelector('.app-container');
    const navItems = document.querySelectorAll('.nav-item');
    const contentSections = document.querySelectorAll('.content-section');

    if (!sidebarToggleBtn || !appContainer) return;

    // --- 1. TOGGLE SIDEBAR (CODE CŨ) ---
    const newBtn = sidebarToggleBtn.cloneNode(true);
    sidebarToggleBtn.parentNode.replaceChild(newBtn, sidebarToggleBtn);
    
    newBtn.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
            appContainer.classList.toggle('sidebar-open');
        } else {
            appContainer.classList.toggle('sidebar-collapsed');
        }
    });

    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768 && 
            appContainer.classList.contains('sidebar-open') && 
            !event.target.closest('.sidebar') && 
            !event.target.closest('#sidebar-toggle')) {
            appContainer.classList.remove('sidebar-open');
        }
    });

    // --- 2. NAVIGATION SPA (CODE CŨ) ---
    navItems.forEach(item => {
        const newItem = item.cloneNode(true);
        item.parentNode.replaceChild(newItem, item);

        newItem.addEventListener('click', function(e) {
            const targetData = this.getAttribute('data-target');
            if (targetData) {
                e.preventDefault();
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
                
                const targetId = targetData + '-view';
                contentSections.forEach(section => section.classList.remove('active'));
                
                const targetSection = document.getElementById(targetId);
                if (targetSection) {
                    targetSection.classList.add('active');
                }

                if (window.innerWidth <= 768) {
                    appContainer.classList.remove('sidebar-open');
                }
            }
        });
    });

    // --- PHẦN BỔ SUNG MỚI: XỬ LÝ GIAO DIỆN CRUD (KHÔNG DÙNG MOCK DATA) ---

    // A. KHAI BÁO BIẾN CHO CÁC VIEW MỚI
    // 1. Phim (Movies)
    const btnAddMovie = document.getElementById('btn-add-movie');
    const btnCancelMovie = document.getElementById('btn-cancel-movie');
    const moviesView = document.getElementById('movies-view');
    const addMovieView = document.getElementById('add-movie-view');
    const editMovieView = document.getElementById('edit-movie-view');

    // 2. Tập Phim (Episodes)
    const btnAddEpisode = document.getElementById('btn-add-episode');
    const episodesView = document.getElementById('episodes-view');
    const addEpisodeView = document.getElementById('add-episode-view');
    const cancelToEpisodeListBtns = document.querySelectorAll('.cancel-to-episode-list');

    // 3. Các nút hủy chung
    const cancelButtons = document.querySelectorAll('.cancel-to-list');

    // B. LOGIC CHUYỂN ĐỔI MÀN HÌNH QUẢN LÝ PHIM

    // 1. Mở màn hình Thêm Phim
    if (btnAddMovie && addMovieView && moviesView) {
        btnAddMovie.addEventListener('click', function() {
            moviesView.classList.remove('active');
            if(editMovieView) editMovieView.classList.remove('active'); // Đảm bảo tắt edit nếu đang mở
            addMovieView.classList.add('active');
        });
    }

    // 2. Mở màn hình Sửa Phim (Sử dụng Event Delegation vì nút Sửa nằm trong bảng)
    if (moviesView) {
        moviesView.addEventListener('click', function(e) {
            // Tìm xem nút được click có phải là nút sửa (hoặc icon bên trong nó) không
            const btnEdit = e.target.closest('.btn-edit-movie');
            if (btnEdit && editMovieView) {
                moviesView.classList.remove('active');
                if(addMovieView) addMovieView.classList.remove('active');
                editMovieView.classList.add('active');
                
                // Tại đây bạn có thể thêm logic lấy ID từ data-id để load dữ liệu bằng AJAX nếu cần
                // const movieId = btnEdit.getAttribute('data-id');
                // loadMovieData(movieId); 
            }
        });
    }

    // 3. Nút Quay lại/Hủy bỏ (chung cho form Phim)
    cancelButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            if(addMovieView) addMovieView.classList.remove('active');
            if(editMovieView) editMovieView.classList.remove('active');
            if(moviesView) moviesView.classList.add('active');
        });
    });
    
    // Nút quay lại riêng lẻ (nếu có ID cụ thể)
    if (btnCancelMovie && addMovieView && moviesView) {
        btnCancelMovie.addEventListener('click', function() {
            addMovieView.classList.remove('active');
            moviesView.classList.add('active');
        });
    }

    // C. LOGIC CHUYỂN ĐỔI MÀN HÌNH TẬP PHIM

    // 1. Mở màn hình Thêm Tập
    if(btnAddEpisode && addEpisodeView && episodesView) {
        btnAddEpisode.addEventListener('click', function() {
            episodesView.classList.remove('active');
            addEpisodeView.classList.add('active');
        });
    }

    // 2. Quay lại màn hình Danh sách Tập
    cancelToEpisodeListBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            addEpisodeView.classList.remove('active');
            episodesView.classList.add('active');
        });
    });

    // D. TỰ ĐỘNG TẠO SLUG KHI NHẬP TÊN (Tiện ích UI)
    
    // Cho form Thêm Phim
    const titleInput = document.getElementById('title'); // ID input tên phim
    const slugInput = document.getElementById('slug');   // ID input slug
    if(titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            slugInput.value = createSlug(this.value);
        });
    }

    // Cho form Sửa Phim
    const editTitleInput = document.getElementById('edit_title');
    const editSlugInput = document.getElementById('edit_slug');
    if(editTitleInput && editSlugInput) {
        editTitleInput.addEventListener('input', function() {
            editSlugInput.value = createSlug(this.value);
        });
    }

    // Cho form Thêm Tập
    const epNameInput = document.getElementById('ep_name');
    const epSlugInput = document.getElementById('ep_slug');
    if(epNameInput && epSlugInput) {
        epNameInput.addEventListener('input', function() {
            epSlugInput.value = createSlug(this.value);
        });
    }

    // --- E. LOGIC CHO SEARCHABLE SELECT (MỚI THÊM) ---
    const searchableSelects = document.querySelectorAll('.searchable-select');
    
    searchableSelects.forEach(select => {
        const trigger = select.querySelector('.select-trigger');
        const input = select.querySelector('.select-search-box input');
        const options = select.querySelectorAll('.select-option');
        const hiddenInput = select.querySelector('input[type="hidden"]');

        if (trigger) {
            trigger.addEventListener('click', function(e) {
                // Ngăn chặn sự kiện nổi bọt
                e.stopPropagation();

                // Đóng tất cả các select khác đang mở
                searchableSelects.forEach(otherSelect => {
                    if (otherSelect !== select) {
                        otherSelect.classList.remove('active');
                    }
                });

                // Toggle trạng thái active cho select hiện tại
                select.classList.toggle('active');
                
                // Nếu mở ra thì focus vào ô tìm kiếm
                if (select.classList.contains('active') && input) {
                    input.focus();
                }
            });
        }

        if (input) {
             // Click vào ô input không đóng dropdown
            input.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Logic tìm kiếm
            input.addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                options.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    if (text.includes(filter)) {
                        option.classList.remove('hidden');
                        option.style.display = ''; // Reset display
                    } else {
                        option.classList.add('hidden');
                        option.style.display = 'none'; // Ẩn đi
                    }
                });
            });
        }

        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                const value = this.getAttribute('data-value');
                const text = this.textContent;

                // Cập nhật giao diện trigger
                if (trigger) {
                    trigger.innerHTML = `${text} <i class="fa-solid fa-chevron-down"></i>`;
                    trigger.classList.add('has-value');
                }

                // Cập nhật input ẩn (để gửi form)
                if (hiddenInput) {
                    hiddenInput.value = value;
                }

                // Đóng dropdown sau khi chọn
                select.classList.remove('active');
            });
        });
    });

    // Sự kiện click ra ngoài để đóng Searchable Select
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.searchable-select')) {
            searchableSelects.forEach(select => {
                select.classList.remove('active');
            });
        }
    });
}

// Logic kiểm tra trạng thái tải trang
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initApp);
} else {
    initApp();
}