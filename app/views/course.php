<!-- app/views/course.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Attendance Setup</title>
</head>
<body>
    <h2>Attendance Setup for <?php echo $course['name']; ?></h2>

    <form method="POST" action="/course/<?php echo $course['course_id']; ?>/attendance">
        <label for="attendance_date">Date:</label>
        <input type="date" name="attendance_date" required><br>

        <label for="attendance_time">Time:</label>
        <input type="time" name="attendance_time" required><br>

        <label for="timeout">Timeout (seconds):</label>
        <input type="number" name="timeout" min="5" required><br>

        <button type="submit">Start Attendance</button>
    </form>
</body>
</html>
