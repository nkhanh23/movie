       <?php
        // echo "<pre>";
        // print_r($getAllGenres);
        // echo "</pre>";
        // die();
        ?>
       <div class="glass-panel rounded-2xl p-6 md:p-10 mb-8 border border-glass-border">
           <div class="flex items-center justify-between mb-6">
               <h2 class="text-xl font-bold text-white flex items-center gap-2">
                   <span class="material-symbols-outlined text-primary">tune</span>
                   Bộ lọc phim
               </h2>
               <button class="group flex items-center gap-2 px-4 py-2 rounded-lg bg-white/5 hover:bg-primary/20 hover:shadow-neon-sm transition-all">
                   <span class="material-symbols-outlined text-gray-400 text-[18px] group-hover:text-primary transition-colors">restart_alt</span>
                   <span class="text-sm font-medium text-gray-300 group-hover:text-white transition-colors">Đặt lại</span>
               </button>
           </div>

           <!-- Full Width Filters (Genre & Country) -->
           <div class="space-y-6 mb-6">
               <!-- Genre Filter -->
               <div class="flex flex-col gap-3">
                   <div class="flex items-center gap-2 mb-1">
                       <span class="material-symbols-outlined text-primary text-[20px]">movie_filter</span>
                       <span class="text-sm font-semibold text-white">Thể loại</span>
                   </div>
                   <div class="flex flex-wrap gap-2">
                       <?php foreach ($getAllGenres as $genre) : ?>
                           <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-primary/20 text-primary border border-primary/30 hover:bg-secondary/30 hover:text-secondary transition-all"><?= $genre['name'] ?></button>
                       <?php endforeach ?>
                   </div>
               </div>

               <!-- Country Filter -->
               <div class="flex flex-col gap-3">
                   <div class="flex items-center gap-2 mb-1">
                       <span class="material-symbols-outlined text-primary text-[20px]">public</span>
                       <span class="text-sm font-semibold text-white">Quốc gia</span>
                   </div>
                   <div class="flex flex-wrap gap-2">
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Việt Nam</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Hàn Quốc</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Mỹ</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Trung Quốc</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Nhật Bản</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Thái Lan</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Anh</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Pháp</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Đức</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Ấn Độ</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Úc</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Canada</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Nga</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Tây Ban Nha</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Ý</button>
                   </div>
               </div>
           </div>

           <!-- Grid Filters (Other filters) -->
           <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
               <!-- Movie Type Filter -->
               <div class="flex flex-col gap-3">
                   <div class="flex items-center gap-2 mb-1">
                       <span class="material-symbols-outlined text-primary text-[20px]">theaters</span>
                       <span class="text-sm font-semibold text-white">Loại phim</span>
                   </div>
                   <div class="flex flex-wrap gap-2">
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-primary/20 text-primary border border-primary/30 hover:bg-secondary/30 hover:text-secondary transition-all">Phim lẻ</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Phim bộ</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Phim chiếu rạp</button>
                   </div>
               </div>

               <!-- Year Filter (Buttons) -->
               <div class="flex flex-col gap-3">
                   <div class="flex items-center gap-2 mb-1">
                       <span class="material-symbols-outlined text-primary text-[20px]">calendar_month</span>
                       <span class="text-sm font-semibold text-white">Năm phát hành</span>
                   </div>
                   <div class="flex flex-wrap gap-2">
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">2024</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">2023</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">2022</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">2021</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">2020</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">2019</button>
                       <button class="px-2 py-1.5 rounded-full text-xs font-medium text-gray-500 hover:text-primary transition-colors">Khác</button>
                   </div>
               </div>

               <!-- Age Rating Filter -->
               <div class="flex flex-col gap-3">
                   <div class="flex items-center gap-2 mb-1">
                       <span class="material-symbols-outlined text-primary text-[20px]">verified_user</span>
                       <span class="text-sm font-semibold text-white">Xếp hạng</span>
                   </div>
                   <div class="flex flex-wrap gap-2">
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">P - Phổ biến</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">T13 - 13+</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">T16 - 16+</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">T18 - 18+</button>
                   </div>
               </div>

               <!-- Version Filter -->
               <div class="flex flex-col gap-3">
                   <div class="flex items-center gap-2 mb-1">
                       <span class="material-symbols-outlined text-primary text-[20px]">language</span>
                       <span class="text-sm font-semibold text-white">Phiên bản</span>
                   </div>
                   <div class="flex flex-wrap gap-2">
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Phụ đề</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Lồng tiếng</button>
                       <button class="px-3 py-1.5 rounded-full text-xs font-medium bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary transition-all">Thuyết minh</button>
                   </div>
               </div>

               <!-- Tech Specs Filter -->
               <div class="flex flex-col gap-3">
                   <div class="flex items-center gap-2 mb-1">
                       <span class="material-symbols-outlined text-primary text-[20px]">settings</span>
                       <span class="text-sm font-semibold text-white">Chất lượng</span>
                   </div>
                   <div class="flex flex-wrap gap-2">
                       <label class="group flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/20 border border-primary/30 cursor-pointer hover:bg-secondary/30 transition-all">
                           <input type="checkbox" checked class="peer sr-only" />
                           <span class="material-symbols-outlined text-primary text-[16px] peer-checked:text-secondary">4k</span>
                           <span class="text-xs font-medium text-primary peer-checked:text-secondary">4K</span>
                       </label>
                       <label class="group flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 cursor-pointer hover:border-primary/30 transition-all">
                           <input type="checkbox" class="peer sr-only" />
                           <span class="material-symbols-outlined text-gray-400 text-[16px] peer-checked:text-primary">hdr_on</span>
                           <span class="text-xs font-medium text-gray-400 peer-checked:text-primary">HDR</span>
                       </label>
                       <label class="group flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 cursor-pointer hover:border-primary/30 transition-all">
                           <input type="checkbox" class="peer sr-only" />
                           <span class="material-symbols-outlined text-gray-400 text-[16px] peer-checked:text-primary">surround_sound</span>
                           <span class="text-xs font-medium text-gray-400 peer-checked:text-primary">Dolby</span>
                       </label>
                   </div>
               </div>

               <!-- Sort Filter -->
               <div class="flex flex-col gap-3">
                   <div class="flex items-center gap-2 mb-1">
                       <span class="material-symbols-outlined text-primary text-[20px]">sort</span>
                       <span class="text-sm font-semibold text-white">Sắp xếp</span>
                   </div>
                   <div class="flex flex-wrap gap-2">
                       <select class="px-4 py-2 rounded-lg bg-white/5 text-gray-300 border border-white/10 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 hover:border-primary/30 transition-all text-sm cursor-pointer w-full">
                           <option value="latest">Mới nhất</option>
                           <option value="popular">Phổ biến nhất</option>
                           <option value="rating">Đánh giá cao</option>
                           <option value="views">Lượt xem nhiều</option>
                           <option value="name-asc">Tên A-Z</option>
                           <option value="name-desc">Tên Z-A</option>
                           <option value="year-desc">Năm giảm dần</option>
                           <option value="year-asc">Năm tăng dần</option>
                       </select>
                   </div>
               </div>
           </div>
       </div>