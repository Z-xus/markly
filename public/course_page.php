<?php
session_start();
require_once __DIR__ . '/../config/config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['teacher'])) {
    header('Location: index.php');
    exit;
}

// Handle attendance marking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendance_date = $_POST['attendance_date'];
    $attendance_time = $_POST['attendance_time'];
    $course_id = $_POST['course_id'];
    
    // Fetch students for the selected course
    $query = "SELECT * FROM students WHERE course_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $students = $stmt->get_result();

    // Store attendance in the database
    while ($student = $students->fetch_assoc()) {
        $uid = $student['uid'];
        $status = $_POST['attendance'][$uid] ?? 'a'; // 'p' or 'a'
        
        $attendance_query = "INSERT INTO attendance (course_id, uid, attendance_date, attendance_time, status) VALUES (?, ?, ?, ?, ?)";
        $attendance_stmt = $conn->prepare($attendance_query);
        $attendance_stmt->bind_param('issss', $course_id, $uid, $attendance_date, $attendance_time, $status);
        $attendance_stmt->execute();
    }

    $success = "Attendance marked successfully!";
}

// Fetch course details and students
$course_id = $_GET['course_id'];
$query = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $course_id);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();

$query = "SELECT * FROM students WHERE course_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $course_id);
$stmt->execute();
$students = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['course_name']); ?> - Attendance</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="attendance-container">
        <h2>Mark Attendance for <?php echo htmlspecialchars($course['course_name']); ?></h2>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        
        <form method="POST" action="course_page.php?course_id=<?php echo $course_id; ?>">
            <label for="attendance_date">Date</label>
            <input type="date" name="attendance_date" id="attendance_date" value="<?php echo date('Y-m-d'); ?>" required>
            
            <label for="attendance_time">Time</label>
            <select name="attendance_time" id="attendance_time" required>
                <option value="13:00">1:00 PM</option>
                <option value="13:30">1:30 PM</option>
                <option value="14:00">2:00 PM</option>
                <option value="14:30">2:30 PM</option>
                <!-- Add more time slots as needed -->
            </select>

            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">

            <h3>Students:</h3>
            <ul>
                <?php while ($student = $students->fetch_assoc()): ?>
                    <li>
                        <label>
                            <input type="checkbox" name="attendance[<?php echo $student['uid']; ?>]" value="p"> 
                            <?php echo htmlspecialchars($student['name']) . " (UID: " . htmlspecialchars($student['uid']) . ")"; ?>
                        </label>
                    </li>
                <?php endwhile; ?>
            </ul>
            <button type="submit">Mark Attendance</button>
        </form>

        <a href="create_course.php">Back to Courses</a>
        <a href="index.php">Logout</a>
    </div>
</body>
</html>
