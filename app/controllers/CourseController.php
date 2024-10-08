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
        $course = $this->courseModel->getCourseById($course_id);
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

    public function createCourse()
    {
        /*include 'views/create_course.php';*/
        $classNames = $this->courseModel->getClassNames();
        include __DIR__ . '/../views/create_course.php';
    }

    public function storeCourse()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseName = $_POST['course_name'];
            $courseId = $_POST['course_id'];
            $className = $_POST['classname'];
            $teacherId = SessionHelper::get('user_id');

            $this->courseModel->createNewCourse($courseName, $courseId, $className, $teacherId);

            // Redirect back to dashboard after creating
            header("Location: /dashboard");
            exit();
        }
    }
    // Handling attendance submission
    public function submitAttendance()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $attendanceData = $_POST['attendance']; // Assume attendance data is passed as array
            $courseId = $_POST['course_id'];


            foreach ($attendanceData as $studentId => $status) {
                $this->attendanceModel->markAttendance($courseId, $studentId, $status);
            }

            echo "Attendance submitted successfully!";
            /*header("Location: /dashboard");*/
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "Invalid Request Method";
        }
    }
}
