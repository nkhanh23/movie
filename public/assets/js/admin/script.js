
// Hàm khởi tạo chính
function initApp() {
    // --- KHAI BÁO BIẾN ---
    const sidebarToggleBtn = document.getElementById('sidebar-toggle');
    const appContainer = document.querySelector('.app-container');
    const navItems = document.querySelectorAll('.nav-item');
    const contentSections = document.querySelectorAll('.content-section');

    if (!sidebarToggleBtn || !appContainer) return;

    // --- 1. TOGGLE SIDEBAR ---
    // Clone nút để xóa sạch các event listener cũ (tránh bị lặp lại sự kiện)
    const newBtn = sidebarToggleBtn.cloneNode(true);
    sidebarToggleBtn.parentNode.replaceChild(newBtn, sidebarToggleBtn);
    
    newBtn.addEventListener('click', function() {
        // Nếu màn hình nhỏ (mobile), dùng class sidebar-open
        if (window.innerWidth <= 768) {
            appContainer.classList.toggle('sidebar-open');
        } else {
            // Màn hình lớn, dùng class sidebar-collapsed
            appContainer.classList.toggle('sidebar-collapsed');
        }
    });

    // Đóng sidebar khi click ra ngoài trên mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768 && 
            appContainer.classList.contains('sidebar-open') && 
            !event.target.closest('.sidebar') && 
            !event.target.closest('#sidebar-toggle')) {
            appContainer.classList.remove('sidebar-open');
        }
    });

    // --- 2. NAVIGATION (SPA SWITCHING) ---
    // Chức năng này giúp chuyển đổi giữa các màn hình (Phim, Users, v.v.)
    navItems.forEach(item => {
        // Clone node để đảm bảo clean event listeners
        const newItem = item.cloneNode(true);
        item.parentNode.replaceChild(newItem, item);

        newItem.addEventListener('click', function(e) {
            // Lấy target ID từ data-attribute
            const targetData = this.getAttribute('data-target');

            // QUAN TRỌNG: 
            // Nếu có data-target -> Xử lý chuyển tab nội bộ (SPA) và chặn href
            // Nếu KHÔNG có data-target -> Để trình duyệt chuyển trang theo href bình thường
            if (targetData) {
                e.preventDefault(); // Ngăn không cho load lại trang

                // Xóa class active khỏi tất cả menu item
                // Lưu ý: cần query lại DOM vì chúng ta đã replaceChild ở trên
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                
                // Thêm class active vào item vừa click
                this.classList.add('active');
                
                const targetId = targetData + '-view';

                // Ẩn tất cả section
                contentSections.forEach(section => section.classList.remove('active'));

                // Hiện section tương ứng
                const targetSection = document.getElementById(targetId);
                if (targetSection) {
                    targetSection.classList.add('active');
                }

                // Trên mobile, đóng sidebar sau khi chọn menu
                if (window.innerWidth <= 768) {
                    appContainer.classList.remove('sidebar-open');
                }
            }
            // Nếu không có data-target, code sẽ tự động chạy theo href
        });
    });
}

// Logic kiểm tra trạng thái tải trang để chạy initApp
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initApp);
} else {
    // Nếu DOM đã tải xong (trường hợp import module), chạy luôn
    initApp();
}
