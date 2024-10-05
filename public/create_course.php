/*<?php*/
/*session_start();*/
/*require_once __DIR__ . '/../config/config.php';*/
/**/
/*// Redirect to login if not logged in*/
/*if (!isset($_SESSION['teacher'])) {*/
/*    header('Location: index.php');*/
/*    exit;*/
/*}*/
/**/
/*// Handle course creation*/
/*if ($_SERVER['REQUEST_METHOD'] === 'POST') {*/
/*    $course_name = $_POST['course_name'];*/
/*    $class = $_POST['class'];*/
/**/
/*    // Insert the new course into the database*/
/*    $query = "INSERT INTO courses (course_name, class, teacher_id) VALUES (?, ?, ?)";*/
/*    $stmt = $conn->prepare($query);*/
/*    $teacher_id = $_SESSION['teacher']; // Assuming this is the login ID*/
/*    $stmt->bind_param('ssi', $course_name, $class, $teacher_id);*/
/**/
/*    if ($stmt->execute()) {*/
/*        $success = "Course created successfully!";*/
/*    } else {*/
/*        $error = "Error creating course. Please try again.";*/
/*    }*/
/*}*/
/**/
/*// Fetch all courses for the teacher*/
/*$query = "SELECT * FROM courses WHERE teacher_id = ?";*/
/*$stmt = $conn->prepare($query);*/
/*$stmt->bind_param('s', $_SESSION['teacher']);*/
/*$stmt->execute();*/
/*$courses = $stmt->get_result();*/
/*?>*/
/**/
/*<!DOCTYPE html>*/
/*<html lang="en">*/
/*<head>*/
/*    <meta charset="UTF-8">*/
/*    <meta name="viewport" content="width=device-width, initial-scale=1.0">*/
/*    <title>Create Course</title>*/
/*    <link rel="stylesheet" href="css/style.css">*/
/*</head>*/
/*<body>*/
/*    <div class="course-container">*/
/*        <h2>Create Course</h2>*/
/*        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>*/
/*        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>*/
/**/
/*        <form method="POST" action="create_course.php">*/
/*            <label for="course_name">Course Name</label>*/
/*            <input type="text" name="course_name" id="course_name" required>*/
/**/
/*            <label for="class">Class</label>*/
/*            <input type="text" name="class" id="class" required>*/
/**/
/*            <button type="submit">Create Course</button>*/
/*        </form>*/
/**/
/*        <h3>My Courses</h3>*/
/*        <ul>*/
/*            <?php while ($course = $courses->fetch_assoc()): ?>*/
/*                <li><?php echo htmlspecialchars($course['course_name']) . " - " . htmlspecialchars($course['class']); ?></li>*/
/*            <?php endwhile; ?>*/
/*        </ul>*/
/**/
/*        <a href="index.php">Logout</a>*/
/*    </div>*/
/*</body>*/
/*</html>*/


<?php
session_start();
require '../src/db.php';

if (!isset($_SESSION['teacher_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_name = $_POST['course_name'];
    $class = $_POST['class'];
    $teacher_id = $_SESSION['teacher_id'];

    // Insert new course into the database
    $stmt = $pdo->prepare('INSERT INTO courses (course_name, class, teacher_id) VALUES (?, ?, ?)');
    if ($stmt->execute([$course_name, $class, $teacher_id])) {
        header('Location: dashboard.php'); // Redirect back to dashboard
        exit;
    } else {
        $error = "Failed to create course.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course</title>
</head>
<body>
    <h1>Create Course</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <label for="course_name">Course Name:</label>
        <input type="text" name="course_name" required>
        <br>
        <label for="class">Class:</label>
        <input type="text" name="class" required>
        <br>
        <button type="submit">Create Course</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
