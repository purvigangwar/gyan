<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Faculty Search Results</title>
  <link rel="stylesheet" href="faculty1.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <nav class="navbar">
    <!-- Navbar content (omitted for brevity) -->
    <ul class="nav-list">
      <div class="logo"><img src="images/logog.jpg" alt="logo"></div>
      <li><a href="index.html">HOME</a></li>
      <li><a href="aboutus.html">ABOUT US</a></li>
      <li><a href="login.php">LOGIN</a></li>
      <li><a href="reg.php">REGISTRATION</a></li>
    </ul>
    <div class="rightnav">
      <form action="searchresults.php" method="post"> <!-- Adjusted action to searchresults.php -->
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
      $sql = "SELECT * FROM faculty WHERE first_name LIKE '%$search_query%' OR last_name LIKE '%$search_query%'";
      break;
    case 'designation':
      $sql = "SELECT * FROM faculty WHERE designation LIKE '%$search_query%'";
      break;
    case 'expertise':
      $sql = "SELECT * FROM faculty WHERE expertise LIKE '%$search_query%'";
      break;
    case 'department':
      // This requires a JOIN with a department table if department details are stored separately
      $sql = "SELECT faculty.*, department.department_name FROM faculty JOIN department ON faculty.department_id = department.department_id WHERE department.department_name LIKE '%$search_query%'";
      break;
    // Add other cases as needed
    default:
      $sql = "SELECT * FROM faculty"; // Default case to fetch all faculty if no specific search is made
  }

  $result = $conn->query($sql);

  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      // Assuming 'title', 'first_name', 'last_name', etc., are columns in your 'faculty' table
      $faculty_id = $row["faculty_id"];
      $title = $row["title"] ?? 'Prof.'; // Default to 'Prof.' if title is not available
      $first_name = $row["first_name"];
      $last_name = $row["last_name"];
      $designation = $row["designation"];
      $expertise = $row["expertise"]; // Assuming expertise is a comma-separated list of strings
      $department_name = $row["department_name"] ?? 'N/A'; // Handle if department name is not fetched

      echo '<div class="outer-section">';
      echo '  <div class="inner-section">';
      echo '    <div class="faculty">';
      echo '      <div class="image"><img src="images/placeholder.png" alt="Faculty Image"></div>';
      echo '      <div class="details">';
      echo "        <h2>$title $first_name $last_name</h2>";
      echo "        <h3>$designation</h3>";
      echo "        <p>$department_name</p>"; // Show department name if available
      echo "        <h3>Expertise</h3><ul>";
      $expertise_list = explode(",", $expertise);
      foreach ($expertise_list as $exp) {
        echo "          <li>$exp</li>";
      }
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
</body>
</html>