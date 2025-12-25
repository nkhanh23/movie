<?php
$is_logged = isLogin();

// Fetch genres and countries for dropdown
$genresModel = new Genres();
$moviesModel = new Movies();
$allGenres = $genresModel->getAllGenres();
$allCountries = $moviesModel->getAllCountries();

// Fetch notifications for logged-in users
$notifications = [];
if (!empty($_SESSION['auth'])) {
  $notificationsModel = new Notifications();
  $notifications = $notificationsModel->getLatest($_SESSION['auth']['id'], 5);
}

// Lấy settings từ database
$siteSettings = getSiteSettings();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="referrer" content="no-referrer">
  <title><?php echo htmlspecialchars($siteSettings['site_name']); ?></title>

  <!-- Favicon -->
  <?php
  $faviconPath = !empty($siteSettings['site_favicon']) ? _HOST_URL . '/' . $siteSettings['site_favicon'] : '';
  ?>
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo htmlspecialchars($faviconPath); ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo htmlspecialchars($faviconPath); ?>">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo htmlspecialchars($faviconPath); ?>">

  <script src="https://cdn.tailwindcss.com"></script>

  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#D96C16", // Cam Cháy (Burnt Orange)
            "secondary": "#F29F05", // Vàng Mật Ong (Honey/Amber)
            "highlight": "#F2CB05", // Vàng Sáng (Bright Yellow)
            "background-light": "#f5f7f8",
            "background-dark": "#050505", // Black
            "glass-surface": "rgba(30, 41, 59, 0.4)",
            "glass-border": "rgba(255, 255, 255, 0.08)",
            "glass-highlight": "rgba(255, 255, 255, 0.03)",
          },
          fontFamily: {
            "display": ["Space Grotesk", "sans-serif"]
          },
          borderRadius: {
            "DEFAULT": "0.5rem",
            "lg": "1rem",
            "xl": "1.5rem",
            "full": "9999px"
          },
          backgroundImage: {
            'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
          },
          boxShadow: {
            'neon': '0 0 10px rgba(217, 108, 22, 0.3), 0 0 20px rgba(217, 108, 22, 0.1)',
            'neon-sm': '0 0 8px rgba(217, 108, 22, 0.5)',
            'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.37)',
          },
          screens: {
            'xs': '400px',
          }
        },
      },
    }
  </script>

  <!-- Thư viện HLS.js -->
  <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <link rel="stylesheet" href="<?php echo _HOST_URL_PUBLIC; ?>/assets/css/client/style.css?v=<?php echo time(); ?>">

  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Comment System -->
  <script src="<?php echo _HOST_URL_PUBLIC; ?>/assets/js/client/comments.js"></script>

  <!-- Mobile Header Handlers -->
  <script src="<?php echo _HOST_URL_PUBLIC; ?>/assets/js/client/mobile-header.js"></script>

  <script type="importmap">
    {
      "imports": {
        "lucide-react": "https://aistudiocdn.com/lucide-react@^0.556.0"
      }
    }
  </script>

  <script src="https://unpkg.com/lucide@latest"></script>

  <!-- Filter Styles -->
  <link rel="stylesheet" href="<?php echo _HOST_URL_PUBLIC; ?>/assets/css/filter-styles.css">

  <!-- Search Styles -->
  <link rel="stylesheet" href="<?php echo _HOST_URL_PUBLIC; ?>/assets/css/search-styles.css">
</head>

