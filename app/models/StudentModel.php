<?php
// app/models/StudentModel.php
class StudentModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
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
