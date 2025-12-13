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
    public function getCommentsByMovie($movieId, $userId = 0)
    {
        // Chỉ lấy những bình luận có status = 1 (được hiển thị)
        $sql = "SELECT c.*, u.fullname as fullname, u.avartar as avartar,
        (SELECT COUNT(*) FROM comment_likes cl WHERE cl.comment_id = c.id) as like_count,
        (SELECT COUNT(*) FROM comment_likes cl WHERE cl.comment_id = c.id AND cl.user_id = $userId) as is_liked
        FROM comments c 
        JOIN users u ON c.user_id = u.id 
        WHERE c.movie_id = ? AND c.status = 1 
        ORDER BY c.created_at DESC";

        return $this->query($sql, [$movieId]);
    }

    // Nếu like rồi thì xóa, chưa thì thêm
    public function toggleLike($userId, $commentId)
    {
        // Kiểm tra xem đã like chưa
        $sqlCheck = "SELECT id FROM comment_likes WHERE user_id = $userId AND comment_id = $commentId";
        $check = $this->getOne($sqlCheck);

        if (!empty($check)) {
            // Đã like -> Xóa (Unlike)
            $this->delete('comment_likes', "id = " . $check['id']);
            return 'unliked';
        } else {
            // Chưa like -> Thêm (Like)
            $data = [
                'user_id' => $userId,
                'comment_id' => $commentId,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->insert('comment_likes', $data);
            return 'liked';
        }
    }

    // Đếm lại số like của 1 comment để trả về cho Client cập nhật số
    public function countLikes($commentId)
    {
        $sql = "SELECT COUNT(*) as total FROM comment_likes WHERE comment_id = $commentId";
        $result = $this->getOne($sql);
        return $result['total'] ?? 0;
    }

    // Thêm bình luận mới
    public function addComment($data)
    {
        return $this->insert('comments', $data);
    }

    // Lấy chi tiết 1 bình luận theo ID
    public function getCommentById($id)
    {
        $sql = "SELECT * FROM comments WHERE id = $id";
        return $this->getOne($sql);
    }

    // Kiểm tra xem bình luận có con không
    public function hasChildComments($id)
    {
        $sql = "SELECT COUNT(*) as count FROM comments WHERE parent_id = $id AND status = 1";
        $result = $this->getOne($sql);
        return !empty($result) && $result['count'] > 0;
    }

    // Xóa đệ quy: Xóa hết con cháu trước rồi mới xóa cha
    public function deleteRecursive($id)
    {
        // 1. Tìm tất cả con trực tiếp
        $sqlChildren = "SELECT id FROM comments WHERE parent_id = $id";
        $children = $this->query($sqlChildren);

        // 2. Xóa đệ quy từng con
        if (!empty($children)) {
            foreach ($children as $child) {
                $this->deleteRecursive($child['id']);
            }
        }

        // 3. Xóa chính nó
        $this->delete('comments', "id = $id");
    }
}
