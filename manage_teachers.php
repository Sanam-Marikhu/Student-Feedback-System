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
// DELETE TEACHER
// =========================
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM Teachers WHERE teacher_id = $delete_id");
    header("Location: manage_teachers.php?msg=deleted");
    exit();
}

// Fetch all teachers
$result = $conn->query("SELECT teacher_id, name, email FROM Teachers ORDER BY teacher_id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Teachers</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h2>Manage Teachers</h2>
<a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
    <p class="success">🗑️ Teacher Deleted Successfully!</p>
<?php endif; ?>

<!-- Add Button -->
<a href="add_teacher.php" class="add-btn">➕ Add New Teacher</a>
<br><br>

<!-- Teacher Table -->
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
            <td><?php echo $row['teacher_id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td>
                <a href="manage_teachers.php?delete_id=<?php echo $row['teacher_id']; ?>" 
                   onclick="return confirm('Are you sure you want to delete this teacher?');"
                   class="delete-btn">
                   Delete
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No teachers found.</p>
<?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
