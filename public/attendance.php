<?php
session_start();
require '../src/db.php';

if (!isset($_SESSION['teacher_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

$course_id = $_GET['course_id'] ?? null;

if (!$course_id) {
    header('Location: dashboard.php'); // Redirect to dashboard if no course ID
    exit;
}

// Fetch students for the selected course
$stmt = $pdo->prepare('SELECT * FROM students WHERE course_id = ?');
$stmt->execute([$course_id]);
$students = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attendance_date = $_POST['attendance_date'];
    $attendance_time = $_POST['attendance_time'];
    
    foreach ($students as $student) {
        $status = $_POST['status'][$student['uid']] ?? 'a'; // Default to absent
        $stmt = $pdo->prepare('INSERT INTO attendance (course_id, uid, attendance_date, attendance_time, status) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$course_id, $student['uid'], $attendance_date, $attendance_time, $status]);
    }
    
    header('Location: dashboard.php'); // Redirect to dashboard after marking attendance
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Attendance</title>
</head>
<body>
    <h1>Take Attendance</h1>
    <form action="" method="POST">
        <label for="attendance_date">Date:</label>
        <input type="date" name="attendance_date" required>
        <br>
        <label for="attendance_time">Time:</label>
        <input type="time" name="attendance_time" required>
        <br>
        <h2>Students:</h2>
        <ul>
            <?php foreach ($students as $student): ?>
                <li>
                    <?php echo htmlspecialchars($student['name']); ?> (UID: <?php echo htmlspecialchars($student['uid']); ?>)
                    <label>
                        Present <input type="radio" name="status[<?php echo htmlspecialchars($student['uid']); ?>]" value="p">
                    </label>
                    <label>
                        Absent <input type="radio" name="status[<?php echo htmlspecialchars($student['uid']); ?>]" value="a" checked>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>
        <button type="submit">Submit Attendance</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
