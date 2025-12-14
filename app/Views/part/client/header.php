<?php
$is_logged = isLogin();

// Fetch genres and countries for dropdown
$genresModel = new Genres();
$moviesModel = new Movies();
$allGenres = $genresModel->getAllGenres();
$allCountries = $moviesModel->getAllCountries();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="referrer" content="no-referrer">
  <title>Phê Phim</title>

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
          }
        },
      },
    }
  </script>

  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <link rel="stylesheet" href="<?php echo _HOST_URL_PUBLIC; ?>/assets/css/client/style.css">

  <script type="importmap">
    {
      "imports": {
        "lucide-react": "https://aistudiocdn.com/lucide-react@^0.556.0"
      }
    }
  </script>

  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
  <?php require_once 'app/Views/part/client/intro-splash.php'; ?>
  <nav class="fixed top-0 w-full z-50 bg-gradient-to-b from-black/90 to-transparent px-4 md:px-12 py-4 flex items-center justify-between transition-all duration-300">
    <div class="flex items-center gap-8">
      <img src="<?php echo _HOST_URL_PUBLIC; ?>/img/logo/PhePhim.png" alt="" class="h-16 w-auto">



      <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-300 ml-4">
        <a href="<?php echo _HOST_URL ?>" class="hover:text-white transition-colors text-white font-bold">Trang chủ</a>
        <a href="<?php echo _HOST_URL ?>/phim_le" class="hover:text-white transition-colors">Phim lẻ</a>
        <a href="<?php echo _HOST_URL ?>/phim_bo" class="hover:text-white transition-colors">Phim bộ</a>
        <a href="<?php echo _HOST_URL ?>/phim_chieu_rap" class="hover:text-white transition-colors">Phim chiếu rạp</a>

        <!-- Genres Dropdown -->
        <div class="relative group">
          <button class="hover:text-white transition-colors flex items-center gap-1">
            Thể loại
            <i class="fa-solid fa-caret-down text-xs"></i>
          </button>
          <div class="absolute left-0 top-full pt-2 hidden group-hover:block w-[800px] glass-panel rounded-xl p-4 border border-white/10 shadow-neon">
            <div class="grid grid-cols-6 gap-2">
              <?php foreach ($allGenres as $genre): ?>
                <a href="<?php echo _HOST_URL ?>/the_loai?id=<?php echo $genre['id'] ?>"
                  class="px-3 py-2 rounded-lg text-xs hover:bg-primary/20 hover:text-primary transition-all border border-transparent hover:border-primary/30">
                  <?php echo $genre['name'] ?>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Countries Dropdown -->
        <div class="relative group">
          <button class="hover:text-white transition-colors flex items-center gap-1">
            Quốc gia
            <i class="fa-solid fa-caret-down text-xs"></i>
          </button>
          <div class="absolute left-0 top-full pt-2 hidden group-hover:block w-[650px] glass-panel rounded-xl p-4 border border-white/10 shadow-neon">
            <div class="grid grid-cols-4 gap-2">
              <?php foreach ($allCountries as $country): ?>
                <a href="<?php echo _HOST_URL ?>/quoc_gia?id=<?php echo $country['id'] ?>"
                  class="px-3 py-2 rounded-lg text-xs hover:bg-primary/20 hover:text-primary transition-all border border-transparent hover:border-primary/30">
                  <?php echo $country['name'] ?>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <a href="<?php echo _HOST_URL ?>/dien_vien" class="hover:text-white transition-colors">Diễn viên</a>


      </div>
    </div>

    <div class="flex items-center gap-6 text-white">
      <!-- Expandable Search Bar -->
      <form action="<?php echo _HOST_URL; ?>/tim_kiem" method="GET">
        <div class="hidden md:flex items-center relative">
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
      <i data-lucide="bell" class="w-5 h-5 cursor-pointer hover:text-gray-300 hidden sm:block"></i>
      <div class="flex items-center gap-2 cursor-pointer group">
        <div class="w-8 h-8 rounded bg-blue-600 flex items-center justify-center font-bold">K</div>
        <i data-lucide="menu" class="w-5 h-5 md:hidden"></i>
      </div>
    </div>
  </nav>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      lucide.createIcons();

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
    });
  </script>