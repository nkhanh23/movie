<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
$data = [
    'tittle' => 'Đăng nhập hệ thống'
];
layout('admin/header-auth', $data);
// đọc flash xác định tab đang active
$activeTab = getSessionFlash('active_tab') ?? 'login';
$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$errorsArr = getSessionFlash('errors');
$oldData = getSessionFlash('oldData');
// Tách lỗi cho từng form
$errorsLogin    = $activeTab === 'login'  ? $errorsArr : [];
$errorsRegister = $activeTab === 'signup' ? $errorsArr : [];
?>
<a href="https://front.codes/" class="logo" target="_blank">
    <img src="https://assets.codepen.io/1462889/fcy.png" alt="">
</a>

<div class="section">
    <div class="container">
        <div class="row full-height justify-content-center">
            <div class="col-12 text-center align-self-center py-5">
                <div class="section pb-5 pt-5 pt-sm-2 text-center">
                    <h6 class="mb-0 pb-3"><span>Log In </span><span>Sign Up</span></h6>
                    <input class="checkbox" type="checkbox" id="reg-log" name="reg-log"
                        <?php echo $activeTab == 'signup' ? 'checked' : '' ?> />
                    <label for="reg-log"></label>
                    <div class="card-3d-wrap mx-auto">
                        <div class="card-3d-wrapper">
                            <div class="card-front">
                                <div class="center-wrap">
                                    <div class="section text-center">
                                        <?php
                                        if (!empty($msg) && !empty($msg_type)) {
                                            getMsg($msg, $msg_type);
                                        }
                                        ?>
                                        <form method="POST" action="" enctype="multipart/form-data">
                                            <h4 class="mb-4 pb-3">Log In</h4>
                                            <div class="form-group">
                                                <input type="email" name="email" class="form-style"
                                                    placeholder="Your Email" id="logemail" autocomplete="off" value="<?php
                                                                                                                        if (!empty($oldData)) {
                                                                                                                            echo oldData($oldData, 'email');
                                                                                                                        } ?>">
                                                <i class="input-icon uil uil-at"></i>
                                                <?php
                                                if (!empty($errorsLogin)) {
                                                    echo formError($errorsLogin, 'email');
                                                }
                                                ?>
                                            </div>
                                            <div class="form-group mt-2">
                                                <input type="password" name="password" class="form-style"
                                                    placeholder="Your Password" id="logpass" autocomplete="off">
                                                <i class="input-icon uil uil-lock-alt"></i>
                                                <?php
                                                if (!empty($errorsLogin)) {
                                                    echo formError($errorsLogin, 'password');
                                                }
                                                ?>
                                            </div>
                                            <button type="submit" href="#" class="btn mt-4">submit</button>
                                            <p class="mb-0 mt-4 text-center"><a href="#0" class="link">Forgot your
                                                    password?</a></p>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php
                            layoutPart('auth/register', [
                                'msg'            => $msg,
                                'msg_type'       => $msg_type,
                                'errorsRegister' => $errorsRegister,
                                'oldData'        => $oldData,
                            ]);
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>