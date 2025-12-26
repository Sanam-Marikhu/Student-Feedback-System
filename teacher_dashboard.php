<?php
session_start();

// Check if teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.html");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$teacher_name = $_SESSION['teacher_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="teacher_dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h1>Welcome, <?php echo htmlspecialchars($teacher_name); ?> 👩‍🏫</h1>
            <nav>
                <a href="viewteacher_feedback.php">📋 View Feedback</a>
                <a href="logout.php" class="logout-btn">🚪 Logout</a>
            </nav>
        </header>

        <main>
            <section class="info">
                <h2>Dashboard Overview</h2>
                <p>You can view feedback given by students for your courses.</p>
            </section>
        </main>
    </div>
</body>
</html>
