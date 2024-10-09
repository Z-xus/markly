<!-- app/views/submitAttendance.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Attendance</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>

<body>
    <h2>Submit Attendance for Course: <?php echo htmlspecialchars($course['name']); ?></h2>

    <form method="POST" action="/generateAttendanceSheet">
        <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['course_id']); ?>">
        <input type="hidden" name="classname" value="<?php echo htmlspecialchars($course['classname']); ?>">
        <input type="submit" value="Generate Attendance Sheet">
    </form>

    <p><a href="/dashboard">Back to Dashboard</a></p>
</body>

</html>
