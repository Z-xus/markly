
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
