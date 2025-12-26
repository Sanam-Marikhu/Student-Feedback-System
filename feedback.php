<?php
session_start();

// Ensure student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.html");
    exit();
}

// Initialize messages
$success = $error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_SESSION['student_id'];
    $course_id = $_POST['course_id'];
    $rating = $_POST['rating'];
    $comments = trim($_POST['comments']);

    // Basic validation
    if (empty($course_id) || empty($rating)) {
        $error = "Please select a course and provide a rating.";
    } else {
        // Database connection
        include_once 'db_connect.php';
        // $conn = new mysqli("localhost", "root", "", "student_feedback_system");
        // if ($conn->connect_error) {
        //     die("Connection failed: " . $conn->connect_error);
        // }

        // Insert feedback
        $stmt = $conn->prepare("INSERT INTO Feedback (student_id, course_id, rating, feedback_text) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $student_id, $course_id, $rating, $comments);

        if ($stmt->execute()) {
            $success = "Feedback submitted successfully!";
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Feedback</title>
    <link rel="stylesheet" href="feedback.css">
</head>
<body>
    <h2>Feedback Form</h2>

    <!-- Display messages -->
    <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post" action="">
        <!-- Hidden student ID -->
        <input type="hidden" name="student_id" value="<?php echo $_SESSION['student_id']; ?>">

        <!-- Course dropdown -->
        <label for="course">Course:</label>
        <select name="course_id" id="course" required>
            <option value="">-- Select Course --</option>
            <?php
            // Database connection for courses
            $conn = new mysqli("localhost", "root", "", "student_feedback_system");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $result = $conn->query("SELECT course_id, course_name FROM Courses");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="'. $row['course_id'] .'">'. htmlspecialchars($row['course_name']) .'</option>';
                }
            } else {
                echo '<option value="">No courses available</option>';
            }

            $conn->close();
            ?>
        </select><br><br>

        <!-- Rating -->
        <label for="rating">Rating (1-5):</label>
        <input type="number" name="rating" id="rating" min="1" max="5" required><br><br>

        <!-- Comments -->
        <label for="comments">Comments:</label><br>
        <textarea name="comments" id="comments" rows="4" cols="50" required></textarea><br><br>

        <input type="submit" value="Submit Feedback">
    </form>
     <a href="student_dashboard.php">Back to Dashboard</a><br><br>
</body>
</html>
