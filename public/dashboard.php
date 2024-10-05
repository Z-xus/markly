<?php
session_start();
require '../src/db.php';

if (!isset($_SESSION['teacher_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

// Fetch courses for the teacher
$stmt = $pdo->prepare('SELECT * FROM courses WHERE teacher_id = ?');
$stmt->execute([$_SESSION['teacher_id']]);
$courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to Markly</h1>
    <h2>Your Courses</h2>
    <ul>
        <?php foreach ($courses as $course): ?>
            <li>
                <?php echo htmlspecialchars($course['course_name']); ?>
                <a href="attendance.php?course_id=<?php echo $course['id']; ?>">Take Attendance</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <h2>Create Course</h2>
    <form action="create_course.php" method="POST">
        <label for="course_name">Course Name:</label>
        <input type="text" name="course_name" required>
        <br>
        <label for="class">Class:</label>
        <input type="text" name="class" required>
        <br>
        <button type="submit">Create Course</button>
    </form>
    <a href="logout.php">Logout</a>
</body>
</html>
