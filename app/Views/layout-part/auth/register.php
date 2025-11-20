<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
// $msg = getSessionFlash('msg');
// $msg_type = getSessionFlash('msg_type');
// $errorsArr = getSessionFlash('errors');
// $oldData = getSessionFlash('oldData');

?>
<div class="card-back">
    <div class="center-wrap">
        <div class="section text-center">
            <!-- <div class="annouce-message aleart alert-danger">Thong bao loi hoac thanh cong</div> -->
            <?php
            if (!empty($msg) && !empty($msg_type)) {
                getMsg($msg, $msg_type);
            }
            ?>
            <form method="POST" action="/movie/register" enctype="multipart/form-data">
                <h4 class="mb-4 pb-3">Sign Up</h4>
                <div class="form-group">
                    <input type="text" name="fullname" class="form-style" placeholder="Your Full Name" id="logname"
                        autocomplete="off" value="<?php
                                                    if (!empty($oldData)) {
                                                        echo oldData($oldData, 'fullname');
                                                    } ?>">
                    <i class="input-icon uil uil-user"></i>
                    <?php
                    if (!empty($errorsArr)) {
                        echo formError($errorsRegister, 'fullname');
                    }
                    ?>
                </div>
                <div class="form-group mt-2">
                    <input type="text" name="email" class="form-style" placeholder="Your Email" id="logemail"
                        autocomplete="off" value="<?php
                                                    if (!empty($oldData)) {
                                                        echo oldData($oldData, 'email');
                                                    } ?>">
                    <i class="input-icon uil uil-at"></i>
                    <?php
                    if (!empty($errorsRegister)) {
                        echo formError($errorsRegister, 'email');
                    }
                    ?>
                </div>
                <div class="form-group mt-2">
                    <input type="password" name="password" class="form-style" placeholder="Your Password" id="logpass"
                        autocomplete="off">
                    <i class="input-icon uil uil-lock-alt"></i>
                    <?php
                    if (!empty($errorsRegister)) {
                        echo formError($errorsRegister, 'password');
                    }
                    ?>
                </div>
                <div class="form-group mt-2">
                    <input type="password" name="confirm_pass" class="form-style" placeholder="Confirm Your Password"
                        id="confirm_pass" autocomplete="off">
                    <i class="input-icon uil uil-lock-alt"></i>
                    <?php
                    if (!empty($errorsRegister)) {
                        echo formError($errorsRegister, 'confirm_pass');
                    }
                    ?>
                </div>
                <button type="submit" href="#" class="btn mt-4">Đăng ký</button>
            </form>
        </div>
    </div>
</div>