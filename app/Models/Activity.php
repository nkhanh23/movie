<?php

class Activity extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //insert log
    public function log($userId, $action, $entityType, $entityId = null, $oldData = null, $newData = null)
    {
        // Lấy IP và User Agent
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        $data = [
            'user_id'     => $userId,
            'action'      => $action, // create, update, delete, login
            'entity_type' => $entityType, // movies, episodes, seasons, genres, comments, persons, video_source
            'entity_id'   => $entityId, // id cua doi tuong
            // JSON_UNESCAPED_UNICODE để sử dụng được Tiếng Việt
            'old_values'  => !empty($oldData) ? json_encode($oldData, JSON_UNESCAPED_UNICODE) : null, // du lieu cu
            'new_values'  => !empty($newData) ? json_encode($newData, JSON_UNESCAPED_UNICODE) : null, // du lieu moi
            'ip_address'  => $ip,
            'user_agent'  => $userAgent,
            'created_at'  => date('Y:m:d H:i:s')
        ];

        return $this->insert('activity_logs', $data);
    }

    // Lấy danh sách log để hiển thị Admin (kèm tên User)
    public function getLatestLogs($limit = 20)
    {
        $sql = "SELECT l.*, u.fullname, u.group_id 
            FROM activity_logs l
            LEFT JOIN users u ON l.user_id = u.id
            ORDER BY l.created_at DESC 
            LIMIT $limit";
        return $this->getAll($sql);
    }

    // Lấy danh sách log của phim
    public function getLatestMoviesLogs($limit = 5)
    {
        $sql = "SELECT l.*, m.tittle as movie_name,m.created_at, 
            e.name as episode_name,
            s.name as season_name
            FROM activity_logs l
            LEFT JOIN movies m ON l.entity_id = m.id
            LEFT JOIN episodes e ON l.entity_id = e.id
            LEFT JOIN seasons s ON e.season_id = s.id
            WHERE l.entity_type = 'movies' AND l.action = 'create'
            ORDER BY l.created_at DESC 
            LIMIT $limit";
        return $this->getAll($sql);
    }

    //Lấy tất cả log
    public function getAllLogs($chuoiWhere = '', $limit = 10, $offset = 0)
    {
        $limit = (int)$limit;
        $offset = (int)$offset;
        $sql = "SELECT l.*, u.fullname, u.group_id
            FROM activity_logs l
            LEFT JOIN users u ON l.user_id = u.id
            $chuoiWhere
            ORDER BY l.created_at DESC
            LIMIT $limit OFFSET $offset";
        return $this->getAll($sql);
    }

    //pagination
    public function pagination($chuoiWhere = '')
    {
        $sqlCount = "SELECT count(*) as total
            FROM activity_logs l
            LEFT JOIN users u ON l.user_id = u.id
            $chuoiWhere";
        return $this->getAll($sqlCount);
    }

    //delete
    public function deleteLog($id, $condition)
    {
        return $this->delete('activity_logs', $condition);
    }
}
