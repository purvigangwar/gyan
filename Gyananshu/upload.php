<?php
session_start(); // Start the session

// Retrieve the faculty ID from the form data
if(isset($_POST['facultyId']) && !empty($_POST['facultyId'])) {
    $faculty_id = $_POST['facultyId']; // Sanitize the input if needed
} else {
    // Handle the case where facultyId is not provided or is empty
    die("Faculty ID not provided.");
}
// Database connection
$host = "localhost"; // MySQL host
$username = "root"; // MySQL username
$password = ""; // MySQL password
$database = "gyananshu"; // Database name

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
/*
// Check if form is submitted
if(isset($_POST["submit"])) {
    // Check if file was uploaded without errors
    if(isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $faculty_id = $_POST['faculty_id']; // Assuming faculty_id is passed via form or session
        
        // File upload
        $targetDir = "uploads/"; // Folder to store uploaded images
        $targetFile = $targetDir . basename($_FILES["file"]["name"]);
        
        // Check file format
        $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
        $allowedFormats = array("jpg", "jpeg", "png", "gif");
        if(!in_array($imageFileType, $allowedFormats)) {
            echo "Sorry, only JPG, JPEG, PNG, GIF files are allowed.";
            exit();
        }

        // Check file size
        if ($_FILES["file"]["size"] > 5000000) { // 5 MB (size in bytes)
            echo "Sorry, your file is too large. Maximum size allowed is 5MB.";
            exit();
        }

        // Move the uploaded image to the folder
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            // Update faculty table with image URL
            $imageUrl = $targetFile;
            $sql = "UPDATE faculty SET image_url = '$imageUrl' WHERE faculty_id = $faculty_id";
            
            if (mysqli_query($conn, $sql)) {
                echo "Image uploaded successfully.";
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "No file uploaded or error occurred.";
    }
}

mysqli_close($conn);*/

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["facultyImage"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is an actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["facultyImage"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["facultyImage"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["facultyImage"]["name"])). " has been uploaded.";
    // Here you should add code to update the database with the new image path
    // Assume $facultyId is the ID of the faculty member and is available
    $sql = "UPDATE faculty SET image_url='$target_file' WHERE id=$faculty_id";
    // Execute the SQL query using your database connection
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
?>
