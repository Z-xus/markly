<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo $teacherName; ?></h2>

    <span style="position: absolute; top: 2rem; right: 2rem;"><a href="/logout">Logout</a></span>

    <!-- Course list and add button will go here -->
    <h3>Ongoing Courses</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Course Name</th>
                <th>Course Code</th>
                <th>Class Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($courses)) {
                foreach ($courses as $course) {
                    echo "<tr>";
                    echo "<td>" . $course['name'] . "</td>";
                    echo "<td>" . $course['course_id'] . "</td>";
                    echo "<td>" . $course['classname'] . "</td>";
                    echo "<td><a href='/course/" . $course['course_id'] . "'>Start Attendance</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No courses found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
