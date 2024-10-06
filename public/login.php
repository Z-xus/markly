<?php
session_start();
require_once __DIR__ . '/../config/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../src/db.php'; // Ensure db.php connects properly to the database
$error = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['login_id'];
    $password = $_POST['password'];

    // Query the database to validate the teacher's login credentials
    $query = "SELECT * FROM teachers WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $teacher = $stmt->fetch();

    if ($teacher && $teacher['password'] === $password) {
        $_SESSION['teacher_id'] = $username;
        header('Location: create_course.php');
        exit;
    } else {
        $error = "Invalid login ID or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container" style="margin: 3rem">
        <h2 style="text-align: center">Teacher Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="index.php">
            <label for="login_id">Login ID</label>
            <input type="text" name="login_id" id="login_id" required>
            
            <br>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
