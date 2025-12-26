<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

include_once 'db_connect.php';

// =========================
// DELETE FEEDBACK
// =========================
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM Feedback WHERE feedback_id = $delete_id");

    // Redirect to avoid accidental re-delete on refresh
    header("Location:view_all_feedback.php?msg=deleted");
    exit();
}

// Fetch all feedback with student, teacher, and course
$result = $conn->query("
SELECT f.feedback_id, s.name AS student_name, t.name AS teacher_name, c.course_name, f.rating, f.feedback_text, f.created_at
FROM Feedback f
JOIN Students s ON f.student_id = s.student_id
JOIN Courses c ON f.course_id = c.course_id
JOIN Teachers t ON c.teacher_id = t.teacher_id
ORDER BY f.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Feedback</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h2>Manage Feedback</h2>
<a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>

<!-- Success Message -->
<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
    <p class="success">🗑️ Feedback Deleted Successfully!</p>
<?php endif; ?>

<!-- Feedback Table -->
<?php if ($result->num_rows > 0): ?>
<table>
    <tr>
        <th>ID</th>
        <th>Student</th>
        <th>Teacher</th>
        <th>Course</th>
        <th>Rating</th>
        <th>Feedback</th>
        <th>Submitted On</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['feedback_id']; ?></td>
        <td><?php echo htmlspecialchars($row['student_name']); ?></td>
        <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
        <td><?php echo htmlspecialchars($row['course_name']); ?></td>
        <td><?php echo htmlspecialchars($row['rating']); ?>/5</td>
        <td><?php echo htmlspecialchars($row['feedback_text']); ?></td>
        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
        <td>
            <a href="view_all_feedback.php?delete_id=<?php echo $row['feedback_id']; ?>" 
               onclick="return confirm('Are you sure you want to delete this feedback?');"
               class="delete-btn">
               Delete
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <p>No feedback submitted yet.</p>
<?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
