<?php
class Role extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllRole($sql = '')
    {
        if (!empty($sql)) {
            return $this->getAll($sql);
        }
        return $this->getAll("SELECT * FROM person_roles");
    }

    public function getOneRole($condition)
    {
        return $this->getOne("SELECT * FROM person_roles WHERE $condition");
    }

    public function countRole($sql = '')
    {
        if (!empty($sql)) {
            return $this->getRows($sql);
        }
        return $this->getRows("SELECT * FROM person_roles");
    }

    public function insertRole($data)
    {
        return $this->insert('person_roles', $data);
    }

    public function updateRole($data, $condition)
    {
        return $this->update('person_roles', $data, $condition);
    }

    public function deleteRole($condition)
    {
        return $this->delete('person_roles', $condition);
    }

    public function deleteMoviePerson($condition)
    {
        return $this->delete('movie_person', $condition);
    }
}