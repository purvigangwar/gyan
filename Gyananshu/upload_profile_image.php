<?php
// Check if form is submitted
if(isset($_POST["submit"])) {
    // Include database connection
    include "db.php";

    // Get faculty ID from the form
    $faculty_id = $_POST["faculty_id"];

    // Specify the directory where the image will be stored
    $target_dir = "profile_images/";

    // Get the file name
    $file_name = basename($_FILES["file"]["name"]);

    // Set the target file path
    $target_file = $target_dir . $file_name;

    // Check if the file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
    } else {
        // Upload the file
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            // File uploaded successfully, now update the database with the file path
            $image_url = "http://yourdomain.com/" . $target_file; // Replace "http://yourdomain.com/" with your actual domain
            $update_query = "UPDATE faculty SET image_url='$image_url' WHERE faculty_id=$faculty_id";

            if (mysqli_query($conn, $update_query)) {
                echo "The file " . htmlspecialchars($file_name) . " has been uploaded and the image URL has been updated in the database.";
            } else {
                echo "Error updating image URL in the database: " . mysqli_error($conn);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Close database connection
    mysqli_close($conn);
}
?>
