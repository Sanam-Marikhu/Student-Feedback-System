<?php
session_start();

// Ensure teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
include_once 'db_connect.php';
// $conn = new mysqli("localhost", "root", "", "student_feedback_system", 3307);
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// Get logged-in teacher’s ID
$teacher_id = $_SESSION['teacher_id'];

// Fetch feedback for teacher’s courses
$stmt = $conn->prepare("
    SELECT s.name AS student_name, 
           c.course_name, 
           f.rating, 
           f.feedback_text, 
           f.created_at
    FROM Feedback f
    JOIN Courses c ON f.course_id = c.course_id
    JOIN Students s ON f.student_id = s.student_id
    WHERE c.teacher_id = ?
    ORDER BY f.created_at DESC
");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher - View Feedback</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <h2>Feedback Received for Your Courses</h2>
    <a href="teacher_dashboard.php">← Back to Dashboard</a><br><br>

    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="10" cellspacing="0" style="margin: 0 auto;">
            <tr>
                <th>Student Name</th>
                <th>Course</th>
                <th>Rating</th>
                <th>Feedback</th>
                <th>Submitted On</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['rating']); ?></td>
                    <td><?php echo htmlspecialchars($row['feedback_text']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No feedback submitted yet for your courses.</p>
    <?php endif; ?>

</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
