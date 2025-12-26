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

// Handle form submission
if (isset($_POST['add_teacher'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO Teachers (name, email, password, department) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $department);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_teachers.php?msg=added");
    exit();
}
?>

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Teacher</title>
    <link rel="stylesheet" href="add_form.css">
</head>
<body>

<h2>Add New Teacher</h2>
<a href="manage_teachers.php" class="back-btn">← Back to Teachers</a>

<form method="POST" class="add-form">
    <label for="name">Teacher Name:</label><br>
    <input type="text" name="name" id="name" placeholder="Enter teacher name" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email" placeholder="Enter email" required><br><br>

    <label for="department">Department:</label><br>
    <select name="department" id="department" required>
        <option value="">-- Select Department --</option>
        <option value="BCA">BCA</option>
        <option value="CSIT">CSIT</option>
    </select><br><br>

    <label for="password">Password:</label><br>
    <input type="password" name="password" id="password" placeholder="Enter password" required><br><br>

    <button type="submit" name="add_teacher">Add Teacher</button>
</form>

</body>
</html>

<?php $conn->close(); ?>
