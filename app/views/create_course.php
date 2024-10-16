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

    <?php
    // Calculate the current year and set the range for academic years
    $currentDate = new DateTime();
    $currentYear = (int)$currentDate->format('Y');
    $currentMonth = (int)$currentDate->format('m');

    // Determine the start and end years for the current academic year
    if ($currentMonth >= 8) {
        $startYear = $currentYear;
    } else {
        $startYear = $currentYear - 1;
    }
    $endYear = $startYear + 1;

    // Create a range of academic years for 10 years before and after
    $academicYears = [];
    for ($i = -10; $i <= 10; $i++) {
        $year = $currentYear + $i;
        $academicYears[] = sprintf("%02d%02d", $year % 100, ($year + 1) % 100);
    }
    ?>

    <form action="/course/store" method="POST">
        <label for="course_name">Course Name:</label>
        <input type="text" id="course_name" name="course_name" required><br><br>

        <label for="academic_year">Academic Year (20):</label>
        <select id="academic_year" name="academic_year" required>
            <option value="">Select Academic Year</option>
            <?php foreach ($academicYears as $year): ?>
                <option value="<?php echo htmlspecialchars($year); ?>"
                    <?php echo $year === sprintf("%02d%02d", $startYear % 100, ($startYear + 1) % 100) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($year); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

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
