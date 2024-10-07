<?php
// app/models/AttendanceModel.php
class AttendanceModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Fetch all students for a course
    public function getStudentsByCourse($courseId)
    {
        $query = "SELECT s.uid, s.name FROM students s 
                  JOIN courses c ON s.classname = c.classname 
                  WHERE c.course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":course_id", $courseId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mark attendance for a student
    public function markAttendance($courseId, $studentId, $status)
    {
        $query = "INSERT INTO attendance (attendance_id, classname, student_uid, course_id, status, attendance_date, attendance_time)
                  VALUES (UUID(), (SELECT classname FROM students WHERE uid = :student_uid), :student_uid, :course_id, :status, CURDATE(), CURTIME())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_uid', $studentId);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
    }
}
