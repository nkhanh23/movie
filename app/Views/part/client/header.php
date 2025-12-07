<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CineMagic AI</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo _HOST_URL_PUBLIC; ?>/assets/css/client/style.css">

  <!-- Import Map -->
  <script type="importmap">
    {
      "imports": {
        "lucide-react": "https://aistudiocdn.com/lucide-react@^0.556.0"
      }
    }
    </script>

  <!-- Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
  <!-- Navigation -->
  <nav class="fixed top-0 w-full z-50 bg-gradient-to-b from-black/90 to-transparent px-4 md:px-12 py-4 flex items-center justify-between transition-all duration-300">
    <div class="flex items-center gap-8">
      <h1 class="text-red-600 text-3xl font-bold tracking-tighter cursor-pointer">CINE<span class="text-white">MAGIC</span></h1>
      <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-300">
        <a href="#" class="hover:text-white transition-colors text-white font-bold">Home</a>
        <a href="#" class="hover:text-white transition-colors">TV Shows</a>
        <a href="#" class="hover:text-white transition-colors">Movies</a>
        <a href="#" class="hover:text-white transition-colors">New & Popular</a>
      </div>
    </div>

    <div class="flex items-center gap-6 text-white">
      <i data-lucide="search" class="w-5 h-5 cursor-pointer hover:text-gray-300"></i>
      <i data-lucide="bell" class="w-5 h-5 cursor-pointer hover:text-gray-300 hidden sm:block"></i>
      <div class="flex items-center gap-2 cursor-pointer group">
        <div class="w-8 h-8 rounded bg-blue-600 flex items-center justify-center font-bold">K</div>
        <i data-lucide="menu" class="w-5 h-5 md:hidden"></i>
      </div>
    </div>
  </nav>