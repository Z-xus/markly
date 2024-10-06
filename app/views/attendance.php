<!-- app/views/attendance.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <script>
        let students = <?php echo json_encode($students); ?>;
        let timeout = <?php echo $timeout; ?>;
        let currentIndex = 0;

        function showNextStudent() {
            if (currentIndex >= students.length) {
                document.getElementById("status").innerHTML = "Attendance Complete";
                return;
            }

            let student = students[currentIndex];
            document.getElementById("student_info").innerHTML = `${student.name} (${student.uid})`;
            setTimeout(() => {
                markAbsentIfNoAction(student.uid);
            }, timeout * 1000);
        }

        function markAttendance(status) {
            let student = students[currentIndex];
            // Perform AJAX request to mark attendance (p or a)
            // Skipping AJAX logic for brevity
            currentIndex++;
            showNextStudent();
        }

        function markAbsentIfNoAction(uid) {
            // Automatically mark as absent if no action
            // Skipping AJAX logic for brevity
            currentIndex++;
            showNextStudent();
        }

        window.onload = showNextStudent;
    </script>
</head>
<body>
    <h2>Mark Attendance</h2>
    <div id="student_info">Loading...</div>
    <div id="status"></div>

    <button onclick="markAttendance('p')">Present</button>
    <button onclick="markAttendance('a')">Absent</button>
</body>
</html>
