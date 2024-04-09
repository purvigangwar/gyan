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
  include('db.php'); // Use your database connection details
  
  // Fetch the search criteria from the form submission
  $search_category = $_POST['search_category'] ?? '';
  $search_query = $_POST['search_query'] ?? '';
  
  // Sanitize the input to prevent SQL injection
  $search_query = $conn->real_escape_string($search_query);

  // Construct the SQL query based on the selected search category
  // (This section is adapted from your original search.php code)
  switch ($search_category) {
    case 'faculty_name':
      $sql = "SELECT faculty.first_name, faculty.last_name, faculty.designation, faculty.expertise, department.department_name FROM faculty JOIN department ON faculty.department_id = department.department_id WHERE faculty.first_name LIKE '%$search_query%' OR faculty.last_name LIKE '%$search_query%'";
      break;
    case 'designation':
      $sql = "SELECT * FROM faculty WHERE designation LIKE '%$search_query%'";
      break;
    case 'expertise':
      $sql = "SELECT * FROM faculty WHERE expertise LIKE '%$search_query%'";
      break;
    case 'department':
      $sql = "SELECT faculty.* FROM faculty JOIN department ON faculty.department_id = department.department_id WHERE department.department_name LIKE '%$search_query%'";
      break;
    case 'publication':
      $sql = "SELECT faculty.first_name, faculty.last_name, publication.publication_title FROM faculty JOIN publication ON faculty.faculty_id = publication.faculty_id WHERE publication.publication_title LIKE '%$search_query%'";
      break;
    case 'conference':
      $sql = "SELECT faculty.first_name, faculty.last_name, conference.conference_name FROM faculty JOIN conference ON faculty.faculty_id = conference.faculty_id WHERE conference.conference_name LIKE '%$search_query%'";
      break;
    default:
      // Handle invalid search category
      echo "<p>Please select a valid search category.</p>";
      $conn->close();
      exit;
  }

  // Execute the search query
  $result = $conn->query($sql);

  // Check if there are results and output them
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      // Adapt this section to format the output as per your requirements
      // This is a simple example showing the name and one attribute based on the search category
      echo "<p>Name: " . $row["first_name"] . " " . $row["last_name"];
      switch ($search_category) {
        case 'faculty_name':
        case 'designation':
        case 'expertise':
          echo " - Designation: " . $row["designation"];
          break;
        case 'department':
          echo " - Department: " . $row["department_name"];
          break;
          case 'publication':
            echo " - Publication Title: " . $row["publication_title"];
            break;
          case 'conference':
            echo " - Conference Name: " . $row["conference_name"];
            break;
        }
        echo "</p>";
      }
    } else {
      echo "<p>No results found.</p>";
    }
    
    // Close the connection
    $conn->close();
    ?>
  </body>
  </html>
  