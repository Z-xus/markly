<?php
// app/models/CourseModel.php
class CourseModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get a specific course by its ID
    public function getCourseById($course_id) {
        $query = "SELECT * FROM courses WHERE course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":course_id", $course_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all courses assigned to a specific teacher by their teacher ID
    public function getCoursesByTeacher($teacher_id) {
        $query = "SELECT * FROM courses WHERE teacher_id = :teacher_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":teacher_id", $teacher_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
