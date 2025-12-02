<?php
class User extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllUser($sql = '')
    {
        if (!empty($sql)) {
            return $this->getAll($sql);
        } else {
            return $this->getAll("SELECT * FROM users");
        }
    }

    public function getAllGroup()
    {
        return $this->getAll("SELECT * FROM groups");
    }

    public function getAllUserStatus()
    {
        return $this->getAll("SELECT * FROM user_status");
    }

    public function getOneUser($condition)
    {
        return $this->getOne("SELECT * FROM users WHERE $condition");
    }

    public function countAllUser($sql)
    {
        return $this->getRows($sql);
    }

    public function insertUser($data)
    {
        return $this->insert("users", $data);
    }

    public function updateUser($data, $condition)
    {
        return $this->update("users", $data, $condition);
    }

    public function deleteUser($condition)
    {
        return $this->delete("users", $condition);
    }
}
