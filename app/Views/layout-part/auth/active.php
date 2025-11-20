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

?>
<a href="https://front.codes/" class="logo" target="_blank">
    <img src="https://assets.codepen.io/1462889/fcy.png" alt="">
</a>
<?php
$filter = filterData('get');
if (!empty($filter['token'])):
    $token = $filter['token'];
    $checkToken = $coreModel->getOne("SELECT * FROM users WHERE active_token = '$token'");
    echo '<pre>';
    print_r($checkToken);
    echo '</pre>';
?>
    <div class="section">
        <div class="container">
            <div class="row full-height justify-content-center">
                <div class="col-12 text-center align-self-center py-5">
                    <div class="section pb-5 pt-5 pt-sm-2 text-center">
                        <input class="checkbox" type="checkbox" id="reg-log" name="reg-log"
                            <?php echo $activeTab == 'signup' ? 'checked' : '' ?> />
                        <div class="card-3d-wrap mx-auto">
                            <?php
                            if (!empty($checkToken)):
                                $data = [
                                    'status' => 1,
                                    'active_token' => NULL,
                                    'updated_at' => date('Y:m:d H:i:s')
                                ];
                                $condition = 'id=' . $checkToken['id'];
                                $coreModel->update('users', $data, $condition);
                            ?>
                                <div class="card-3d-wrapper">
                                    <div class="card-front">
                                        <div class="center-wrap">
                                            <div class="section text-center">
                                                <h4 class="mb-4 pb-3">Xác nhận tài khoản thành công</h4>
                                                <button href="./login.php" class="btn mt-4">submit</button>
                                                <p class="mb-0 mt-4 text-center"><a href="#0" class="link">Forgot your
                                                        password?</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else : ?>
                                <div class="card-3d-wrapper">
                                    <div class="card-front">
                                        <div class="center-wrap">
                                            <div class="section text-center">
                                                <h4 class="mb-4 pb-3">Kích hoạt tài khoản không thành công .Đường link đã hết
                                                    hạn</h4>
                                                <button href="./login.php" class="btn mt-4">submit</button>
                                                <p class="mb-0 mt-4 text-center"><a href="#0" class="link">Forgot your
                                                        password?</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php else: ?>
    <div class="section">
        <div class="container">
            <div class="row full-height justify-content-center">
                <div class="col-12 text-center align-self-center py-5">
                    <div class="section pb-5 pt-5 pt-sm-2 text-center">
                        <input class="checkbox" type="checkbox" id="reg-log" name="reg-log"
                            <?php echo $activeTab == 'signup' ? 'checked' : '' ?> />
                        <div class="card-3d-wrap mx-auto">
                            <div class="card-3d-wrapper">
                                <div class="card-front">
                                    <div class="center-wrap">
                                        <div class="section text-center">
                                            <h4 class="mb-4 pb-3">Trang kích hoạt đã hết hạn hoặc không tồn tại</h4>
                                            <button href="./login.php" class="btn mt-4">submit</button>
                                            <p class="mb-0 mt-4 text-center"><a href="#0" class="link">Forgot your
                                                    password?</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php endif; ?>