<?php
// app/controllers/DashboardController.php
require_once __DIR__ . '/../models/TeacherModel.php';
require_once __DIR__ . '/../models/CourseModel.php';
require_once __DIR__ . '/../../helpers/SessionHelper.php';

class OfflineDashboardController
{
    private $teacherModel;
    private $courseModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->getConnection();
        $this->teacherModel = new TeacherModel($db);
        $this->courseModel = new CourseModel($db);
    }

    public function showDashboard()
    {
        SessionHelper::startSession();
        $teacherId = SessionHelper::get('user_id');

        // Fetch teacher and their courses
        $teacher = $this->teacherModel->getTeacherById($teacherId);
        $courses = $this->courseModel->getCoursesByTeacher($teacherId);

        if (!$teacher) {
            // Handle error, teacher not found
            header("Location: /login");
            exit();
        }

        // Extract teacher's name and pass data to the view
        $teacherName = $teacher['name'];

        // Include the dashboard view
        include __DIR__ . '/../views/offline_dashboard.php';
    }
}
