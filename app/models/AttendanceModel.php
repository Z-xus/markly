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
        $query = "SELECT student_uid, attendance_date, attendance_time, status
                  FROM {$courseId} WHERE classname = :classname";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':classname', $classname);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /* public function getAttendanceByCourseForClass($courseId, $classname) */
    /* { */
    /*     $query = "SELECT student_id, student_name, attendance_date, attendance_time, status */
    /*               FROM attendance */
    /*               WHERE course_id = :course_id AND classname = :classname"; */
    /*     $stmt = $this->conn->prepare($query); */
    /*     $stmt->bindParam(":course_id", $courseId); */
    /*     $stmt->bindParam(":classname", $classname); */
    /*     $stmt->execute(); */
    /*     return $stmt->fetchAll(PDO::FETCH_ASSOC); */
    /* } */

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
        // Check for required parameters
        if (empty($studentId) || empty($courseId) || !isset($status)) {
            echo "Invalid request. Missing required parameters to update database.";
            return;
        }

        if ($status === 'p') {
            $status = 1;
        } elseif ($status === 'a') {
            $status = 0;
        } else {
            echo "Invalid status.";
            return;
        }

        // Create the attendance table for the course if it doesn't exist
        $this->createCourseAttendanceTable($courseId);

        // Prepare the attendance insert query
        $query = "INSERT INTO $courseId (student_uid, attendance_date, attendance_time, status)
              VALUES (:student_uid, CURDATE(), CURTIME(), :status)
              ON DUPLICATE KEY UPDATE status = :status"; // Update if exists

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_uid', $studentId);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT); // Bind as integer

        // Execute the statement
        if ($stmt->execute()) {
            echo "Attendance marked successfully for student ID: $studentId, Status: $status, Course ID: $courseId<br>";
        } else {
            echo "Error marking attendance: " . implode(", ", $stmt->errorInfo());
        }
    }

    private function createCourseAttendanceTable($courseId)
    {
        $query = "CALL create_course_attendance_table(:course_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->execute();
    }
}
