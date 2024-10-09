<?php
// app/models/CourseModel.php
class CourseModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get a specific course by its ID
    public function getCourseById($course_id)
    {
        $query = "SELECT * FROM courses WHERE course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":course_id", $course_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all courses assigned to a specific teacher by their teacher ID
    public function getCoursesByTeacher($teacher_id)
    {
        $query = "SELECT * FROM courses WHERE teacher_id = :teacher_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":teacher_id", $teacher_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createNewCourse($courseName, $courseId, $className, $teacherId)
    {
        $sql = "INSERT INTO courses (name, course_id, classname, teacher_id) VALUES (:name, :course_id, :classname, :teacher_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $courseName);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->bindParam(':classname', $className);
        $stmt->bindParam(':teacher_id', $teacherId);
        // FIXME: The heck.
        $stmt->execute();
    }

    public function updateAttendanceTimeout($course_id, $timeout)
    {
        $query = "UPDATE courses SET attendance_time_out = ? WHERE course_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $timeout, $course_id);
        return $stmt->execute();
    }

    public function getClassNames()
    {
        $stmt = $this->conn->prepare("SELECT classname FROM class");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
