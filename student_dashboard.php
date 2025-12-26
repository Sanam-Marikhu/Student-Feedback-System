<?php
session_start();

// Ensure student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
require_once 'db_connect.php';
// $conn = new mysqli("localhost", "root", "", "student_feedback_system", 3307);
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// Fetch student name
$student_id = $_SESSION['student_id'];
$stmt = $conn->prepare("SELECT name FROM Students WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="student_dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h1>Welcome, <?php echo htmlspecialchars($student_name); ?> 👨‍🎓</h1>
            <nav>
                <a href="feedback.php">📝 Give Feedback</a>
                <a href="viewstudent_feedback.php">📋 View Feedback</a>
                <a href="logout.php" class="logout-btn">🚪 Logout</a>
            </nav>
        </header>

        <main>
            <section class="info">
                <h2>Dashboard Overview</h2>
                <p>You can give feedback for your courses and view previously submitted feedback.</p>
            </section>
        </main>
    </div>
</body>
</html>
