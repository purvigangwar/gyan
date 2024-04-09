<?php
// Include database connection
include "db.php";

// Check if faculty ID is provided in the URL
if(isset($_GET["faculty_id"])) {
    // Get faculty ID from the URL
    $faculty_id = $_GET["faculty_id"];

    // Retrieve publications from the database based on faculty ID
    $query = "SELECT publication_title, publication_type, publication_date FROM publication WHERE faculty_id=$faculty_id";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        // Output the publications as a list
        echo "<ul>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<li><strong>Title:</strong> " . $row["publication_title"] . ", <strong>Type:</strong> " . $row["publication_type"] . ", <strong>Date:</strong> " . $row["publication_date"] . "</li>";
        }
        echo "</ul>";
    } else {
        // If no publications are found, output a suitable message
        echo "No publications found for this faculty member.";
    }
} else {
    // If faculty ID is not provided in the URL, output a suitable message
    echo "Please provide a valid faculty ID in the URL.";
}

// Close database connection
mysqli_close($conn);
?>
