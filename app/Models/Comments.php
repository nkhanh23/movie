<?php
class Comments extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }
    // ADMIN

    public function getAllComment($sql)
    {
        return $this->getAll($sql);
    }

    public function countComment($sql)
    {
        return $this->getRows($sql);
    }

    public function getCommentBySeasonId($season_id)
    {
        return $this->getOne("SELECT * FROM comments WHERE season_id = $season_id");
    }

    public function deleteComment($condition)
    {
        return $this->delete("comments", $condition);
    }

    public function getOneComment($condition)
    {
        return $this->getOne("SELECT * FROM comments WHERE $condition");
    }

    // Lấy tổng số bình luận
    public function getTotalComments()
    {
        return $this->getRows("SELECT id FROM comments");
    }

    // ------------------------------------- CLIENT ------------------------------------------------------
    // Lấy danh sách bình luận của phim (kèm thông tin user)
    public function getCommentsByMovie($movieId, $userId = 0, $episodeId = null)
    {

        $sql = "SELECT c.*, 
                       u.fullname as fullname, 
                       u.avartar as avartar,
                       e.name as episode_name, 
                       (SELECT COUNT(*) FROM comment_likes cl WHERE cl.comment_id = c.id) as like_count,
                       (SELECT COUNT(*) FROM comment_likes cl WHERE cl.comment_id = c.id AND cl.user_id = $userId) as is_liked
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                LEFT JOIN episodes e ON c.episode_id = e.id 
                WHERE c.movie_id = :movie_id AND c.status = 1";

        $params = ['movie_id' => $movieId];

        if ($episodeId !== null && $episodeId > 0) {
            $sql .= " AND c.episode_id = :episode_id";
            $params['episode_id'] = $episodeId;
        }

        $sql .= " ORDER BY c.created_at DESC";

        return $this->query($sql, $params);
    }

    public function countCommentsByMovie($movieId, $episodeId = null)
    {
        $sql = "SELECT COUNT(*) as total FROM comments WHERE movie_id = $movieId AND status = 1";

        if ($episodeId !== null && $episodeId > 0) {
            $sql .= " AND episode_id = $episodeId";
        }

        return $this->countRows($sql);
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
        //Tìm tất cả con trực tiếp
        $sqlChildren = "SELECT id FROM comments WHERE parent_id = $id";
        $children = $this->query($sqlChildren);

        if (!empty($children)) {
            foreach ($children as $child) {
                $this->deleteRecursive($child['id']);
            }
        }

        // 3. Xóa chính nó
        $this->delete('comments', "id = $id");
    }

    //Lấy thông tin của người cmt
    public function getCmtOwner($id)
    {
        $sql = "SELECT c.*,u.id as user_id, u.fullname as fullname, u.avartar as avartar 
        FROM comments c 
        JOIN users u ON c.user_id = u.id 
        WHERE c.id = :id";
        $params = [
            'id' => $id,
        ];
        return $this->getOne($sql, $params);
    }
}
