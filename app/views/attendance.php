<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <link rel="stylesheet" href="/css/styles.css">
    <script>
        let students = <?php echo json_encode($students); ?>;
        let timeout = <?php echo $timeout; ?>; // in seconds
        let currentIndex = 0;
        let timer;
        let countdown; // For countdown interval

        function showNextStudent() {
            if (currentIndex >= students.length) {
                document.getElementById("status").innerHTML = "Attendance Complete";
                document.getElementById("attendanceForm").submit(); // Submit form when attendance is done
                return;
            }

            let student = students[currentIndex];
            document.getElementById("student_info").innerHTML = `${student.f_name} ${student.l_name} (${student.uid})`;
            startCountdown(); // Start the countdown for this student

            // Reset timer to mark absent if no action is taken
            clearTimeout(timer);
            timer = setTimeout(() => {
                markAttendance('a', student.uid); // Mark as absent automatically
            }, timeout * 1000); // Convert timeout to milliseconds
        }

        function startCountdown() {
            let remainingTime = timeout;
            document.getElementById("timer").innerHTML = remainingTime; // Set initial timer display

            // Clear any previous interval
            clearInterval(countdown);
            countdown = setInterval(() => {
                remainingTime--;
                document.getElementById("timer").innerHTML = remainingTime;
                if (remainingTime <= 0) {
                    clearInterval(countdown); // Stop countdown when time is up
                }
            }, 1000); // Update every second
        }

        function markAttendance(status, uid) {
            // Append attendance information to hidden form field
            let attendanceField = document.getElementById("attendance_data");
            attendanceField.value += `${uid}:${status},`;

            clearTimeout(timer); // Clear the timeout once an action is taken
            clearInterval(countdown); // Stop countdown when action is taken
            currentIndex++;
            showNextStudent(); // Move to the next student
        }

        window.onload = () => {
            showNextStudent();
            document.addEventListener('keydown', (event) => {
                if (event.key === 'p') {
                    document.querySelector('button[onclick*="markAttendance(\'p\'"]').click();
                } else if (event.key === 'a') {
                    document.querySelector('button[onclick*="markAttendance(\'a\'"]').click();
                }
            });
        };
    </script>
</head>

<body>
    <h2>Mark Attendance</h2>
    <div id="student_info">Loading...</div>
    <div>Time Remaining: <span id="timer">0</span> seconds</div>
    <div id="status"></div>

    <form id="attendanceForm" action="/submitAttendance" method="POST">
        <input type="hidden" id="attendance_data" name="attendance_data" value="">
        <input type="hidden" name="course_id" value="<?php echo $_COOKIE['course_id'] ?>">
        <button type="button" onclick="markAttendance('p', students[currentIndex].uid)">Present</button>
        <button type="button" onclick="markAttendance('a', students[currentIndex].uid)">Absent</button>
    </form>
</body>

</html>
