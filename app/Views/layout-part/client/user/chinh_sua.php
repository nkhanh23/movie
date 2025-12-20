<!-- Modal Overlay (hidden by default) -->
<div id="editProfileModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="display: none;">
    <!-- Dimmed Background -->
    <div onclick="closeEditModal()" class="absolute inset-0 bg-black/70 backdrop-blur-md transition-all"></div>

    <!-- Modal Content -->
    <div class="relative w-full max-w-[500px] overflow-hidden rounded-3xl border border-primary/30 bg-[#0b1121]/95 shadow-[0_0_50px_-12px_rgba(217,108,22,0.5)] backdrop-blur-2xl ring-1 ring-white/10 animate-modal-appear">
        <!-- Animated Background Effects -->
        <div class="pointer-events-none absolute inset-0 z-0">
            <div class="absolute -top-[50%] -left-[50%] w-[200%] h-[200%] bg-[conic-gradient(transparent_0deg,transparent_90deg,rgba(217,108,22,0.15)_180deg,transparent_270deg)] animate-spin-slow opacity-50"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 mix-blend-overlay"></div>
        </div>

        <!-- Modal Header -->
        <div class="relative z-10 flex items-center justify-between border-b border-white/5 px-8 py-6">
            <h3 class="text-xl font-bold tracking-tight text-white drop-shadow-[0_0_10px_rgba(217,108,22,0.5)]">Chỉnh Sửa Hồ Sơ</h3>
            <button onclick="closeEditModal()" class="group rounded-full p-2 text-slate-400 hover:bg-white/5 hover:text-white transition-all">
                <span class="material-symbols-outlined text-xl transition-transform group-hover:rotate-90">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="relative z-10 px-8 py-8 space-y-8 max-h-[70vh] overflow-y-auto">
            <!-- Avatar Section -->
            <div class="flex flex-col items-center justify-center">
                <div class="group relative cursor-pointer">
                    <div class="absolute inset-[-8px] rounded-full border border-primary/40 border-dashed animate-spin-slow"></div>
                    <div class="absolute inset-[-16px] rounded-full border border-secondary/30 border-dotted animate-spin-reverse-slow opacity-60"></div>
                    <div class="relative h-28 w-28 overflow-hidden rounded-full border-2 border-white/10 bg-slate-800 shadow-[0_0_30px_rgba(217,108,22,0.3)]">
                        <div class="h-full w-full bg-cover bg-center opacity-90 transition-all duration-500 group-hover:scale-110 group-hover:opacity-50" style="background-image: url('<?php echo !empty($_SESSION['auth']['avatar']) ? $_SESSION['auth']['avatar'] : 'https://lh3.googleusercontent.com/aida-public/AB6AXuDp5z9ZOQaU3DTgLKIV6PUXCrR683wgb-cfCtJkQb8fjthg6JZpJSoLnJAB_yLhGXcB50ZUZavHxOwTg49G1jP75MI4G4Ze4X59DwMVmAw5WSNPMbtKXDfKAQ_gbF3HBmgak9heLsPTafhUNnl0XnjySGe2aXePkhP3jNqlHLilcq_MOq77GLgj8f7DUbiYQ69J76kGeQi_Jc4pRNRZmiN24BpItbsEpLMeh0vaXya_5iTPRrZAibG83nrS3UDYSj-8bXuXmQPaH3hG'; ?>');"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 backdrop-blur-sm transition-opacity duration-300 group-hover:opacity-100">
                            <span class="material-symbols-outlined text-3xl text-primary drop-shadow-[0_0_8px_rgba(217,108,22,1)]">upload</span>
                        </div>
                    </div>
                    <div class="absolute -right-2 top-0 h-2 w-2 rounded-full bg-primary blur-[1px] shadow-[0_0_10px_#D96C16] animate-pulse"></div>
                    <div class="absolute -left-2 bottom-4 h-1.5 w-1.5 rounded-full bg-secondary blur-[1px] shadow-[0_0_10px_#F29F05] animate-pulse delay-700"></div>
                </div>
                <p class="mt-4 text-xs font-medium uppercase tracking-widest text-primary/90">Cập nhật ảnh đại diện</p>
            </div>

            <!-- Form Fields -->
            <form id="editProfileForm" class="space-y-5">
                <div class="group">
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-400">Họ và tên</label>
                    <div class="relative">
                        <input name="fullname" class="peer w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-sm font-medium text-white shadow-inner placeholder:text-slate-600 focus:border-primary/60 focus:bg-primary/5 focus:outline-none focus:ring-1 focus:ring-primary/60 transition-all duration-300" type="text" value="<?php echo !empty($userInfor['fullname']) ? $userInfor['fullname'] : ''; ?>" />
                        <div class="pointer-events-none absolute inset-0 rounded-xl shadow-[0_0_0_0_rgba(217,108,22,0)] transition-shadow duration-300 peer-focus:shadow-[0_0_15px_rgba(217,108,22,0.2)]"></div>
                    </div>
                </div>

                <div class="group">
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-400">Email</label>
                    <div class="relative">
                        <input name="email" class="peer w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-sm font-medium text-white shadow-inner placeholder:text-slate-600 focus:border-primary/60 focus:bg-primary/5 focus:outline-none focus:ring-1 focus:ring-primary/60 transition-all duration-300" type="email" value="<?php echo !empty($userInfor['email']) ? $userInfor['email'] : ''; ?>" />
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 peer-focus:text-primary transition-colors">lock</span>
                    </div>
                </div>

                <div class="group">
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-400">Số điện thoại</label>
                    <div class="relative">
                        <input name="phone" class="peer w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-sm font-medium text-white shadow-inner placeholder:text-slate-600 focus:border-primary/60 focus:bg-primary/5 focus:outline-none focus:ring-1 focus:ring-primary/60 transition-all duration-300" type="tel" value="<?php echo !empty($userInfor['phone']) ? $userInfor['phone'] : ''; ?>" />
                    </div>
                </div>

                <div class="group">
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-400">Địa chỉ</label>
                    <div class="relative">
                        <input name="address" class="peer w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-sm font-medium text-white shadow-inner placeholder:text-slate-600 focus:border-primary/60 focus:bg-primary/5 focus:outline-none focus:ring-1 focus:ring-primary/60 transition-all duration-300" type="tel" value="<?php echo !empty($userInfor['address']) ? $userInfor['address'] : ''; ?>" />
                    </div>
                </div>
            </form>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4 pt-2">
                <button onclick="closeEditModal()" class="flex-1 rounded-xl border border-white/10 bg-white/5 py-3.5 text-sm font-semibold text-slate-300 hover:bg-white/10 hover:text-white transition-all hover:shadow-[0_0_15px_rgba(255,255,255,0.05)]">
                    Hủy
                </button>
                <button onclick="saveProfile()" class="group relative flex-1 overflow-hidden rounded-xl bg-gradient-to-r from-primary to-secondary py-3.5 text-sm font-bold tracking-wide text-white shadow-[0_0_20px_rgba(217,108,22,0.4)] hover:shadow-[0_0_30px_rgba(217,108,22,0.6)] transition-all">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        Lưu thay đổi
                        <span class="material-symbols-outlined text-lg">check</span>
                    </span>
                    <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:animate-shimmer"></div>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes modal-appear {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-20px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .animate-modal-appear {
        animation: modal-appear 0.3s ease-out forwards;
    }

    @keyframes spin-slow {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .animate-spin-slow {
        animation: spin-slow 12s linear infinite;
    }

    .animate-spin-reverse-slow {
        animation: spin-slow 15s linear infinite reverse;
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-150%);
        }

        100% {
            transform: translateX(150%);
        }
    }

    .group-hover\:animate-shimmer:hover {
        animation: shimmer 2.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>

<script>
    function openEditModal() {
        const modal = document.getElementById('editProfileModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        const modal = document.getElementById('editProfileModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function saveProfile() {
        // Add your save logic here
        console.log('Saving profile...');
        // For now, just close the modal
        closeEditModal();
    }

    // Close modal when pressing Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeEditModal();
        }
    });
</script>