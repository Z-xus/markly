<?php
// app/controllers/AttendanceController.php
/* require_once 'app/models/AttendanceModel.php'; */
/* require_once 'app/models/CourseModel.php'; */
require_once __DIR__ . '/../models/CourseModel.php';
/* require_once __DIR__ . '/../models/StudentModel.php'; */
require_once __DIR__ . '/../models/AttendanceModel.php';
/* require_once __DIR__ . '/../../helpers/SessionHelper.php'; */


use Shuchkin\SimpleXLSX;
use Shuchkin\SimpleXLSXGen;

class AttendanceController
{
    private $db;
    private $attendanceModel;
    private $courseModel;

    public function __construct($db)
    {
        $database = new Database();
        $this->db = $database->getConnection();

        $this->attendanceModel = new AttendanceModel($db);
        $this->courseModel = new CourseModel($db);
    }

    public function exportToExcel($courseId, $classname)
    {
        if (!isset($courseId, $classname)) {
            echo "Invalid request.";
            return;
        }
        // Get the attendance data for the course and class.
        $data = $this->attendanceModel->getAttendanceByCourseForClass($courseId, $classname);
        if (empty($data)) {
            echo "No attendance data found for the specified course and class.";
            return;
        }

        // Generate Excel sheet
        SimpleXLSXGen::fromArray($data)->downloadAs("{$classname}_Attendance.xlsx");
    }

    // Ignore this function
    public function submitAttendance()
    {
        // Get course ID and classname from POST request
        if (!isset($_POST['course_id'], $_POST['classname'])) {
            echo "Invalid request.";
            return;
        }

        $courseId = $_POST['course_id'];
        $classname = $_POST['classname'];

        // Fetch attendance data for the course
        $attendanceData = $this->attendanceModel->getAttendanceByCourse($courseId);

        // Check if attendance data exists
        if (empty($attendanceData)) {
            echo "No attendance records found for this course.";
            return;
        }

        // Generate Excel sheet
        $xlsx = new SimpleXLSXGen();

        // Create a header row
        $header = ['Student UID', 'Student Name', 'Attendance Date', 'Status'];
        $data = [$header];

        // Fill data into the sheet
        foreach ($attendanceData as $attendance) {
            $data[] = [
                $attendance['student_uid'],
                $attendance['student_name'],
                $attendance['attendance_date'],
                $attendance['status'],
            ];
        }

        // Create the Excel file
        $filename = "{$classname}_Attendance.xlsx";
        $xlsx->fromArray($data)->saveAs($filename);

        // Provide a download link
        echo "<h3>Attendance sheet generated successfully!</h3>";
        echo "<a href='/{$filename}' download>Download Attendance Sheet</a>";
        echo "<br><a href='/dashboard'>Back to Dashboard</a>";
    }
}
