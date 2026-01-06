<?php

class WatchHistory extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //Cap nhat tien trinh xem phim
    public function saveProgress($userId, $movieId, $episodeId, $seasonId, $currentTime)
    {
        $sql = "INSERT INTO watch_history 
                    (`user_id`, `movie_id`, `episode_id`, `season_id`, `current_time`, `updated_at`) 
                VALUES 
                    (:user_id, :movie_id, :episode_id, :season_id, :current_time, NOW()) 
                ON DUPLICATE KEY UPDATE 
                    `episode_id`   = VALUES(`episode_id`), 
                    `season_id`    = VALUES(`season_id`), 
                    `current_time` = VALUES(`current_time`), 
                    `updated_at`   = NOW()";


        $data = [
            'user_id' => $userId,
            'movie_id' => $movieId,
            'episode_id' => $episodeId,
            'season_id' => $seasonId,
            'current_time' => $currentTime,
        ];
        return $this->execute($sql, $data);
    }

    // Lấy tiến trình xem (để tua khi load trang)
    public function getProgress($userId, $movieId, $episodeId, $seasonId)
    {
        $sql = "SELECT `current_time` FROM watch_history WHERE user_id = :user_id AND movie_id = :movie_id AND episode_id = :episode_id";
        $data = [
            'user_id' => $userId,
            'movie_id' => $movieId,
            'episode_id' => $episodeId
        ];

        if ($seasonId !== null) {
            $sql .= " AND season_id = :season_id";
            $data['season_id'] = $seasonId;
        }

        $result = $this->getOne($sql, $data);
        return !empty($result) ? (float)$result['current_time'] : 0;
    }

    public function getContinueWatchingList($userId, $limit = 10)
    {
        $sql = "SELECT 
                wh.*, 
                m.tittle, m.slug as movie_slug, m.thumbnail, m.poster_url, m.original_tittle, m.release_year, m.duration as movie_duration, m.type_id,
                e.name as episode_name, e.duration as episode_duration,
                se.name as season_name,
                (SELECT COUNT(*) + 1 FROM seasons s2 WHERE s2.movie_id = m.id AND (s2.name + 0) < (se.name + 0)) as season_number,
                (SELECT COUNT(*) + 1 FROM episodes e2 WHERE e2.season_id = wh.season_id AND e2.id < wh.episode_id) as episode_number
            FROM watch_history wh
            JOIN movies m ON wh.movie_id = m.id
            LEFT JOIN seasons se ON wh.season_id = se.id   
            LEFT JOIN episodes e ON wh.episode_id = e.id
            WHERE wh.user_id = :user_id
            ORDER BY wh.updated_at DESC
            LIMIT $limit";

        $data = [
            'user_id' => $userId
        ];
        return $this->getAll($sql, $data);
    }

    public function deleteHistory($condition)
    {

        return $this->delete('watch_history', $condition);
    }
}
