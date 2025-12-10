<?php
class Comments extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }
    // ADMIN

    // ------------------------------------- CLIENT ------------------------------------------------------
    // Lấy danh sách bình luận của phim (kèm thông tin user)
    public function getCommentsByMovie($movieId)
    {
        // Chỉ lấy những bình luận có status = 1 (được hiển thị)
        $sql = "SELECT c.*, u.fullname, u.avatar 
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.movie_id = ? AND c.status = 1 
                ORDER BY c.created_at DESC";

        return $this->query($sql, [$movieId]);
    }

    // Thêm bình luận mới
    public function addComment($data)
    {
        return $this->insert('comments', $data);
    }
}