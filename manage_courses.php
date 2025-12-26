<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

include_once 'db_connect.php';
// $conn = new mysqli("localhost", "root", "", "student_feedback_system", 3307);
// if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// =========================
// DELETE COURSE
// =========================
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM Courses WHERE course_id = $delete_id");
    header("Location: manage_courses.php?msg=deleted");
    exit();
}

// Fetch all courses with teacher name
$result = $conn->query("
    SELECT c.course_id, c.course_name, t.name AS teacher_name
    FROM Courses c
    LEFT JOIN Teachers t ON c.teacher_id = t.teacher_id
    ORDER BY c.course_id ASC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Courses</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h2>Manage Courses</h2>
<a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
    <p class="success">🗑️ Course Deleted Successfully!</p>
<?php endif; ?>

<!-- Add Button -->
<a href="add_course.php" class="add-btn">➕ Add New Course</a>
<br><br>

<!-- Course Table -->
<?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Course Name</th>
            <th>Teacher</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['course_id']; ?></td>
            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
            <td><?php echo htmlspecialchars($row['teacher_name'] ?? 'Unassigned'); ?></td>
            <td>
                <a href="manage_courses.php?delete_id=<?php echo $row['course_id']; ?>" 
                   onclick="return confirm('Are you sure you want to delete this course?');"
                   class="delete-btn">
                   Delete
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No courses found.</p>
<?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
