<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <script>
        let students = <?php echo json_encode($students); ?>;
        let timeout = <?php echo $timeout; ?>; // in seconds
        let currentIndex = 0;
        let timer;

        function showNextStudent() {
            if (currentIndex >= students.length) {
                document.getElementById("status").innerHTML = "Attendance Complete";
                document.getElementById("attendanceForm").submit(); // Submit form when attendance is done
                return;
            }

            let student = students[currentIndex];
            document.getElementById("student_info").innerHTML = `${student.name} (${student.uid})`;

            // Reset timer to mark absent if no action is taken
            clearTimeout(timer);
            timer = setTimeout(() => {
                markAttendance('a', student.uid); // Mark as absent automatically
            }, timeout * 1000); // Convert timeout to milliseconds
        }

        function markAttendance(status, uid) {
            // Append attendance information to hidden form field
            let attendanceField = document.getElementById("attendance_data");
            attendanceField.value += `${uid}:${status},`;

            clearTimeout(timer); // Clear the timeout once an action is taken
            currentIndex++;
            showNextStudent(); // Move to the next student
        }

        window.onload = showNextStudent;
    </script>
</head>

<body>
    <h2>Mark Attendance</h2>
    <div id="student_info">Loading...</div>
    <div id="status"></div>

    <form id="attendanceForm" action="/submitAttendance" method="POST">
        <!-- Hidden input to store attendance data -->
        <input type="hidden" id="attendance_data" name="attendance_data" value="">
        <button type="button" onclick="markAttendance('p', students[currentIndex].uid)">Present</button>
        <button type="button" onclick="markAttendance('a', students[currentIndex].uid)">Absent</button>
    </form>
</body>

</html>
