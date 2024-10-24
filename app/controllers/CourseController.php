<?php
// app/controllers/CourseController.php
require_once __DIR__ . '/../models/CourseModel.php';
require_once __DIR__ . '/../models/StudentModel.php';
require_once __DIR__ . '/../models/AttendanceModel.php';
require_once __DIR__ . '/../../helpers/SessionHelper.php';

class CourseController
{
    private $db;
    private $courseModel;
    private $studentModel;
    private $attendanceModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->courseModel = new CourseModel($this->db);
        $this->studentModel = new StudentModel($this->db);
        $this->attendanceModel = new AttendanceModel($this->db);
    }

    public function viewCourse($course_id)
    {
        SessionHelper::startSession();
        if (SessionHelper::get('user_id') === null) {
            header("Location: /login");
            exit();
        }
        $course = $this->courseModel->getCourse($course_id);
        include __DIR__ . '/../views/course.php';
    }

    public function startAttendance($course_id)
    {
        SessionHelper::startSession();
        if (SessionHelper::get('user_id') === null) {
            header("Location: /login");
            exit();
        }

        setcookie('course_id', $course_id, time() + 3600, "/"); // 1 hour = 3600 seconds
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['attendance_date'];
            $time = $_POST['attendance_time'];
            $timeout = (int)$_POST['timeout'];
            $course = $this->courseModel->getCourse($course_id);
            $students = $this->studentModel->getStudentsByClass($course['classname']);
            include __DIR__ . '/../views/attendance.php';
        }
    }

    public function createCoursePage()
    {
        /*include 'views/create_course.php';*/
        $classNames = $this->courseModel->getClassNames();
        include __DIR__ . '/../views/create_course.php';
    }

    public function storeCourse()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseName = $_POST['course_name'];
            /* $courseId = $_POST['course_id']; */
            $className = $_POST['classname'];
            $academic_year = $_POST['academic_year'];
            $teacherId = SessionHelper::get('user_id');

            $this->courseModel->createNewCourse($courseName, $className, $teacherId, $academic_year);

            // Redirect back to dashboard after creating
            header("Location: /dashboard");
            exit();
        }
    }
    // Handling attendance submission
    public function submitAttendance()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $attendanceData = $_POST['attendance_data'];
            $courseId = $_POST['course_id'];

            if (!empty($attendanceData)) {
                try {
                    $attendanceEntries = explode(',', rtrim($attendanceData, ','));
                    if (empty($attendanceEntries)) {
                        echo "No attendance data found!";
                        throw new Exception("No attendance data found!");
                    }
                    if (empty($courseId)) {
                        echo "No course ID found!";
                        throw new Exception("No course ID found!");
                    }
                    foreach ($attendanceEntries as $entry) {
                        list($studentId, $status) = explode(':', $entry);
                        $this->attendanceModel->markAttendance($courseId, $studentId, $status);
                    }
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                    return;
                }
                echo "Attendance submitted successfully!";
                echo "<br><a href='/course/" . htmlspecialchars($courseId) . "/exportToExcel'>Export to Excel</a>";
            } else {
                echo "Attendance data not stored!";
            }

            /*header("Location: /dashboard");*/
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "Invalid Request Method";
        }
    }
}
