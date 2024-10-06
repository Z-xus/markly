<?php
// app/models/TeacherModel.php
class TeacherModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTeacherById($teacher_id) {
        $query = "SELECT * FROM teachers WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $teacher_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
