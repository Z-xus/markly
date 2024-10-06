<?php
// app/controllers/CourseController.php
require_once __DIR__ . '/../models/CourseModel.php';
require_once __DIR__ . '/../models/StudentModel.php';
require_once __DIR__ . '/../../helpers/SessionHelper.php';

class CourseController
{
    private $db;
    private $courseModel;
    private $studentModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->courseModel = new CourseModel($this->db);
        $this->studentModel = new StudentModel($this->db);
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
}
