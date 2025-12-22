<?php
class Support extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllSupport($chuoiWhere = '', $limit = 10, $offset = 0)
    {
        $limit = (int)$limit;
        $offset = (int)$offset;
        $sql = "SELECT s.*,u.fullname as user_name, u.email as user_email, st.name as support_type_name, ss.status as status_name
        FROM supports s
        LEFT JOIN users u ON s.user_id = u.id
        LEFT JOIN support_types st ON s.support_type_id = st.id
        LEFT JOIN support_status ss ON s.support_status_id = ss.id
        $chuoiWhere
        ORDER BY s.created_at DESC
        LIMIT $limit OFFSET $offset
        ";
        return $this->getAll($sql);
    }

    public function countAllSupport($chuoiWhere = '')
    {
        $sql = "SELECT COUNT(*) as total FROM supports s
        LEFT JOIN users u ON s.user_id = u.id
        LEFT JOIN support_types st ON s.support_type_id = st.id
        LEFT JOIN support_status ss ON s.support_status_id = ss.id
        $chuoiWhere";
        return $this->countRows($sql);
    }

    public function getAllSupportType()
    {
        $sql = "SELECT * FROM support_types";
        return $this->getAll($sql);
    }

    public function getAllStatus()
    {
        $sql = "SELECT * FROM support_status";
        return $this->getAll($sql);
    }

    public function insertSupport($data)
    {
        return $this->insert('supports', $data);
    }

    public function insertSupportType($data)
    {
        return $this->insert('support_types', $data);
    }

    public function getSupportTypeById($id)
    {
        $sql = "SELECT * FROM support_types WHERE id = $id";
        return $this->getOne($sql);
    }

    public function getSupportById($id)
    {
        $sql = "SELECT s.*,u.fullname as user_name, u.email as user_email, st.name as support_type_name, ss.status as status_name
        FROM supports s
        LEFT JOIN users u ON s.user_id = u.id
        LEFT JOIN support_types st ON s.support_type_id = st.id
        LEFT JOIN support_status ss ON s.support_status_id = ss.id
        WHERE s.id = $id";
        return $this->getOne($sql);
    }

    public function updateSupport($data, $condition)
    {
        return $this->update('supports', $data, $condition);
    }

    public function updateSupportType($data, $condition)
    {
        return $this->update('support_types', $data, $condition);
    }

    public function deleteSupportType($id)
    {
        return $this->delete('support_types', 'id=' . $id);
    }
}
