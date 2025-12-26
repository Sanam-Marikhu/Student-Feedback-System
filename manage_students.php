<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Database connection
include_once 'db_connect.php';
// $conn = new mysqli("localhost", "root", "", "student_feedback_system", 3307);
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// =========================
// DELETE STUDENT
// =========================
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM Students WHERE student_id = $delete_id");
    header("Location: manage_students.php?msg=deleted");
    exit();
}

// Fetch all students
$result = $conn->query("SELECT student_id, name, email FROM Students ORDER BY student_id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h2>Manage Students</h2>
<a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
    <p class="success">🗑️ Student Deleted Successfully!</p>
<?php endif; ?>

<!-- Add Button -->
<a href="add_student.php" class="add-btn">➕ Add New Student</a>
<br><br>

<!-- Student Table -->
<?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['student_id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>

            <td>
                <!-- Delete Link -->
                <a href="manage_students.php?delete_id=<?php echo $row['student_id']; ?>" 
                   onclick="return confirm('Are you sure you want to delete this student?');"
                   class="delete-btn">
                   Delete
                </a>
            </td>
        </tr>
        <?php endwhile; ?>

    </table>
<?php else: ?>
    <p>No students found.</p>
<?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
