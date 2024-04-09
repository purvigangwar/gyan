<?php
// Include your database connection
include 'db.php';

// Get the submitted form data
$faculty_id = $_POST['faculty_id'] ?? '';
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? ''; // Example: added last_name field

// Sanitize these inputs as necessary

// Prepare an SQL update statement with correct syntax
$sql = "UPDATE faculty SET first_name = ?, last_name = ? WHERE faculty_id = ?";
$stmt = $conn->prepare($sql);

if (false === $stmt) {
    // Prepare failed
    echo "Prepare failed: " . htmlspecialchars($conn->error);
    exit();
}

// Correctly bind parameters. Ensure the number of variables matches the placeholders
$bound = $stmt->bind_param("ssi", $first_name, $last_name, $faculty_id);

if (false === $bound) {
    // Bind failed
    echo "Bind param failed: " . htmlspecialchars($stmt->error);
    exit();
}

if ($stmt->execute()) {
    // Redirect back to the profile page or show a success message
    header("Location: faculty_profile.php?faculty_id=" . $faculty_id);
    exit();
} else {
    // Execute failed
    echo "Error updating record: " . htmlspecialchars($stmt->error);
}

$stmt->close();
$conn->close();
?>
