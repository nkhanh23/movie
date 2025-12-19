<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('admin/header');
layout('admin/sidebar');

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$errors = getSessionFlash('errors');
?>
<!-- EDIT SOURCE VIEW -->
<section id="edit-source-view" class="content-section active" style="padding: 30px;">
    <div class="card">
        <form class="form-grid" action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $oldData['id'] ?>">
            <input type="hidden" name="movie_id" value="<?php echo $oldData['movie_id'] ?>">
            <input type="hidden" name="season_id" value="<?php echo $oldData['season_id'] ?>">
            <input type="hidden" name="episode_id" value="<?php echo $oldData['episode_id'] ?>">

            <div class="form-group full-width">
                <label>Loại</label>
                <input type="text" name="voice_type" id="voice_type" placeholder="Thuyết minh"
                    value="<?php echo !empty($oldData['voice_type']) ? $oldData['voice_type'] : '' ?>">
            </div>
            <div class="form-group full-width">
                <label>Link Video</label>
                <input type="text" name="source_url" id="source_url"
                    value="<?php echo !empty($oldData['source_url']) ? $oldData['source_url'] : '' ?>">
            </div>
            <div class="form-actions full-width">
                <button type="submit" class="btn btn-primary">Lưu Nguồn</button>
            </div>
        </form>
    </div>
</section>