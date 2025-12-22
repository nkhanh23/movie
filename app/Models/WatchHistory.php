<?php

class WatchHistory extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //Cap nhat tien trinh xem phim
    public function saveProgress($userId, $movieId, $episodeId, $currentTime)
    {
        $sql = "INSERT INTO watch_history (`user_id`, `movie_id`, `episode_id`, `current_time`) 
                VALUES (:user_id, :movie_id, :episode_id, :current_time) 
                ON DUPLICATE KEY UPDATE `current_time` = VALUES(`current_time`), `updated_at` = NOW()";


        $data = [
            'user_id' => $userId,
            'movie_id' => $movieId,
            'episode_id' => $episodeId,
            'current_time' => $currentTime,
        ];
        return $this->execute($sql, $data);
    }

    // Lấy tiến trình xem (để tua khi load trang)
    public function getProgress($userId, $movieId, $episodeId)
    {
        $sql = "SELECT `current_time` FROM watch_history WHERE user_id = :user_id AND movie_id = :movie_id AND episode_id = :episode_id";
        $data = [
            'user_id' => $userId,
            'movie_id' => $movieId,
            'episode_id' => $episodeId
        ];
        $result = $this->getOne($sql, $data);
        return (float)$result['current_time'] ?? 0;
    }
}
