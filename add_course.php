<?php
session_start();

// Check admin login
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Database connection
include_once 'db_connect.php';
// $conn = new mysqli("localhost", "root", "", "student_feedback_system", 3307);
// if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch teachers for dropdown
$teacher_result = $conn->query("SELECT teacher_id, name FROM Teachers ORDER BY name ASC");

// Handle form submission
if (isset($_POST['add_course'])) {
    $course_name = $_POST['course_name'];
    $teacher_id = $_POST['teacher_id'];

    $stmt = $conn->prepare("INSERT INTO Courses (course_name, teacher_id) VALUES (?, ?)");
    $stmt->bind_param("si", $course_name, $teacher_id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_courses.php?msg=added");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Course</title>
    <link rel="stylesheet" href="add_form.css">
</head>
<body>

<h2>Add New Course</h2>
<a href="manage_courses.php" class="back-btn">← Back to Courses</a>

<form method="POST" class="add-form">
    <label for="course_name">Course Name:</label><br>
    <input type="text" name="course_name" id="course_name" placeholder="Enter course name" required><br><br>

    <label for="teacher_id">Assign Teacher:</label><br>
    <select name="teacher_id" id="teacher_id" required>
        <option value="">-- Select Teacher --</option>
        <?php while ($row = $teacher_result->fetch_assoc()): ?>
            <option value="<?php echo $row['teacher_id']; ?>">
                <?php echo htmlspecialchars($row['name']); ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <button type="submit" name="add_course">Add Course</button>
</form>

</body>
</html>

<?php $conn->close(); ?>
