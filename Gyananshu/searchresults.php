<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/AboutUs.css">
    <link rel="stylesheet" href="faculty.css">
    <title>Gyananshu - A Scholar Connect</title>
</head>
<body>
      <nav class="navbar">
        <ul class="nav-list">
            <div class="logo"><img src="images/logog.jpg" alt="logo">
            </div>
            <li><a href="index.html">HOME</a></li>
            <li><a href="aboutus.html">ABOUT US</a></li>
            <li><a href="login.php">LOGIN</a></li>
            <li><a href="reg.php">REGISTRATION</a></li>            
        </ul>
        
        <div class="rightnav">
            <form action="searchresults.php" method="post">
                <select name="search_category">
                    <option value="faculty_name">Faculty Name</option>
                    <option value="designation">Designation</option>
                    <option value="expertise">Expertise</option>
                    <option value="department">Department</option>
                    <option value="publication">Research Paper/Publication</option>
                    <option value="conference">Conference</option>
                </select>
                <input type="text" name="search_query" placeholder="Search...">
                
                <button type="submit" class="btn btn-sm">Search</button>
            </form>
        </div>
    </nav>
</body>
</html>
<?php
include('db.php'); // Include your database connection details

// Get search category and query from POST data, with fallbacks
$search_category = $_POST['search_category'] ?? '';
$search_query = $_POST['search_query'] ?? '';

// Sanitize the input to prevent SQL injection
$search_query = $conn->real_escape_string($search_query);

// Determine SQL query based on the search category
switch ($search_category) {
    case 'faculty_name':
        $sql = "SELECT faculty.*, department.department_name 
                FROM faculty 
                LEFT JOIN department ON faculty.department_id = department.department_id 
                WHERE faculty.first_name LIKE '%$search_query%' OR faculty.last_name LIKE '%$search_query%'";
        break;
    case 'designation':
        $sql = "SELECT faculty.*, department.department_name 
                FROM faculty 
                LEFT JOIN department ON faculty.department_id = department.department_id 
                WHERE faculty.designation LIKE '%$search_query%'";
        break;
    case 'expertise':
        $sql = "SELECT faculty.*, department.department_name 
                FROM faculty 
                LEFT JOIN department ON faculty.department_id = department.department_id 
                WHERE faculty.expertise LIKE '%$search_query%'";
        break;
    case 'department':
        $sql = "SELECT faculty.*, department.department_name 
                FROM faculty 
                LEFT JOIN department ON faculty.department_id = department.department_id 
                WHERE department.department_name LIKE '%$search_query%'";
        break;
    default:
        $sql = "SELECT faculty.*, department.department_name 
                FROM faculty 
                LEFT JOIN department ON faculty.department_id = department.department_id";
}

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $faculty_id = $row["faculty_id"];
        $title = $row["title"] ?? 'Prof.'; // Default to 'Prof.' if title is not available
        $first_name = $row["first_name"];
        $last_name = $row["last_name"];
        $designation = $row["designation"];
        $expertise = $row["expertise"]; // Assuming expertise is a comma-separated list of strings
        $department_name = $row["department_name"] ?? 'N/A'; // Handle if department name is not fetched
        $image_url = !empty($row["image_url"]) ? $row["image_url"] : 'images/placeholder.png';

        // Use the faculty image if available, otherwise use a placeholder

        echo '<div class="outer-section">';
        echo '  <div class="inner-section">';
        echo '    <div class="faculty">';
        //echo '<div class="image"><img src="images/placeholder.png" alt="Faculty Image" style="width: 200px; height: 200px; object-fit: cover;"></div>';
        echo '  <img src="' . htmlspecialchars($image_url) . '" alt="Faculty Image" style="width: 200px; height: 200px; object-fit: cover;">';
  
        echo '      <div class="details">';
        echo "          <a href=faculty1.php?faculty_id=$faculty_id><h2>$title $first_name $last_name</h2></a>";
        echo "        <h3>$designation</h3>";
        echo "        <p>$department_name</p>"; // Show department name if available
        echo "        <h3>Expertise</h3><ul>";
        $expertise_list = explode(",", $expertise);
        foreach ($expertise_list as $exp) {
            echo "          <li>$exp</li>";
        }
        echo '<a href="cont.html" class="btn-contact" style="display: inline-block; margin-top: 10px; height: 40px; font-size: 20px; padding: 5px 10px; background-color: black; color: white; text-decoration: none; border-radius: 5px;">Contact Me</a>';
        echo "        </ul>";
        echo '      </div>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
    }
} else {
    echo "<p>No results found.</p>";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/AboutUs.css">
    
    
    <title>Gyananshu - A Scholar Connect</title>
</head>
<body>
   
    <footer><br><br>
        <h3 class="contact">Get in touch</h3>
        <h4>Department of Computer Science<br>Banasthali Vidhyapith<br>Jaipur, Rajasthan</h4>
        <h5 class="fill-white">Copyright &copy; 2023 Gyananshu: The Scholar Connect</h5>
    </footer>
</body>
</html>
