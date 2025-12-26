<?php
session_start();

// Only process if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // include_once 'db_connect.php';

    $servername = "localhost";
    $username = "root"; 
    $password = "";     
    $dbname = "student_feedback_system"; 

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = trim($_POST['email']);
    $pass  = trim($_POST['password']);

    if (empty($email) || empty($pass)) {
        die("<h1 style='color:red;text-align:center;'>Email and Password are required!</h1>");
    }

    // Admin login
    $admin_email = "admin@system.com";
    $admin_pass  = "admin123";

    if ($email === $admin_email && $pass === $admin_pass) {
        $_SESSION['admin'] = true;
        $_SESSION['admin_name'] = "System Admin";
        header("Location: admin_dashboard.php");
        exit();
    }

    // Student login
    $stmt = $conn->prepare("SELECT student_id, name, password FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            $_SESSION['student_id'] = $row['student_id'];
            $_SESSION['student_name'] = $row['name'];
            header("Location: student_dashboard.php");
            exit();
        }
    }

    // Teacher login
    $stmt = $conn->prepare("SELECT teacher_id, name, password FROM Teachers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // Assuming teacher password stored in plain text
        if ($pass === $row['password']) {
            $_SESSION['teacher_id'] = $row['teacher_id'];
            $_SESSION['teacher_name'] = $row['name'];
            header("Location: teacher_dashboard.php");
            exit();
        }
    }

    // Invalid login
die("
<div style='
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    height:100vh;
'>
    <h1 style='color:red;'>Invalid Email or Password!</h1>
    <a style=' display: inline-block;
    background: #e67e22;
    color: #ffffffff;
    text-decoration: none;
    font-weight: bold;
    padding: 10px 20px;
    border-radius: 6px;
    transition: all 0.3s ease;
    margin: 5px;' href='login.html'>Back to login</a>
</div>
");


  $conn->close();
} else {
    // If someone opens login.php directly without POST
    header("Location: login.html");
    exit();
}
?>
