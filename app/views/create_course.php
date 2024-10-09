<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">
    <title>Create Course</title>
</head>

<body>
    <h2>Create a New Course</h2>
    <form action="/course/store" method="POST">
        <label for="course_name">Course Name:</label>
        <input type="text" id="course_name" name="course_name" required><br><br>

        <label for="course_id">Course Code:</label>
        <input type="text" id="course_id" name="course_id" required><br><br>

        <label for="classname">Class Name:</label>
        <select id="classname" name="classname" required>
            <option value="">Select Class</option>
            <?php foreach ($classNames as $class): ?>
                <option value="<?php echo htmlspecialchars($class['classname']); ?>">
                    <?php echo htmlspecialchars($class['classname']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Create Course</button>
    </form>
</body>

</html>
