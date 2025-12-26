<?php
$conn = new mysqli("localhost", "root", "", "student_feedback_system");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
?>     