<?php
// public/index.php
require_once __DIR__ . '/../app/controllers/LoginController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/CourseController.php';

$requestUri = $_SERVER['REQUEST_URI'];

if ($requestUri === '/login') {
    $controller = new LoginController();
    $controller->login();
} elseif ($requestUri === '/dashboard') {
    $controller = new DashboardController();
    $controller->showDashboard();
} elseif ($requestUri === '/submitAttendance' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CourseController();
    $controller->submitAttendance();
} elseif (preg_match('#^/course/([^/]+)$#', $requestUri, $matches)) {
    $controller = new CourseController();
    $controller->viewCourse($matches[1]);
} elseif (preg_match('#^/course/([^/]+)/attendance$#', $requestUri, $matches)) {
    $controller = new CourseController();
    $controller->startAttendance($matches[1]);
} elseif (preg_match('#^/course/([^/]+)/update-timeout$#', $requestUri, $matches)) {
    $controller = new CourseController();
    $controller->updateTimeout($matches[1]);
} elseif ($requestUri === '/logout') {
    $controller = new LoginController();
    $controller->logout();
} else {
    header("HTTP/1.0 404 Not Found");
    echo "Page not found";
}
