<?php
class CoreModel
{
    private $connect;
    public function __construct()
    {
        $this->connect = Database::connectPDO();
    }

    public function getAll($sql)
    {
        $stm = $this->connect->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getRows($sql)
    {
        $stm = $this->connect->prepare($sql);
        $stm->execute();
        $result = $stm->rowCount();
        return $result;
    }

    public function countRows($sql)
    {
        // 1. Chuẩn bị câu lệnh
        $stm = $this->connect->prepare($sql);

        // 2. Thực thi
        $stm->execute();

        // 3. Lấy dữ liệu (SỬA ĐOẠN NÀY)
        // PDO::FETCH_ASSOC giúp lấy về mảng kết hợp (key là tên cột), bỏ qua index số cho nhẹ
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

        // 4. Trả về mảng dữ liệu
        return $result;
    }

    public function getOne($sql)
    {
        $stm = $this->connect->prepare($sql);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function insert($table, $data)
    {
        //INSERT INTO users (username,email) VALUES (:username,:email)
        $keys = array_keys($data);
        //Hàm giúp nối các keys lại cách nhau bởi dấu ,
        $column = implode(',', $keys);
        $placeholder = ':' . implode(',:', $keys);
        $sql = "INSERT INTO $table ({$column}) VALUES ({$placeholder})";
        $stm = $this->connect->prepare($sql);
        $result = $stm->execute($data);
        return $result;
    }

    public function update($table, $data, $condition = '')
    {
        //UPDATE users SET username=:username,status=:status WHERE id = 1;
        $update = '';
        foreach ($data as $key => $value) {
            $update .= $key . '=:' . $key . ',';
        }
        //Xoa ki tu , o cuoi
        $update = trim($update, ',');

        if (!empty($condition)) {
            $sql = "UPDATE $table SET $update WHERE $condition";
        } else {
            $sql = "UPDATE $table SET $update";
        }
        $stm = $this->connect->prepare($sql);
        $result = $stm->execute($data);
        return $result;
    }

    public function delete($table, $condition = '')
    {

        if (!empty($condition)) {
            $sql = "DELETE FROM $table WHERE $condition";
        } else {
            $sql = "DELETE FROM $table";
        }
        $stm = $this->connect->prepare(($sql));

        $result = $stm->execute();
        return $result;
    }

    public function getLastID()
    {
        return $this->connect->lastInsertId();
    }
}
