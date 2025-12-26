<?php
session_start();

// Ensure student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
include_once 'db_connect.php';
// $conn = new mysqli("localhost", "root", "", "student_feedback_system", 3307);
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// Fetch student feedback
$student_id = $_SESSION['student_id'];
$stmt = $conn->prepare("
    SELECT f.feedback_id, c.course_name, f.rating, f.feedback_text, f.created_at
    FROM Feedback f
    JOIN Courses c ON f.course_id = c.course_id
    WHERE f.student_id = ?
    ORDER BY f.created_at DESC
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <h2>Your Submitted Feedback</h2>
    <a href="student_dashboard.php">Back to Dashboard</a><br><br>

    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="10" cellspacing="0" style="margin: 0 auto;">
            <tr>
                <th>Course</th>
                <th>Rating</th>
                <th>Comments</th>
                <th>Submitted On</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['rating']); ?></td>
                    <td><?php echo htmlspecialchars($row['feedback_text']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No feedback submitted yet.</p>
    <?php endif; ?>

</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
