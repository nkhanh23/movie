<?php
class Country extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllCountryWithCount($chuoiWhere, $offset, $perPage)
    {
        $sql = "SELECT c.*, COUNT(m.id) as count_movies 
                FROM countries c 
                LEFT JOIN movies m ON c.id = m.country_id
                $chuoiWhere
                GROUP BY c.id
                LIMIT $offset , $perPage";
        return $this->getAll($sql);
    }

    public function countCountry($sql)
    {
        $sql = "SELECT COUNT(*) as count FROM countries";
        return $this->countRows($sql);
    }

    public function countCheckCountry($name)
    {
        $sql = "SELECT * FROM countries WHERE name = '$name'";
        return $this->getRows($sql);
    }

    public function getOneCountry($id)
    {
        $sql = "SELECT * FROM countries WHERE id = $id";
        return $this->getOne($sql);
    }

    public function insertCountry($data)
    {
        return $this->insert('countries', $data);
    }

    public function getLastIdCountry()
    {
        return $this->getLastId();
    }

    public function updateCountry($data, $condition)
    {
        return $this->update('countries', $data, $condition);
    }

    public function deleteCountry($condition)
    {
        return $this->delete('countries', $condition);
    }
}
