<?php
// Include database connection
include "db.php";

// Check if faculty ID is provided in the URL
if(isset($_GET["faculty_id"])) {
    // Get faculty ID from the URL
    $faculty_id = $_GET["faculty_id"];

    // Retrieve image URL from the database based on faculty ID
    $query = "SELECT image_url FROM faculty WHERE faculty_id=$faculty_id";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        // Fetch the row
        $row = mysqli_fetch_assoc($result);

        // Check if image URL is not empty
        if(!empty($row["image_url"])) {
            // Output the image URL
            echo $row["image_url"];
        } else {
            // If no image URL is found, output a default image URL or any suitable message
            echo "No profile image found for this faculty member.";
        }
    } else {
        // If no matching faculty ID is found in the database, output a suitable message
        echo "Faculty ID not found in the database.";
    }
} else {
    // If faculty ID is not provided in the URL, output a suitable message
    echo "Please provide a valid faculty ID in the URL.";
}

// Close database connection
mysqli_close($conn);
?>
