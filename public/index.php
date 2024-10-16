
<?php
// public/index.php
require_once __DIR__ . '/../app/controllers/LoginController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/CourseController.php';

// Define available routes
$routes = [
    'GET' => [
        '/login' => [LoginController::class, 'login'],
        '/dashboard' => [DashboardController::class, 'showDashboard'],
        '/course/create' => [CourseController::class, 'createCoursePage'],
        '/logout' => [LoginController::class, 'logout'],
    ],
    'POST' => [
        '/submitAttendance' => [CourseController::class, 'submitAttendance'],
        '/login' => [LoginController::class, 'login'], // Handle login submission
        '/course/store' => [CourseController::class, 'storeCourse'],
    ],
    'DYNAMIC' => [
        '#^/course/([^/]+)$#' => [CourseController::class, 'viewCourse'],
        '#^/course/([^/]+)/attendance$#' => [CourseController::class, 'startAttendance'],
        '#^/course/([^/]+)/update-timeout$#' => [CourseController::class, 'updateTimeout'],
    ],
];

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Function to handle route dispatching
function dispatchRoute(array $route, $params = [])
{
    [$controllerClass, $method] = $route;
    $controller = new $controllerClass();
    $controller->$method(...$params);
}

// Static routes matching
if (isset($routes[$requestMethod][$requestUri])) {
    dispatchRoute($routes[$requestMethod][$requestUri]);
    exit;
}

// Dynamic routes matching
foreach ($routes['DYNAMIC'] as $pattern => $route) {
    if (preg_match($pattern, $requestUri, $matches)) {
        array_shift($matches); // Remove the full match
        dispatchRoute($route, $matches);
        exit;
    }
}

// Handle 404 Not Found
header("HTTP/1.0 404 Not Found");
echo "Page not found";
