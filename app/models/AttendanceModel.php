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

    public function getAttendanceByCourseForClass($courseId, $classname)
    {
        $query = "SELECT student_id, student_name, attendance_date, status
                  FROM attendance
                  WHERE course_id = :course_id AND classname = :classname";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":course_id", $courseId);
        $stmt->bindParam(":classname", $classname);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAttendanceByCourse($courseId)
    {
        $query = "SELECT a.student_uid, s.name as student_name, a.attendance_date, a.status
                  FROM attendance a
                  JOIN students s ON a.student_uid = s.uid
                  WHERE a.course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":course_id", $courseId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mark attendance for a student
    public function markAttendance($courseId, $studentId, $status)
    {
        print_r("Marking attendance for student with ID: $studentId\t Status: $status\t Course ID: $courseId<br>");
        // FIXME: STORE THE FUCKING DATA B4 CALLING IT
        if (empty($studentId) || empty($courseId) || empty($status)) {
            echo "Invalid request. Missing required parameters to update database.";
            return;
        }
        $query = "INSERT INTO attendance (attendance_id, classname, student_uid, course_id, status, attendance_date, attendance_time)
                  VALUES (UUID(), (SELECT classname FROM students WHERE uid = :student_uid), :student_uid, :course_id, :status, CURDATE(), CURTIME())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_uid', $studentId);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
    }
}
