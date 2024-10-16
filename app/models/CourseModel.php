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
    public function getCourse($course_id)
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
        $query = "SELECT * FROM courses WHERE teacher_id = :teacher_id AND archived = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":teacher_id", $teacher_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all archived courses assigned to a specific teacher by their teacher ID
    public function getArchivedCoursesByTeacher($teacher_id)
    {
        $query = "SELECT * FROM courses WHERE teacher_id = :teacher_id AND archived = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":teacher_id", $teacher_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a new course
    //
    public function createNewCourse($courseName, $className, $teacherId, $academic_year)
    {
        $courseId = $courseName . "-" . $academic_year . "-" . $className;
        $courseId = str_replace("-", "_", $courseId);
        $sql = "INSERT INTO courses (name, course_id, classname, teacher_id, academic_year) VALUES (:name, :course_id, :classname, :teacher_id, :academic_year)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $courseName);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->bindParam(':classname', $className);
        $stmt->bindParam(':teacher_id', $teacherId);
        $stmt->bindParam(':academic_year', $academic_year);

        // Execute the insert
        if ($stmt->execute()) {
            return $this->createAttendanceTable($courseId);
        }
        return false; // Return false on failure
    }

    private function createAttendanceTable($courseId)
    {
        try {
            $sql = "CALL create_course_attendance_table(:course_id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':course_id', $courseId);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function archiveCourse($course_id)
    {
        $query = "UPDATE courses SET archived = 1 WHERE course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":course_id", $course_id);
        $stmt->execute();
    }

    public function getClassNames()
    {
        $stmt = $this->conn->prepare("SELECT classname FROM class");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
