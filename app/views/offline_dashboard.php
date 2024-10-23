<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">
    <title>Dashboard</title>
</head>

<body>
    <?php include __DIR__ . '/../views/navbar.php'; ?>

    <h2>Welcome, <?php echo $teacherName; ?>
        <span style="background-color: #ffcc00;cursor: pointer; padding: 4px 8px; border-radius: 4px;">Offline Mode</span>
    </h2>

    <!--<span style="position: absolute; top: 2rem; right: 2rem;"><a href="/logout">Logout</a></span>-->

    <!-- Course list and add button will go here -->
    <button onclick="window.location.href='/course/create'">Create Course</button>
    <h3>Ongoing Courses</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Course Name</th>
                <th>Course ID</th>
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
                    echo "<td>";
                    echo "<a href='/course/" . $course['course_id'] . "'>Start Attendance</a> | ";
                    echo "<a href='/course/save/" . $course['course_id'] . "'>Save</a>";
                    echo "</td>";
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
