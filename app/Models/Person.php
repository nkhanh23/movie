<?php
class Person extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

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
}
