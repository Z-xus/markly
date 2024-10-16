<?php
// app/models/TeacherModel.php
class TeacherModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addTeacher($name, $email, $password) {
        $query = "INSERT INTO teachers (id, name, email, password) VALUES (UUID(), :name, :email, :password)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        return $stmt->execute();
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
