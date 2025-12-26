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
if (isset($_POST['add_student'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO Students (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_students.php?msg=added");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Student</title>
    <link rel="stylesheet" href="add_form.css">
</head>
<body>

<h2>Add New Student</h2>
<a href="manage_students.php" class="back-btn">← Back to Students</a>

<form method="POST" class="add-form">
    <label for="name">Student Name:</label><br>
    <input type="text" name="name" id="name" placeholder="Enter student name" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email" placeholder="Enter email" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" name="password" id="password" placeholder="Enter password" required><br><br>

    <button type="submit" name="add_student">Add Student</button>
</form>

</body>
</html>

<?php $conn->close(); ?>
