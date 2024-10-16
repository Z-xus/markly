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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['attendance_date'];
            $time = $_POST['attendance_time'];
            $timeout = (int)$_POST['timeout'];
            $course = $this->courseModel->getCourseById($course_id);
            $students = $this->studentModel->getStudentsByClass($course['classname']);
            include __DIR__ . '/../views/attendance.php';
        }
    }

    /*
    public function updateTimeout($course_id)
    {
        SessionHelper::startSession();
        if (SessionHelper::get('user_id') === null) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['timeout'])) {
            $timeout = max(5, intval($_POST['timeout']));
            $success = $this->courseModel->updateAttendanceTimeout($course_id, $timeout);

            header('Content-Type: application/json');
            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to update timeout']);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Invalid request']);
        }
    }
    */

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
            $attendanceData = $_POST['attendance_data']; // Assume attendance data is passed as array
            $courseId = $_POST['course_id'];

            if (!empty($attendanceData)) {
                try {
                    $attendanceEntries = explode(',', rtrim($attendanceData, ','));
                    foreach ($attendanceEntries as $entry) {
                        list($studentId, $status) = explode(':', $entry); // Split each entry into student ID and status
                        $this->attendanceModel->markAttendance($courseId, $studentId, $status);
                    }
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                    return;
                }
                echo "Attendance submitted successfully!";
                echo "<br><a href='/exportToExcel'>Export to Excel</a>";
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
