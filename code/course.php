<?php
$conn = new mysqli("localhost", "root", "", "student_feedback_system", 3307);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM Courses");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo $row['course_id'] . " - " . $row['course_name'] . "<br>";
    }
} else {
    echo "No courses found!";
}
$conn->close();
?>
