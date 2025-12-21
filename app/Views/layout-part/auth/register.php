<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
?>
<!-- Register Form Content -->
<form method="POST" action="/movie/register" enctype="multipart/form-data" class="flex flex-col gap-5">

    <!-- Full Name -->
    <div class="flex flex-col gap-2">
        <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 ml-1 mb-1">Họ và tên</label>
        <div class="relative group/input">
            <span class="absolute left-5 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-500 group-focus-within/input:text-primary transition-colors duration-300">person</span>
            <input type="text" name="fullname"
                class="w-full bg-slate-950/40 border border-white/10 rounded-2xl px-5 pl-14 py-4 text-white placeholder-slate-600 focus:placeholder-slate-500 transition-all duration-300 input-neon focus:border-primary/50 focus:shadow-[0_0_20px_rgba(217,108,22,0.15)]"
                placeholder="Nguyễn Văn A"
                autocomplete="off"
                value="<?php if (!empty($oldData)) {
                            echo oldData($oldData, 'fullname');
                        } ?>">
            <span class="absolute right-4 top-1/2 -translate-y-1/2 flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-20 group-focus-within/input:opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-primary/20 group-focus-within/input:bg-primary"></span>
            </span>
        </div>
        <?php if (!empty($errorsRegister)) {
            echo formError($errorsRegister, 'fullname');
        } ?>
    </div>

    <!-- Email -->
    <div class="flex flex-col gap-2">
        <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 ml-1 mb-1">Email</label>
        <div class="relative group/input">
            <span class="absolute left-5 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-500 group-focus-within/input:text-primary transition-colors duration-300">alternate_email</span>
            <input type="text" name="email"
                class="w-full bg-slate-950/40 border border-white/10 rounded-2xl px-5 pl-14 py-4 text-white placeholder-slate-600 focus:placeholder-slate-500 transition-all duration-300 input-neon focus:border-primary/50 focus:shadow-[0_0_20px_rgba(217,108,22,0.15)]"
                placeholder="name@example.com"
                autocomplete="off"
                value="<?php if (!empty($oldData)) {
                            echo oldData($oldData, 'email');
                        } ?>">
        </div>
        <?php if (!empty($errorsRegister)) {
            echo formError($errorsRegister, 'email');
        } ?>
    </div>

    <!-- Password -->
    <div class="flex flex-col gap-2">
        <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 ml-1 mb-1">Mật khẩu</label>
        <div class="relative group/input">
            <span class="absolute left-5 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-500 group-focus-within/input:text-primary transition-colors duration-300">lock</span>
            <input type="password" name="password"
                class="w-full bg-slate-950/40 border border-white/10 rounded-2xl px-5 pl-14 py-4 text-white placeholder-slate-600 focus:placeholder-slate-500 transition-all duration-300 input-neon focus:border-primary/50 focus:shadow-[0_0_20px_rgba(217,108,22,0.15)]"
                placeholder="••••••••"
                autocomplete="off">
        </div>
        <?php if (!empty($errorsRegister)) {
            echo formError($errorsRegister, 'password');
        } ?>
    </div>

    <!-- Confirm Password -->
    <div class="flex flex-col gap-2">
        <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 ml-1 mb-1">Nhập lại mật khẩu</label>
        <div class="relative group/input">
            <span class="absolute left-5 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-500 group-focus-within/input:text-primary transition-colors duration-300">lock_reset</span>
            <input type="password" name="confirm_pass"
                class="w-full bg-slate-950/40 border border-white/10 rounded-2xl px-5 pl-14 py-4 text-white placeholder-slate-600 focus:placeholder-slate-500 transition-all duration-300 input-neon focus:border-primary/50 focus:shadow-[0_0_20px_rgba(217,108,22,0.15)]"
                placeholder="••••••••"
                autocomplete="off">
        </div>
        <?php if (!empty($errorsRegister)) {
            echo formError($errorsRegister, 'confirm_pass');
        } ?>
    </div>

    <button type="submit" class="relative overflow-hidden btn-shimmer group w-full flex items-center justify-center gap-3 px-8 py-4 rounded-xl text-white text-base font-bold tracking-wide shadow-[0_0_25px_rgba(217,108,22,0.3)] hover:shadow-[0_0_40px_rgba(217,108,22,0.5)] transition-all transform hover:scale-[1.02] border border-white/20 mt-4">
        <span class="relative z-10 flex items-center gap-2 drop-shadow-md">
            Đăng Ký
            <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
        </span>
        <div class="absolute inset-0 bg-white/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </button>
</form>