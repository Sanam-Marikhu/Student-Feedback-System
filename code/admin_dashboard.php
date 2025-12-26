<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h1>Welcome, <?php echo htmlspecialchars($admin_name); ?> 🛡️</h1>
            <nav>
                <!-- Student Management -->
                <a href="manage_students.php">👨‍🎓 Manage Students</a>

                <!-- Teacher Management -->
                <a href="manage_teachers.php">👩‍🏫 Manage Teachers</a>

                <!-- Course Management -->
                <a href="manage_courses.php">📚 Manage Courses</a>

                <!-- View Feedback -->
                <a href="view_all_feedback.php">📋 View Feedback</a>

                <!-- Logout -->
                <a href="logout.php" class="logout-btn">🚪 Logout</a>
            </nav>
        </header>

        <main>
            <section class="info">
                <h2>Dashboard Overview</h2>
                <p>Use the links above to add/remove students, teachers, courses, and view feedback.</p>
            </section>
        </main>
    </div>
</body>
</html>