<body>
  <nav class="fixed top-0 inset-x-0 w-full z-50 bg-gradient-to-b from-black/90 to-transparent px-3 sm:px-4 md:px-12 py-4 transition-all duration-300">

    <!-- ================= MOBILE NAV (FIX TRÀN ICON 100%) ================= -->
    <div class="relative md:hidden w-full">
      <!-- Right icons: absolute (không bị ảnh hưởng bởi logo) -->
      <div class="absolute right-0 top-1/2 -translate-y-1/2 flex items-center gap-2">
        <?php if (!empty($_SESSION['auth'])): ?>
          <!-- Mobile Notification Button -->
          <button id="mobileNotificationBtn"
            class="relative flex items-center justify-center hover:text-primary transition-colors">
            <i data-lucide="bell" class="w-5 h-5 text-gray-300"></i>
            <?php if (!empty($notifications)): ?>
              <span class="absolute top-0 right-0 w-2 h-2 bg-green-500 rounded-full animate-pulse translate-x-1/2 -translate-y-1/2"></span>
            <?php endif; ?>
          </button>
        <?php endif; ?>

        <!-- Mobile Search Button -->
        <button id="mobileSearchBtn"
          class="flex items-center justify-center hover:text-primary transition-colors">
          <i data-lucide="search" class="w-5 h-5 text-gray-300"></i>
        </button>
      </div>

      <!-- Left content: chừa sẵn chỗ bên phải để không đè icon -->
      <div class="flex items-center gap-2 min-w-0 pr-16">
        <button id="mobileMenuBtn" class="flex-shrink-0 hover:text-primary transition-colors">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>

        <a href="<?php echo _HOST_URL; ?>/">
          <img
            src="<?php echo _HOST_URL_PUBLIC; ?>/img/logo/PhePhim.png"
            alt=""
            class="h-8 w-auto object-contain min-w-0 max-w-[110px] xs:max-w-[130px] sm:max-w-[160px]">
        </a>
      </div>
    </div>

    <!-- ================= DESKTOP NAV (GIỮ NGUYÊN) ================= -->
    <div class="hidden md:flex items-center justify-between w-full">

      <!-- Desktop LEFT -->
      <div class="flex items-center gap-4 min-w-0">
        <a href="<?php echo _HOST_URL; ?>/"><img src="<?php echo _HOST_URL_PUBLIC; ?>/img/logo/PhePhim.png" alt="" class="h-10 w-auto"></a>

        <div class="flex items-center gap-6 text-sm font-medium text-gray-300 ml-4">
          <a href="<?php echo _HOST_URL ?>" class="text-white font-bold">Trang chủ</a>
          <a href="<?php echo _HOST_URL ?>/phim_le">Phim lẻ</a>
          <a href="<?php echo _HOST_URL ?>/phim_bo">Phim bộ</a>
          <a href="<?php echo _HOST_URL ?>/phim_chieu_rap">Phim chiếu rạp</a>

          <!-- Genres -->
          <div class="relative group">
            <button class="flex items-center gap-1">
              Thể loại <i class="fa-solid fa-caret-down text-xs"></i>
            </button>
            <div class="absolute left-0 top-full pt-2 hidden group-hover:block w-[800px] glass-panel p-4 rounded-xl">
              <div class="grid grid-cols-6 gap-2">
                <?php foreach ($allGenres as $genre): ?>
                  <a href="<?php echo _HOST_URL ?>/the_loai?id=<?php echo $genre['id'] ?>"
                    class="px-3 py-2 text-xs rounded-lg hover:bg-primary/20">
                    <?php echo $genre['name'] ?>
                  </a>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <!-- Countries -->
          <div class="relative group">
            <button class="flex items-center gap-1">
              Quốc gia <i class="fa-solid fa-caret-down text-xs"></i>
            </button>
            <div class="absolute left-0 top-full pt-2 hidden group-hover:block w-[650px] glass-panel p-4 rounded-xl">
              <div class="grid grid-cols-4 gap-2">
                <?php foreach ($allCountries as $country): ?>
                  <a href="<?php echo _HOST_URL ?>/quoc_gia?id=<?php echo $country['id'] ?>"
                    class="px-3 py-2 text-xs rounded-lg hover:bg-primary/20">
                    <?php echo $country['name'] ?>
                  </a>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <a href="<?php echo _HOST_URL ?>/dien_vien">Diễn viên</a>
        </div>
      </div>

      <!-- Desktop RIGHT (giữ nguyên phần của bạn) -->
      <div class="hidden md:flex items-center gap-6 text-white">
        <!-- Desktop Expandable Search Bar -->
        <form action="<?php echo _HOST_URL; ?>/tim_kiem" method="GET">
          <div class="flex items-center relative">
            <div id="searchContainer" class="flex items-center relative transition-all duration-300 ease-out">
              <button id="searchIcon" type="button" class="w-10 h-10 rounded-full bg-white/5 backdrop-blur-md border border-white/10 flex items-center justify-center hover:bg-white/10 hover:border-white/20 transition-all duration-300 group">
                <i data-lucide="search" class="w-5 h-5 text-gray-300 group-hover:text-white transition-colors"></i>
              </button>
              <input type="text" name="tu_khoa" id="searchInput" required placeholder="Tìm kiếm phim, diễn viên..."
                class="absolute right-0 bg-white/5 backdrop-blur-md text-gray-200 text-sm rounded-full py-2.5 pl-4 pr-12 border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20 focus:border-white/20 placeholder:text-gray-400 transition-all duration-300 font-medium opacity-0 pointer-events-none w-10"
                style="box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);">
            </div>
          </div>
        </form>

        <!-- Desktop Notification Dropdown -->
        <?php if (!empty($_SESSION['auth'])): ?>
          <div class="relative" id="notificationDropdownContainer">
            <button id="notificationBtn" class="relative cursor-pointer hover:text-gray-300 transition-colors flex items-center justify-center">
              <i data-lucide="bell" class="w-5 h-5"></i>
              <?php if (!empty($notifications)): ?>
                <span class="absolute top-0 right-0 w-3 h-3 bg-green-500 rounded-full animate-pulse translate-x-1/2 -translate-y-1/2"></span>

              <?php endif; ?>
            </button>

            <!-- Dropdown Menu -->
            <div id="notificationDropdown" class="absolute right-0 top-full mt-3 w-96 glass-panel rounded-xl border border-white/10 shadow-glass opacity-0 invisible transform translate-y-2 transition-all duration-300 overflow-hidden max-h-[500px] flex flex-col">
              <!-- Header -->
              <div class="px-4 py-3 border-b border-white/10 bg-white/5">
                <div class="flex items-center justify-between">
                  <h3 class="text-white font-semibold text-sm">Thông báo</h3>
                  <a href="<?php echo _HOST_URL; ?>/thong_bao" class="text-primary text-xs hover:text-secondary transition-colors">
                    Xem tất cả
                  </a>
                </div>
              </div>

              <!-- Notifications List -->
              <div class="overflow-y-auto custom-scroll flex-1">
                <?php if (!empty($notifications)): ?>
                  <div class="py-2">
                    <?php foreach ($notifications as $item): ?>
                      <?php
                      $config = [
                        'icon' => 'info',
                        'color' => 'bg-white/10',
                        'icon_bg' => 'bg-white/5',
                        'icon_color' => 'text-white/60',
                        'title' => 'Thông báo hệ thống'
                      ];

                      switch ($item['type']) {
                        case 'new_episode':
                          $config = [
                            'icon' => 'film',
                            'color' => 'bg-primary',
                            'icon_bg' => 'bg-primary/20',
                            'icon_color' => 'text-primary',
                            'title' => 'Tập phim mới'
                          ];
                          break;
                        case 'reply':
                          $config = [
                            'icon' => 'message-square',
                            'color' => 'bg-secondary',
                            'icon_bg' => 'bg-secondary/20',
                            'icon_color' => 'text-secondary',
                            'title' => 'Phản hồi mới'
                          ];
                          break;
                        case 'like':
                          $config = [
                            'icon' => 'heart',
                            'color' => 'bg-red-500',
                            'icon_bg' => 'bg-red-500/20',
                            'icon_color' => 'text-red-500',
                            'title' => 'Lượt thích mới'
                          ];
                          break;
                      }
                      ?>
                      <a href="<?php echo $item['link']; ?>" class="block px-4 py-3 hover:bg-white/5 transition-colors group border-l-2 border-transparent hover:border-primary">
                        <div class="flex items-start gap-3">
                          <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 <?php echo $config['icon_bg']; ?>">
                            <i data-lucide="<?php echo $config['icon']; ?>" class="w-5 h-5 <?php echo $config['icon_color']; ?>"></i>
                          </div>

                          <div class="flex-1 min-w-0">
                            <p class="text-white text-xs font-medium mb-1"><?php echo $config['title']; ?></p>
                            <p class="text-slate-400 text-xs line-clamp-2 mb-1"><?php echo html_entity_decode($item['message']); ?></p>
                            <span class="text-slate-500 text-[10px]"><?php echo timeAgo($item['created_at']); ?></span>
                          </div>

                          <div class="w-2 h-2 rounded-full <?php echo $config['color']; ?> flex-shrink-0 mt-1"></div>
                        </div>
                      </a>
                    <?php endforeach; ?>
                  </div>
                <?php else: ?>
                  <div class="flex flex-col items-center justify-center py-12 text-slate-500">
                    <i data-lucide="bell-off" class="w-12 h-12 mb-3 opacity-50"></i>
                    <p class="text-sm">Chưa có thông báo</p>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <!-- User Avatar / Login Button (GIỮ NGUYÊN phần của bạn) -->
        <?php if (!empty($_SESSION['auth'])): ?>
          <div class="relative hidden sm:block" id="userDropdownContainer">
            <div class="flex items-center gap-2 cursor-pointer" id="userAvatarBtn">
              <img src="<?php echo $_SESSION['auth']['avatar']; ?>"
                alt="<?php echo $_SESSION['auth']['fullname']; ?>"
                class="w-6 h-6 sm:w-10 sm:h-10 rounded-full object-cover border-2 border-white/20 hover:border-primary transition-all duration-300">
              <i data-lucide="chevron-down" id="dropdownChevron" class="w-4 h-4 hidden md:block transition-transform duration-300"></i>
            </div>

            <div id="userDropdown" class="absolute right-0 top-full mt-3 w-64 glass-panel rounded-xl border border-white/10 shadow-glass opacity-0 invisible transform translate-y-2 transition-all duration-300 overflow-hidden">
              <div class="px-4 py-3 border-b border-white/10">
                <p class="text-white font-semibold text-sm"><?php echo $_SESSION['auth']['fullname']; ?></p>
                <p class="text-gray-400 text-xs mt-0.5"><?php echo $_SESSION['auth']['email']; ?></p>
              </div>

              <div class="py-2">
                <?php if ($_SESSION['auth']['group_id'] == '2'): ?>
                  <a href="<?php echo _HOST_URL; ?>/admin/dashboard" class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/5 transition-colors group">
                    <i data-lucide="shield" class="w-5 h-5 text-gray-400 group-hover:text-primary transition-colors"></i>
                    <span class="text-gray-300 text-sm group-hover:text-white transition-colors">Trang admin</span>
                  </a>
                <?php endif; ?>
                <a href="<?php echo _HOST_URL; ?>/yeu_thich" class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/5 transition-colors group">
                  <i data-lucide="heart" class="w-5 h-5 text-gray-400 group-hover:text-primary transition-colors"></i>
                  <span class="text-gray-300 text-sm group-hover:text-white transition-colors">Yêu thích</span>
                </a>

                <a href="<?php echo _HOST_URL; ?>/tai_khoan" class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/5 transition-colors group">
                  <i data-lucide="user" class="w-5 h-5 text-gray-400 group-hover:text-primary transition-colors"></i>
                  <span class="text-gray-300 text-sm group-hover:text-white transition-colors">Tài khoản</span>
                </a>

                <a href="<?php echo _HOST_URL; ?>/thong_bao" class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/5 transition-colors group">
                  <i data-lucide="bell" class="w-5 h-5 text-gray-400 group-hover:text-primary transition-colors"></i>
                  <span class="text-gray-300 text-sm group-hover:text-white transition-colors">Thông báo</span>
                </a>

                <div class="border-t border-white/10 my-2"></div>

                <a href="<?php echo _HOST_URL; ?>/logout" class="flex items-center gap-3 px-4 py-2.5 hover:bg-red-500/10 transition-colors group">
                  <i data-lucide="log-out" class="w-5 h-5 text-gray-400 group-hover:text-red-500 transition-colors"></i>
                  <span class="text-gray-300 text-sm group-hover:text-red-500 transition-colors">Đăng xuất</span>
                </a>
              </div>
            </div>
          </div>
        <?php else: ?>
          <a href="<?php echo _HOST_URL; ?>/login"
            class="px-6 py-2.5 rounded-full bg-gradient-to-r from-primary to-secondary hover:from-secondary hover:to-primary text-white font-semibold text-sm transition-all duration-300 shadow-neon hover:shadow-neon-sm transform hover:scale-105 flex items-center gap-2">
            <i data-lucide="log-in" class="w-4 h-4"></i>
            <span class="hidden sm:inline">Đăng nhập</span>
          </a>
        <?php endif; ?>
      </div>

    </div>
  </nav>




  <!-- Mobile Menu Sidebar -->
  <div id="mobileSidebar" class="fixed inset-0 z-40 pointer-events-none">
    <!-- Overlay -->
    <div id="mobileOverlay" class="absolute inset-0 bg-black/80 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>

    <!-- Sidebar Panel -->
    <div id="mobileSidebarPanel" class="absolute top-0 left-0 h-full w-80 bg-gradient-to-b from-black/95 to-black/90 backdrop-blur-md border-r border-white/10 transform -translate-x-full transition-transform duration-300 overflow-y-auto pt-6">

      <!-- Mobile Search Bar -->
      <div class="px-4 pb-4 xs:hidden">
        <form action="<?php echo _HOST_URL; ?>/tim_kiem" method="GET" class="relative">
          <input type="text" name="tu_khoa" placeholder="Tìm kiếm phim..."
            class="w-full px-4 py-2.5 pl-10 bg-white/5 border border-white/10 rounded-lg text-white text-sm placeholder:text-gray-400 focus:outline-none focus:border-primary/50">
          <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
        </form>
      </div>

      <!-- User Section (for mobile when avatar is hidden) -->
      <?php if (!empty($_SESSION['auth'])): ?>
        <div class="p-4 border-b border-white/10">
          <div class="flex items-center gap-3 mb-3">
            <img src="<?php echo $_SESSION['auth']['avatar']; ?>" alt="" class="w-10 h-10 rounded-full object-cover border-2 border-white/20">
            <div>
              <p class="text-white font-medium text-sm"><?php echo $_SESSION['auth']['fullname']; ?></p>
              <p class="text-gray-400 text-xs"><?php echo $_SESSION['auth']['email']; ?></p>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-2">
            <a href="<?php echo _HOST_URL; ?>/tai_khoan" class="px-3 py-2 rounded-lg bg-white/5 text-gray-300 text-xs text-center hover:bg-primary/20 hover:text-primary transition-all">
              Tài khoản
            </a>
            <a href="<?php echo _HOST_URL; ?>/logout" class="px-3 py-2 rounded-lg bg-red-500/10 text-red-400 text-xs text-center hover:bg-red-500/20 transition-all">
              Đăng xuất
            </a>
          </div>
        </div>
      <?php else: ?>
        <div class="p-4 border-b border-white/10">
          <a href="<?php echo _HOST_URL; ?>/login" class="block w-full px-4 py-3 rounded-lg bg-gradient-to-r from-primary to-secondary text-white text-sm font-medium text-center">
            Đăng nhập
          </a>
        </div>
      <?php endif; ?>

      <!-- Navigation Links -->
      <div class="p-4 space-y-2">
        <a href="<?php echo _HOST_URL ?>" class="block px-4 py-3 rounded-lg hover:bg-white/5 text-white font-medium transition-all">
          Trang chủ
        </a>
        <a href="<?php echo _HOST_URL ?>/phim_le" class="block px-4 py-3 rounded-lg hover:bg-white/5 text-gray-300 hover:text-white transition-all">
          Phim lẻ
        </a>
        <a href="<?php echo _HOST_URL ?>/phim_bo" class="block px-4 py-3 rounded-lg hover:bg-white/5 text-gray-300 hover:text-white transition-all">
          Phim bộ
        </a>
        <a href="<?php echo _HOST_URL ?>/phim_chieu_rap" class="block px-4 py-3 rounded-lg hover:bg-white/5 text-gray-300 hover:text-white transition-all">
          Phim chiếu rạp
        </a>
        <a href="<?php echo _HOST_URL ?>/dien_vien" class="block px-4 py-3 rounded-lg hover:bg-white/5 text-gray-300 hover:text-white transition-all">
          Diễn viên
        </a>
      </div>

      <!-- Genres Dropdown -->
      <div class="border-t border-white/10">
        <button onclick="document.getElementById('mobileGenresContent').classList.toggle('hidden'); this.querySelector('svg').classList.toggle('rotate-180');"
          class="w-full p-4 flex items-center justify-between text-white font-bold hover:bg-white/5 transition-all">
          <span>Thể loại</span>
          <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        <div id="mobileGenresContent" class="hidden px-4 pb-4">
          <div class="grid grid-cols-2 gap-2">
            <?php foreach (array_slice($allGenres, 0, 12) as $genre): ?>
              <a href="<?php echo _HOST_URL ?>/the_loai?id=<?php echo $genre['id'] ?>"
                class="px-3 py-2 rounded-lg text-xs text-gray-300 hover:bg-primary/20 hover:text-primary transition-all border border-transparent hover:border-primary/30">
                <?php echo $genre['name'] ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Countries Dropdown -->
      <div class="border-t border-white/10">
        <button onclick="document.getElementById('mobileCountriesContent').classList.toggle('hidden'); this.querySelector('svg').classList.toggle('rotate-180');"
          class="w-full p-4 flex items-center justify-between text-white font-bold hover:bg-white/5 transition-all">
          <span>Quốc gia</span>
          <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        <div id="mobileCountriesContent" class="hidden px-4 pb-4">
          <div class="grid grid-cols-2 gap-2">
            <?php foreach (array_slice($allCountries, 0, 8) as $country): ?>
              <a href="<?php echo _HOST_URL ?>/quoc_gia?id=<?php echo $country['id'] ?>"
                class="px-3 py-2 rounded-lg text-xs text-gray-300 hover:bg-primary/20 hover:text-primary transition-all border border-transparent hover:border-primary/30">
                <?php echo $country['name'] ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Mobile Search Overlay -->
  <div id="mobileSearchOverlay" class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-lg opacity-0 invisible transition-all duration-300 md:hidden">
    <div class="flex flex-col h-full p-4">
      <!-- Header -->
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-white font-bold text-lg">Tìm kiếm</h3>
        <button id="closeMobileSearch" class="w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 transition-colors">
          <i data-lucide="x" class="w-5 h-5 text-white"></i>
        </button>
      </div>
      <!-- Search Form -->
      <form action="<?php echo _HOST_URL; ?>/tim_kiem" method="GET" class="relative mb-4">
        <input type="text" name="tu_khoa" id="mobileSearchInput" placeholder="Tìm kiếm phim, diễn viên..."
          class="w-full px-5 py-4 pl-12 bg-white/10 border border-white/20 rounded-xl text-white text-base placeholder:text-gray-400 focus:outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/20">
        <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 px-4 py-2 bg-primary rounded-lg text-white text-sm font-medium hover:bg-primary/80 transition-colors">
          Tìm
        </button>
      </form>
      <!-- Quick Links -->
      <div class="flex flex-wrap gap-2 mb-6">
        <a href="<?php echo _HOST_URL; ?>/phim_le" class="px-3 py-1.5 bg-white/5 border border-white/10 rounded-full text-gray-300 text-xs hover:bg-primary/20 hover:text-primary transition-all">Phim lẻ</a>
        <a href="<?php echo _HOST_URL; ?>/phim_bo" class="px-3 py-1.5 bg-white/5 border border-white/10 rounded-full text-gray-300 text-xs hover:bg-primary/20 hover:text-primary transition-all">Phim bộ</a>
        <a href="<?php echo _HOST_URL; ?>/phim_chieu_rap" class="px-3 py-1.5 bg-white/5 border border-white/10 rounded-full text-gray-300 text-xs hover:bg-primary/20 hover:text-primary transition-all">Phim chiếu rạp</a>
        <a href="<?php echo _HOST_URL; ?>/dien_vien" class="px-3 py-1.5 bg-white/5 border border-white/10 rounded-full text-gray-300 text-xs hover:bg-primary/20 hover:text-primary transition-all">Diễn viên</a>
      </div>
      <p class="text-gray-500 text-xs text-center">Nhập tên phim hoặc diễn viên để tìm kiếm</p>
    </div>
  </div>

  <!-- Mobile Notification Dropdown -->
  <?php if (!empty($_SESSION['auth'])): ?>
    <div id="mobileNotificationDropdown" class="fixed top-14 right-2 z-[100] w-[calc(100vw-16px)] max-w-sm glass-panel rounded-xl border border-white/10 shadow-glass opacity-0 invisible transform translate-y-2 transition-all duration-300 overflow-hidden max-h-[70vh] flex flex-col md:hidden">
      <!-- Header -->
      <div class="px-4 py-3 border-b border-white/10 bg-white/5">
        <div class="flex items-center justify-between">
          <h3 class="text-white font-semibold text-sm">Thông báo</h3>
          <a href="<?php echo _HOST_URL; ?>/thong_bao" class="text-primary text-xs hover:text-secondary transition-colors">
            Xem tất cả
          </a>
        </div>
      </div>
      <!-- Notifications List -->
      <div class="overflow-y-auto custom-scroll flex-1">
        <?php if (!empty($notifications)): ?>
          <div class="py-2">
            <?php foreach ($notifications as $item): ?>
              <?php
              $config = [
                'icon' => 'info',
                'color' => 'bg-white/10',
                'icon_bg' => 'bg-white/5',
                'icon_color' => 'text-white/60',
                'title' => 'Thông báo hệ thống'
              ];
              switch ($item['type']) {
                case 'new_episode':
                  $config = ['icon' => 'film', 'color' => 'bg-primary', 'icon_bg' => 'bg-primary/20', 'icon_color' => 'text-primary', 'title' => 'Tập phim mới'];
                  break;
                case 'reply':
                  $config = ['icon' => 'message-square', 'color' => 'bg-secondary', 'icon_bg' => 'bg-secondary/20', 'icon_color' => 'text-secondary', 'title' => 'Phản hồi mới'];
                  break;
                case 'like':
                  $config = ['icon' => 'heart', 'color' => 'bg-red-500', 'icon_bg' => 'bg-red-500/20', 'icon_color' => 'text-red-500', 'title' => 'Lượt thích mới'];
                  break;
              }
              ?>
              <a href="<?php echo $item['link']; ?>" class="block px-4 py-3 hover:bg-white/5 transition-colors group border-l-2 border-transparent hover:border-primary">
                <div class="flex items-start gap-3">
                  <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 <?php echo $config['icon_bg']; ?>">
                    <i data-lucide="<?php echo $config['icon']; ?>" class="w-4 h-4 <?php echo $config['icon_color']; ?>"></i>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-medium mb-1"><?php echo $config['title']; ?></p>
                    <p class="text-slate-400 text-xs line-clamp-2"><?php echo html_entity_decode($item['message']); ?></p>
                    <span class="text-slate-500 text-[10px]"><?php echo timeAgo($item['created_at']); ?></span>
                  </div>
                  <div class="w-2 h-2 rounded-full <?php echo $config['color']; ?> flex-shrink-0 mt-1"></div>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="flex flex-col items-center justify-center py-10 text-slate-500">
            <i data-lucide="bell-off" class="w-10 h-10 mb-3 opacity-50"></i>
            <p class="text-sm">Chưa có thông báo</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <!-- Mobile Notification Overlay -->
    <div id="mobileNotificationOverlay" class="fixed inset-0 z-[99] bg-black/60 opacity-0 invisible transition-opacity duration-300 md:hidden"></div>
  <?php endif; ?>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      lucide.createIcons();

      // === Mobile Menu Toggle ===
      const mobileMenuBtn = document.getElementById('mobileMenuBtn');
      const closeMobileMenu = document.getElementById('closeMobileMenu');
      const mobileSidebar = document.getElementById('mobileSidebar');
      const mobileSidebarPanel = document.getElementById('mobileSidebarPanel');
      const mobileOverlay = document.getElementById('mobileOverlay');
      let isMobileMenuOpen = false;

      function toggleMobileMenu() {
        if (isMobileMenuOpen) {
          closeMobileMenuFunc();
        } else {
          openMobileMenu();
        }
      }

      function openMobileMenu() {
        mobileSidebar.classList.remove('pointer-events-none');
        mobileSidebarPanel.classList.remove('-translate-x-full');
        mobileOverlay.classList.remove('opacity-0');
        mobileOverlay.classList.add('opacity-100', 'pointer-events-auto');
        document.body.style.overflow = 'hidden';
        isMobileMenuOpen = true;
      }

      function closeMobileMenuFunc() {
        mobileSidebarPanel.classList.add('-translate-x-full');
        mobileOverlay.classList.remove('opacity-100', 'pointer-events-auto');
        mobileOverlay.classList.add('opacity-0');
        setTimeout(() => {
          mobileSidebar.classList.add('pointer-events-none');
          document.body.style.overflow = '';
        }, 300);
        isMobileMenuOpen = false;
      }

      if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleMobileMenu);
      if (closeMobileMenu) closeMobileMenu.addEventListener('click', closeMobileMenuFunc);
      if (mobileOverlay) mobileOverlay.addEventListener('click', closeMobileMenuFunc);

      // Expandable Search Functionality
      const searchIcon = document.getElementById('searchIcon');
      const searchInput = document.getElementById('searchInput');
      const searchContainer = document.getElementById('searchContainer');
      let isExpanded = false;

      searchIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        if (!isExpanded) {
          // Expand search
          searchInput.style.width = '280px';
          searchInput.style.opacity = '1';
          searchInput.style.pointerEvents = 'auto';
          searchInput.focus();
          isExpanded = true;
        }
      });

      // Close search when clicking outside
      document.addEventListener('click', function(e) {
        if (isExpanded && !searchContainer.contains(e.target)) {
          // Collapse search
          searchInput.style.width = '10px';
          searchInput.style.opacity = '0';
          searchInput.style.pointerEvents = 'none';
          searchInput.value = '';
          isExpanded = false;
        }
      });

      // Prevent closing when clicking inside the input
      searchInput.addEventListener('click', function(e) {
        e.stopPropagation();
      });

      // User Dropdown Toggle
      const userAvatarBtn = document.getElementById('userAvatarBtn');
      const userDropdown = document.getElementById('userDropdown');
      const dropdownChevron = document.getElementById('dropdownChevron');
      const userDropdownContainer = document.getElementById('userDropdownContainer');

      if (userAvatarBtn && userDropdown) {
        let isDropdownOpen = false;

        userAvatarBtn.addEventListener('click', function(e) {
          e.stopPropagation();
          isDropdownOpen = !isDropdownOpen;

          if (isDropdownOpen) {
            userDropdown.classList.remove('opacity-0', 'invisible', 'translate-y-2');
            userDropdown.classList.add('opacity-100', 'visible', 'translate-y-0');
            if (dropdownChevron) dropdownChevron.classList.add('rotate-180');
          } else {
            userDropdown.classList.add('opacity-0', 'invisible', 'translate-y-2');
            userDropdown.classList.remove('opacity-100', 'visible', 'translate-y-0');
            if (dropdownChevron) dropdownChevron.classList.remove('rotate-180');
          }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
          if (isDropdownOpen && !userDropdownContainer.contains(e.target)) {
            userDropdown.classList.add('opacity-0', 'invisible', 'translate-y-2');
            userDropdown.classList.remove('opacity-100', 'visible', 'translate-y-0');
            if (dropdownChevron) dropdownChevron.classList.remove('rotate-180');
            isDropdownOpen = false;
          }
        });
      }

      // Notification Dropdown Toggle
      const notificationBtn = document.getElementById('notificationBtn');
      const notificationDropdown = document.getElementById('notificationDropdown');
      const notificationDropdownContainer = document.getElementById('notificationDropdownContainer');

      if (notificationBtn && notificationDropdown) {
        let isNotificationOpen = false;

        notificationBtn.addEventListener('click', function(e) {
          e.stopPropagation();
          isNotificationOpen = !isNotificationOpen;

          if (isNotificationOpen) {
            notificationDropdown.classList.remove('opacity-0', 'invisible', 'translate-y-2');
            notificationDropdown.classList.add('opacity-100', 'visible', 'translate-y-0');
          } else {
            notificationDropdown.classList.add('opacity-0', 'invisible', 'translate-y-2');
            notificationDropdown.classList.remove('opacity-100', 'visible', 'translate-y-0');
          }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
          if (isNotificationOpen && !notificationDropdownContainer.contains(e.target)) {
            notificationDropdown.classList.add('opacity-0', 'invisible', 'translate-y-2');
            notificationDropdown.classList.remove('opacity-100', 'visible', 'translate-y-0');
            isNotificationOpen = false;
          }
        });
      }
    });
  </script>