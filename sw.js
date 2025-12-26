const CACHE_NAME = 'movie-webapp-v2'; // Đã nâng version lên v2 để ép cập nhật
const ASSETS_TO_CACHE = [
    '/',
    '/index.php',
    '/public/css/style.css',
    '/public/js/main.js',
    '/public/img/logo/1766558379_favicon.ico',
    '/public/img/logo/android-chrome-512x512.png',
    '/offline.html' // Đảm bảo bạn đã tạo file này
];

// 1. Cài đặt (Install) - Cache các file tĩnh quan trọng
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log('[SW] Caching static assets');
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
    // Giúp SW kích hoạt ngay lập tức không cần chờ reload
    self.skipWaiting();
});

// 2. Kích hoạt (Activate) - Xóa cache cũ
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keyList) => {
            return Promise.all(keyList.map((key) => {
                if (key !== CACHE_NAME) {
                    console.log('[SW] Removing old cache', key);
                    return caches.delete(key);
                }
            }));
        })
    );
    return self.clients.claim();
});

// 3. Xử lý tải trang (Fetch Strategy: Network First)
self.addEventListener('fetch', (event) => {
    const requestUrl = new URL(event.request.url);

    // --- BỘ LỌC REQUEST ---

    // 1. Chỉ xử lý http/https (Bỏ qua chrome-extension://, file://...)
    if (!requestUrl.protocol.startsWith('http')) {
        return;
    }

    // 2. Chỉ cache method GET (Bỏ qua POST/PUT/DELETE)
    if (event.request.method !== 'GET') {
        return;
    }

    // 3. Không cache Video streaming (Tránh tràn bộ nhớ)
    if (requestUrl.pathname.endsWith('.mp4') ||
        requestUrl.pathname.endsWith('.m3u8') ||
        requestUrl.href.includes('video_sources')) {
        return;
    }

    // --- CHIẾN LƯỢC TẢI ---
    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // TRƯỜNG HỢP 1: Có mạng -> Tải từ Server

                // Kiểm tra phản hồi có hợp lệ không
                if (!response || response.status !== 200 || response.type !== 'basic') {
                    return response;
                }

                // Nếu tải thành công, clone response và lưu vào cache mới nhất
                const responseToCache = response.clone();
                caches.open(CACHE_NAME).then((cache) => {
                    cache.put(event.request, responseToCache);
                });

                return response;
            })
            .catch(() => {
                // TRƯỜNG HỢP 2: Mất mạng -> Lấy từ Cache
                return caches.match(event.request)
                    .then((response) => {
                        if (response) {
                            return response; // Có trong cache thì trả về
                        }

                        // TRƯỜNG HỢP 3: Mất mạng + Không có Cache -> Trả về trang Offline
                        // Chỉ áp dụng nếu người dùng đang điều hướng (navigate) sang trang HTML
                        if (event.request.mode === 'navigate') {
                            return caches.match('/offline.html');
                        }
                    });
            })
    );
});