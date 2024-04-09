<?php
// Connection variables
/*$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gyananshu";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve search inputs
$faculty_name = $_POST['faculty_name'];
$designation = $_POST['designation'];
$expertise = $_POST['expertise'];
$department = $_POST['department'];
$publication = $_POST['publication'];
$conference = $_POST['conference'];

// Construct the SQL query
$query = "SELECT * FROM faculty 
          JOIN department ON faculty.department_id = department.department_id 
          JOIN publication ON faculty.faculty_id = publication.faculty_id 
          JOIN conference ON faculty.faculty_id = conference.faculty_id 
          WHERE faculty.faculty_name LIKE '%$faculty_name%' 
          OR faculty.designation LIKE '%$designation%' 
          OR faculty.expertise LIKE '%$expertise%' 
          OR department.department_name LIKE '%$department%' 
          OR publication.publication_title LIKE '%$publication%' 
          OR conference.conference_name LIKE '%$conference%'";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "Faculty Name: " . $row["faculty_name"]. " - Designation: " . $row["designation"]. " - Expertise: " . $row["expertise"]. " - Department: " . $row["department_name"]. " - Publication: " . $row["publication_title"]. " - Conference: " . $row["conference_name"]. "<br>";
    }
} else {
    echo "0 results found";
}

$conn->close();*/

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gyananshu";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$search_category = $_POST['search_category'] ?? '';
$search_query = $_POST['search_query'] ?? '';

// Sanitize the input to prevent SQL injection
$search_query = $conn->real_escape_string($search_query);

// Initialize SQL query variable
$sql = "";

// Construct the SQL query based on the selected search category
switch ($search_category) {
    case 'faculty_name':
        $sql = "SELECT faculty.first_name,faculty.last_name, faculty.designation, faculty.expertise, department.department_name FROM faculty JOIN department ON faculty.department_id = department.department_id WHERE faculty.first_name LIKE '%$search_query%' OR faculty.last_name LIKE '%$search_query%'";
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
        $sql = "SELECT faculty.first_name,faculty.last_name, publication.publication_title FROM faculty JOIN publication ON faculty.faculty_id = publication.faculty_id WHERE publication.publication_title LIKE '%$search_query%'";
        break;
    case 'conference':
        $sql = "SELECT faculty.first_name,faculty.last_name, conference.conference_name  FROM faculty JOIN conference ON faculty.faculty_id = conference.faculty_id WHERE conference.conference_name LIKE '%$search_query%'";
        break;
    default:
        echo "Please select a valid search category.";
        exit;
}

// Execute the query
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        switch ($search_category) {
            case 'faculty_name':
            case 'designation':
            case 'expertise':
                echo "Name: " . $row["first_name"] . " - Designation: " . $row["designation"] . " - Expertise: " . $row["expertise"] . "<br>";
                break;
            case 'department':
                echo "Name: " . $row["first_name"] . " - Department: " . $row["department_name"] . "<br>";
                break;
            case 'publication':
                echo "Faculty Name: " . $row["first_name"] . " - Publication Title: " . $row["publication_title"] . "<br>";
                break;
            case 'conference':
                echo "Faculty Name: " . $row["first_name"] . " - Conference Name: " . $row["conference_name"] . "<br>";
                break;
        }
    }
} else {
    echo "0 results found.";
}

// Close the connection
$conn->close();
?>
