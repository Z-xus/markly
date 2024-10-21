<!-- app/views/course.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Attendance Setup - <?php echo htmlspecialchars($course['name']); ?></title>
    <link rel="stylesheet" href="/css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <?php include __DIR__ . '/../views/navbar.php'; ?>

    <h2>Attendance Setup for <?php echo htmlspecialchars($course['name']); ?></h2>
    <form method="POST" action="/course/<?php echo htmlspecialchars($course['course_id']); ?>/attendance">
        <label for="attendance_date">Date:</label>
        <input type="date" id="attendance_date" name="attendance_date" value="<?php echo date('Y-m-d'); ?>" required><br>

        <label for="attendance_time">Time:</label>
        <select id="attendance_time" name="attendance_time" required>
            <?php
            $current_time = new DateTime();
            $start_time = new DateTime('08:00');
            $end_time = new DateTime('18:00');
            $interval = new DateInterval('PT30M');
            $current_rounded = new DateTime(date('H:i'));
            $current_rounded->setTime($current_rounded->format('H'), floor($current_rounded->format('i') / 30) * 30);

            // FIXME: Select the current time as default

            while ($start_time <= $end_time) {
                $time_option = $start_time->format('H:i');
                $selected = ($start_time == $current_rounded) ? 'selected' : '';
                echo "<option value=\"{$time_option}\" {$selected}>{$time_option}</option>";
                $start_time->add($interval);
            }
            ?>
        </select><br>

        <label for="timeout">Timeout to mark absent (seconds):</label>
        <input type="number" id="timeout" value="3" name="timeout" min="3" required><br>

        <button type="submit">Start Attendance</button>
    </form>

    <script>
        $(document).ready(function() {
            $('#timeout').change(function() {
                $.ajax({
                    url: '/course/<?php echo htmlspecialchars($course['course_id']); ?>/update-timeout',
                    method: 'POST',
                    data: {
                        timeout: $(this).val()
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Timeout updated successfully');
                        } else {
                            console.error('Error updating timeout:', response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating timeout:', error);
                    }
                });
            });
        });
    </script>
</body>

</html>
