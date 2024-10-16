<?php
// app/models/StudentModel.php
class StudentModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addStudentToClass($uid, $f_name, $m_name, $l_name, $classname) {
        $query = "INSERT INTO students (uid, f_name, m_name, l_name, classname) VALUES (:uid, :f_name, :m_name, :l_name, :classname)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $uid);
        $stmt->bindParam(":f_name", $f_name);
        $stmt->bindParam(":m_name", $m_name);
        $stmt->bindParam(":l_name", $l_name);
        $stmt->bindParam(":classname", $classname);
        return $stmt->execute();
    }

    public function muteStudent($uid) {
        $query = "UPDATE students SET is_muted = 1 WHERE uid = :uid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $uid);
        return $stmt->execute();
    }

    public function getStudentsByClass($classname) {
        $query = "SELECT * FROM students WHERE classname = :classname";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":classname", $classname);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
