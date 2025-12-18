<?php
class Notifications extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //Tao thong bao moi
    public function createNotification($data)
    {
        return $this->insert('notifications', $data);
    }

    // Lay thong bao
    public function getLatest($user_id, $Limit = 10)
    {
        $sql = "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT $Limit";
        $params = [
            'user_id' => $user_id,
        ];
        return $this->getAll($sql, $params);
    }
}
