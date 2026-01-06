<?php
class Person extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    // --------------------------------- ADMIN ----------------------
    public function getAllPerson($sql)
    {
        return $this->getAll($sql);
    }


    public function getPersonsByRole($roleId, $offset, $perPage)
    {
        $sql = "SELECT DISTINCT p.* FROM movie_person mp
                JOIN persons p ON mp.person_id = p.id 
                WHERE mp.role_id = ?
                ORDER BY p.id DESC
                LIMIT $perPage OFFSET $offset";

        return $this->getAll($sql, [$roleId]);
    }

    public function countPersonsByRole($roleId)
    {
        $sql = "SELECT DISTINCT p.id FROM movie_person mp
                JOIN persons p ON mp.person_id = p.id 
                WHERE mp.role_id = ?";

        return $this->getRows($sql, [$roleId]);
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
        // ON DELETE CASCADE đã được set trong DB nên không cần xóa bảng phụ
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

    // Hàm lấy thông tin chi tiết của một diễn viên
    public function getPersonDetail($id)
    {
        return $this->getOne("SELECT p.*, COUNT(mp.movie_id) as count_movies, GROUP_CONCAT( DISTINCT pr.name SEPARATOR ', ') as role_name
        FROM persons p
        LEFT JOIN movie_person mp ON p.id = mp.person_id
        LEFT JOIN person_roles pr ON pr.id = mp.role_id
        WHERE p.id = $id");
    }

    // Hàm tìm diễn viên theo slug
    public function findBySlug($slug)
    {
        return $this->getOne("SELECT p.*, COUNT(mp.movie_id) as count_movies, GROUP_CONCAT( DISTINCT pr.name SEPARATOR ', ') as role_name
        FROM persons p
        LEFT JOIN movie_person mp ON p.id = mp.person_id
        LEFT JOIN person_roles pr ON pr.id = mp.role_id
        WHERE p.slug = ?", [$slug]);
    }

    // Hàm lấy số lượng phim của một diễn viên
    public function countPersonMovies($id)
    {
        return $this->getRows("SELECT m.*
        FROM movies m
        JOIN movie_person mp ON m.id = mp.movie_id
        WHERE mp.person_id = $id");
    }

    // Hàm lấy danh sách phim dựa tên slug của một diễn viên
    public function getPersonMovies($id, $offset = 0, $perPage = 10)
    {
        $sql = "SELECT m.*
                FROM movies m
                JOIN movie_person mp ON m.id = mp.movie_id
                LEFT JOIN person_roles pr ON mp.role_id = pr.id
                WHERE mp.person_id = $id
                ORDER BY m.id DESC";

        if ($perPage > 0) {
            $sql .= " LIMIT " . $offset . ", " . $perPage;
        }

        return $this->getAll($sql);
    }

    // Kiểm tra diễn viên đã được yêu thích chưa
    public function checkIsFavorite($userId, $actorId)
    {
        // Giả định bảng favorites có cột user_id và personId
        $sql = "SELECT id 
        FROM favorites_persons 
        WHERE user_id = ? AND person_id = ?";
        return $this->getOne($sql, [$userId, $actorId]);
    }

    public function toggleFavorite($userId, $actorId)
    {
        $checkFavorite = $this->checkIsFavorite($userId, $actorId);
        // Nếu đã yêu thích
        if (!empty($checkFavorite)) {
            $condition = 'id=' . $checkFavorite['id'];
            $this->delete('favorites_persons', $condition);
            return 'removed';
        } else {
            // Nếu chưa yêu thích
            $data = [
                'user_id' => $userId,
                'person_id' => $actorId,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $this->insert('favorites_persons', $data);
            return 'added';
        }
    }

    public function getFavoriteActors($userId)
    {
        $sql = "SELECT p.id, p.name, p.avatar
        FROM persons p
        JOIN favorites_persons fp ON p.id = fp.person_id
        WHERE fp.user_id = ?";
        return $this->getAll($sql, [$userId]);
    }
}
