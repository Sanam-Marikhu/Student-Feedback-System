<?php
include_once 'db_connect.php';
// // Database connection
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "student_feedback_system";

// $conn = new mysqli($servername, $username, $password, $dbname, 3307);

// // Check connection
// if ($conn->connect_error) {
//     die("<div style='color:red; font-weight:bold;'>Database connection failed.</div>");
// }

// Get POST data safely
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$pass = trim($_POST['password'] ?? '');

// Basic validation
if (empty($name) || empty($email) || empty($pass)) {
    die("<div style='color:red;'>All fields are required!</div>");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("<div style='color:red;'>Invalid email format!</div>");
}

// Check if email exists
$check = $conn->prepare("SELECT student_id FROM Students WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    die("<h1 style='color:red;text-align:center;'>Email already registered!</h1>");
}

// Hash password
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

// Insert student
$stmt = $conn->prepare("INSERT INTO Students (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashed_password);

if ($stmt->execute()) {
    echo "
        <div style='text-align:center; margin-top:40px; font-family:Arial;'>
            <h2 style='color:green;'>Registration Successful!</h2>
            <p>Your account has been created.</p>
            <a href='login.html' 
               style='display:inline-block; background:#f39c12; color:white; padding:10px 20px;
                      border-radius:6px; text-decoration:none; font-weight:bold;'>
                Login Here
            </a>
        </div>
    ";
} else {
    echo "<div style='color:red;'>Error: " . $stmt->error . "</div>";
}

// Close everything
$stmt->close();
$check->close();
$conn->close();
?>
