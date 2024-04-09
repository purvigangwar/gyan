<?php
// Include database connection
include "db.php";

// Check if faculty ID is provided in the URL
if(isset($_GET["faculty_id"])) {
    // Get faculty ID from the URL
    $faculty_id = $_GET["faculty_id"];

    // Retrieve expertise from the database based on faculty ID
    $query = "SELECT expertise FROM faculty WHERE faculty_id=$faculty_id";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        // Fetch the row
        $row = mysqli_fetch_assoc($result);

        // Check if expertise is not empty
        if(!empty($row["expertise"])) {
            // Output the expertise
            echo $row["expertise"];
        } else {
            // If no expertise is found, output a suitable message
            echo "No expertise found for this faculty member.";
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
