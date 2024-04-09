<?php
// Database connection
$host = "localhost"; // MySQL host
$username = "root"; // MySQL username
$password = ""; // MySQL password
$database = "gyananshu"; // Database name

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve faculty ID from the URL parameter
if(isset($_GET['faculty_id'])) {
    $faculty_id = $_GET['faculty_id'];

    // Fetch image URL from the faculty table based on faculty ID
    $sql = "SELECT image_url FROM faculty WHERE faculty_id = $faculty_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Output image
        $row = mysqli_fetch_assoc($result);
        $image_url = $row["image_url"];
        echo '<div class="circular-image"><img src="' . $image_url . '" alt="Faculty Image"></div>';
    } else {
        echo "No image found for this faculty.";
    }
} else {
    echo "Faculty ID not provided.";
}

mysqli_close($conn);
?>
