<?php
// Assume database connection is already established
include 'db.php';

$faculty_id = $_GET['faculty_id'] ?? ''; // Get faculty_id from URL

// Fetch faculty information from the database
$sql = "SELECT * FROM faculty WHERE faculty_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $faculty = $result->fetch_assoc();
} else {
    die("Faculty not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Faculty Profile</title>
    <!-- Include CSS files as needed -->
</head>
<body>
    <h2>Edit Faculty Profile</h2>
    <form action="update_faculty.php" method="post">
        <input type="hidden" name="faculty_id" value="<?php echo $faculty_id; ?>">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo $faculty['first_name']; ?>"><br>
        <label for="last_name">Last Name:</label>
        <input type="text" id="lastt_name" name="last_name" value="<?php echo $faculty['last_name']; ?>"><br>
        
        <!-- Add more fields as needed for all editable information -->
        
        <input type="submit" value="Update Profile">
    </form>
</body>
</html>
