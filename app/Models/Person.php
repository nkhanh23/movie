<?php
class Person extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    // --------------------------------- ADMIN ----------------------
    public function getAllPersonWithCount($sql)
    {
        return $this->countRows($sql);
    }

    public function countAllPersons()
    {
        return $this->getRows("SELECT * FROM persons");
    }

    public function getAllPersonRole()
    {
        return $this->getAll("SELECT * FROM person_roles");
    }

    public function getAllMoviePerson($condition)
    {
        return $this->getAll("SELECT * FROM movie_person WHERE $condition");
    }

    public function countPerson($sql)
    {
        return $this->getRows($sql);
    }

    public function getLastIdPerson()
    {
        return $this->getLastID();
    }

    public function getOnePerson($sql)
    {
        return $this->getOne($sql);
    }

    public function insertPerson($data)
    {
        return $this->insert('persons', $data);
    }

    public function insertMoviePerson($data)
    {
        return $this->insert('movie_person', $data);
    }

    public function updatePerson($data, $condition)
    {
        return $this->update('persons', $data, $condition);
    }

    public function updateMoviePerson($data, $condition)
    {
        return $this->update('movie_person', $data, $condition);
    }

    public function deleteMoviePerson($condition)
    {
        return $this->delete('movie_person', $condition);
    }

    public function deletePerson($condition)
    {
        return $this->delete('persons', $condition);
    }

    // ------------------------ CLIENT -----------------------------
    // Lấy danh sách nhân sự của một bộ phim (kèm tên diễn viên và tên vai trò)
    public function getCastByMovieId($movieId)
    {
        $sql = "SELECT mp.*, p.*, p.name as person_name, r.name as role_name
                FROM movie_person mp 
                JOIN persons p ON mp.person_id = p.id 
                JOIN person_roles r ON mp.role_id = r.id 
                WHERE mp.movie_id = $movieId";
        return $this->getAll($sql);
    }

    // Hàm lấy tất cả diễn viên (để đổ vào dropdown chọn)
    public function getAllPersonsSimple()
    {
        return $this->getAll("SELECT id, name 
        FROM persons 
        ORDER BY name ASC");
    }
}