<?php


include 'db.php'; // Include your database connection
// Assuming you have the faculty ID
$faculty_id = $_GET['faculty_id'] ?? ''; // Replace with a valid default or error handling as needed
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit-photo'])) {
    // Check if file is uploaded
    if(isset($_FILES["new-photo"])) {
        // Directory where the image will be stored
        $target_dir = "uploads/";
        // Name of the uploaded file
        $target_file = $target_dir . basename($_FILES["new-photo"]["name"]);
        // Check if the file is an image
        $check = getimagesize($_FILES["new-photo"]["tmp_name"]);
        if($check !== false) {
            // If the file is an image, move it to the uploads folder
            if (move_uploaded_file($_FILES["new-photo"]["tmp_name"], $target_file)) {
                // Image URL to store in the database
                $image_url1 = $target_file;
                // Update the faculty table with the image URL
                $sql = "UPDATE faculty SET image_url=? WHERE faculty_id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $image_url1, $faculty_id);
                if ($stmt->execute()) {
                    echo "Image uploaded successfully.";
                } else {
                    echo "Error updating image URL in the database.";
                }
                $stmt->close();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
        }
    } else {
        echo "No file uploaded.";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit-expertise'])) {
    // Assuming expertise values are submitted as an array of inputs with name expertise[]
    $expertise = $_POST['expertise'] ?? [];

    // Convert the array of expertise values into a comma-separated string
    $expertise_list = implode(',', $expertise);

    // Update the expertise in the database
    $sql = "UPDATE faculty SET expertise=? WHERE faculty_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $expertise_list, $faculty_id);

    if ($stmt->execute()) {
        echo "Expertise updated successfully.";
    } else {
        echo "Error updating expertise in the database.";
    }
    $stmt->close();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit-personal'])) {
    // Retrieve form data
    $title = $_POST['title'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $department_name = $_POST['department'] ?? '';

    // Check if the department already exists in the department table
    $sql_check_department = "SELECT department_id FROM department WHERE department_name = ?";
    $stmt_check_department = $conn->prepare($sql_check_department);
    $stmt_check_department->bind_param("s", $department_name);
    $stmt_check_department->execute();
    $result_check_department = $stmt_check_department->get_result();

    if ($result_check_department->num_rows > 0) {
        // If the department exists, fetch its department_id
        $row = $result_check_department->fetch_assoc();
        $department_id = $row['department_id'];
    } else {
        // If the department does not exist, insert it into the department table
        $sql_insert_department = "INSERT INTO department (department_name) VALUES (?)";
        $stmt_insert_department = $conn->prepare($sql_insert_department);
        $stmt_insert_department->bind_param("s", $department_name);
        if ($stmt_insert_department->execute()) {
            // Get the auto-generated department_id
            $department_id = $stmt_insert_department->insert_id;
        } else {
            echo "Error inserting department into the database.";
            exit; // Exit if there's an error
        }
        $stmt_insert_department->close();
    }

    // Update the faculty table with the new personal information and department_id
    $sql_update_personal_info = "UPDATE faculty SET title=?, first_name=?, last_name=?, gender=?, department_id=? WHERE faculty_id=?";
    $stmt_update_personal_info = $conn->prepare($sql_update_personal_info);
    $stmt_update_personal_info->bind_param("ssssii", $title, $first_name, $last_name, $gender, $department_id, $faculty_id);

    if ($stmt_update_personal_info->execute()) {
        // If the update is successful, close the modal and refresh the page
        echo "success";
    } else {
        echo "Error updating personal information in the database.";
    }
    $stmt_update_personal_info->close();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit-experience'])) {
    // Retrieve form data
    $experience_year = $_POST['experience-year'] ?? '';
    $experience_position = $_POST['experience-position'] ?? '';

    // Update the faculty table with the new experience information
    $sql_update_experience = "UPDATE faculty SET year_of_join=?, designation=? WHERE faculty_id=?";
    $stmt_update_experience = $conn->prepare($sql_update_experience);
    $stmt_update_experience->bind_param("ssi", $experience_year, $experience_position, $faculty_id);

    if ($stmt_update_experience->execute()) {
        // If the update is successful, close the modal and refresh the page
        echo "Experience information updated successfully.";
    } else {
        echo "Error updating experience information in the database.";
    }
    $stmt_update_experience->close();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit-qualification'])) {
    // Retrieve form data
    $qualification = $_POST['qualification'] ?? '';

    // Update the faculty table with the new qualification information
    $sql_update_qualification = "UPDATE faculty SET qualification=? WHERE faculty_id=?";
    $stmt_update_qualification = $conn->prepare($sql_update_qualification);
    $stmt_update_qualification->bind_param("si", $qualification, $faculty_id);

    if ($stmt_update_qualification->execute()) {
        // If the update is successful, close the modal and refresh the page
        echo "Qualification information updated successfully.";
    } else {
        echo "Error updating qualification information in the database.";
    }
    $stmt_update_qualification->close();
}
// Check if the form for adding a new publication is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit-publication'])) {
    // Retrieve form data
    $publication_title = $_POST['publication-title'] ?? '';
    $publisher = $_POST['publisher'] ?? '';
    $publication_type = $_POST['publication-type'] ?? '';
    $publication_date = intval($_POST['publication-date'] ?? ''); // Convert to integer
    $publication_link = $_POST['publication-link'] ?? '';

    // Insert the new publication into the publication table
    $sql_insert_publication = "INSERT INTO publication (faculty_id, publication_title, publisher, publication_type, publication_date, publication_link) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_insert_publication = $conn->prepare($sql_insert_publication);
    $stmt_insert_publication->bind_param("isssis", $faculty_id, $publication_title, $publisher, $publication_type, $publication_date, $publication_link);

    if ($stmt_insert_publication->execute()) {
        echo "Publication added successfully.";
        header("Location: faculty.php?faculty_id={$faculty_id}");
        exit();
    } else {
        echo "Error adding publication to the database.";
    }
    $stmt_insert_publication->close();
}

// Prepare SQL query to fetch faculty information along with department name
$sql = "SELECT f.title, f.first_name, f.last_name, f.gender, f.designation, f.year_of_join, f.qualification, f.expertise, d.department_name 
        FROM faculty f 
        JOIN department d ON f.department_id = d.department_id 
        WHERE f.faculty_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $faculty = $result->fetch_assoc(); // Fetch faculty information
} else {
    echo "No faculty found.";
    // Handle case where no faculty is found
}
$sql2 = "SELECT publication_title, publisher, publication_type, publication_date, publication_link
        FROM publication
        WHERE faculty_id = ?
        ORDER BY publication_date DESC"; // Ordering by publication_date for example

$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $faculty_id);
$stmt2->execute();
$result2 = $stmt2->get_result();

$publications = [];
if ($result2->num_rows > 0) {
    while ($row = $result2->fetch_assoc()) {
        $publications[] = $row;
    }
} 
$expertise_list = $faculty['expertise']; // This contains a comma-separated list of expertise areas

// Convert the comma-separated list into an array
$expertise_array = explode(',', $expertise_list);
$sql3 = "SELECT image_url FROM faculty WHERE faculty_id = ?";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("i", $faculty_id);
$stmt3->execute();
$result3 = $stmt3->get_result();

if ($result3->num_rows > 0) {
    $row = $result3->fetch_assoc();
    $image_url = $row['image_url'];
    preg_match('/\/file\/d\/(.*?)\//', $image_url, $matches);
    $file_id = $matches[1] ?? '';

    // Construct the direct image URL
    $direct_image_url = "https://drive.google.com/uc?export=view&id={$file_id}";
} else {
    echo "Faculty image not found.";
    // You may choose to set $image_url to a default image path if no specific image is found
    $image_url = "images/default.jpg"; }// Path to a default image
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty</title>
    <link rel="stylesheet" href="dummy.css">
    <link rel="stylesheet" href="css/style.css">
    <script>
        // Function to navigate back to faculty.php with faculty_id in the URL
        function navigateBack() {
            // Get the faculty_id from the URL
            const urlParams = new URLSearchParams(window.location.search);
            const faculty_id = urlParams.get('faculty_id');

            // If faculty_id is valid, navigate back to faculty.php with the faculty_id in the URL
            if (faculty_id) {
                // Construct the URL
                const url = `faculty.php?faculty_id=${faculty_id}`;

                // Use history.pushState() to modify browser history
                history.pushState({}, '', url);
            } else {
                // If faculty_id is not available, navigate back to faculty.php without any query parameters
                history.back();
            }
        }

        // Event listener for the browser's back button
        window.addEventListener('popstate', function(event) {
            // Call navigateBack function when the back button is clicked
            navigateBack();
        });

        // Call navigateBack function when the document is loaded
        document.addEventListener('DOMContentLoaded', function(event) {
            navigateBack();
        });
    </script>
</head>
<body>
    <!-- Header Section -->
    <nav class="navbar">
        <ul class="nav-list">
            <div class="logo"><img src="images/logog.jpg" alt="logo"></div>
            <li><a href="index.html">HOME</a></li>
            <li><a href="aboutus.html">ABOUT US</a></li>
            <li><a href="login.php">LOGIN</a></li>
            <li><a href="reg.php">REGISTRATION</a></li>            
        </ul>
        <div class="rightnav">
            <form action="search.php" method="post">
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

    <div class="outer-section">
        <div class="inner-section">
            <!-- Faculty Information -->
            <div class="faculty">
                <div class="image">
                    <!--<img src="images/faculty1.jpg" alt="Faculty Image">-->
                    <?php if (!empty($image_url)): ?>
                    <!-- Display image from the direct URL stored in $image_url -->
                    <img src="<?php echo htmlspecialchars($image_url); ?>" alt="Faculty Image">
                    <?php else: ?>
                    <!-- Optionally display a default image if $image_url is empty -->
                    <img src="images/default.jpg" alt="Default Image">
                    <?php endif; ?>
                </div>
                <button class="edit-button" onclick="openModal('change-photo')" style="transform: translate(-170px, 170px);">Change Photo</button>
                <div class="details">
                    <!--<h2>Dr Sudeshna Sarkar</h2>
                    <h3>Affiliation</h3>
                    <p>Department of Computer Science</p>-->
                    <h2><?php echo htmlspecialchars($faculty['title'] . " " . $faculty['first_name'] . " " . $faculty['last_name']); ?></h2>
                <h3>Affiliation</h3>
                <!-- Dynamically insert department name -->
                <p>Department of <?php echo htmlspecialchars($faculty['department_name']); ?>, Banasthali Vidyapith</p>
                <p><?php echo htmlspecialchars($faculty['designation']); ?></p>
                </div>
            </div>    

            <!-- Change Photo Modal -->
            <input type="checkbox" id="modal-change-photo" class="modal-toggle">
            <div class="modal">
                <div class="modal-content">
                    <form method="post" enctype="multipart/form-data">
                    <input type="file" id="new-photo" name="new-photo">
                    <button class="close-button" onclick="closeModal('change-photo')" name="submit-photo">Save Changes</button>
                    </form>
                </div>
            </div>

            <!-- Experties -->
            <div class="bg-section" id="expertise">
                <div class="info">
                    <hr>
                    <h3><i class="fas fa-briefcase"></i>Expertise</h3>
                    <hr>
                    <!--<ul>
                        <li>Computer Science</li>
                        <li>Natural Language Processing</li>
                        <li>Machine Learning</li>
                        <li>Data Mining</li>
                    </ul>-->
                    <ul>
                        <?php foreach ($expertise_array as $expertise): ?>
                        <li><?php echo htmlspecialchars($expertise); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button class="edit-button" onclick="openModal('expertise')">Edit</button> 
                </div>
            </div>

            <!-- Edit Modal for Expertise -->
            <input type="checkbox" id="modal-expertise" class="modal-toggle">
            <div class="modal">
                <div class="modal-content">
                <form method="post">
                <?php for ($i = 0; $i < 6; $i++): ?>
                <!-- Assuming maximum 6 input fields for expertise -->
                <input type="text" id="expertise<?php echo $i + 1; ?>" name="expertise[]" value="<?php echo isset($expertise_array[$i]) ? htmlspecialchars($expertise_array[$i]) : ''; ?>">
                <?php endfor; ?>
                <button class="close-button" type="submit" name="submit-expertise">Save Changes</button>
                </form>
                    
                </div>
            </div>   

            <!-- Personal Information -->
            <div class="bg-section">
                <div class="info">
                    <hr>
                    <h3><i class="fas fa-user"></i>Personal Information</h3>
                    <hr>
                    <!--<h4>Dr Sudeshna Sarkar</h4>
                    <p>Female</p>
                    <p>Department Of Computer Science And Engineering</p>
                    <p>Indian Institute Of Technology Kharagpur</p>
                    <p>West Medinipur, West Bengal, India - 721302</p>-->
                    <h4><?php echo htmlspecialchars($faculty['title'] . ' ' . $faculty['first_name'] . ' ' . $faculty['last_name']); ?></h4>
                    <p><?php echo htmlspecialchars($faculty['gender']); ?></p>
                    <p>Department of <?php echo htmlspecialchars($faculty['department_name']); ?></p>
                    <p>Banasthali Vidyapith</p>
                    <p>Tonk, Rajasthan, India - 304022</p>
                    <button class="edit-button" onclick="openModal('personal')">Edit</button>
                    
                </div>
            </div>
            <!-- Edit Modal for Personal Information -->
            <input type="checkbox" id="modal-personal" class="modal-toggle">
            <div class="modal">
                <div class="modal-content">
                <form method="post">
                    <input type="text" id="title" name="title" placeholder="Title">
                    <input type="text" id="first name" name="first name" placeholder="First Name">
                    <input type="text" id="last name" name="last name" placeholder="Last Name">
                    <input type="text" id="Gender" name="Gender" placeholder="Gender">
                    <input type="text" id="department" name="department" placeholder="Department">
                    <button class="close-button" onclick="closeModal('personal')" name="submit-personal">Save Changes</button>
                </form>
                </div>
            </div> 

            <!-- Experience Information -->
            <div class="bg-section">
                <div class="info">
                    <hr>
                    <h3><i class="fas fa-chart-line"></i>Experience</h3>
                    <hr>
                    <!--<p><strong>1998 - Present</strong></p>
                    <p>Professor</p>
                    <p>Department of Computer Science and Engineering</p>
                    <p>Indian Institute of Technology Kharagpur, Paschim Medinipur</p>-->
                    <p><strong><?php echo htmlspecialchars($faculty['year_of_join']); ?> - Present</strong>
                    <p><?php echo htmlspecialchars($faculty['designation']); ?></p>
                    <p>Department of <?php echo htmlspecialchars($faculty['department_name']); ?></p>
                    <p>Banasthali Vidyapith, Tonk, Rajasthan</p>
                    <button class="edit-button" onclick="openModal('experience')">Edit</button>
                    
                </div>
            </div>

            <!-- Experience Information Modal -->
            <input type="checkbox" id="modal-experience" class="modal-toggle">
            <div class="modal">
                <form method="post">
                <div class="modal-content">
                    <input type="text" id="experience-year" name="experience-year" placeholder="Year">
                    <input type="text" id="experience-position" name="experience-position" placeholder="Position">
                    <button class="close-button" onclick="closeModal('experience')" name="submit-experience">Save Changes</button>
                </form>
                </div>
            </div>
            

            <!-- Qualification Information -->
            <div class="bg-section">
                <div class="info">
                    <hr>
                    <h3><i class="fas fa-graduation-cap"></i>Qualification</h3>
                    <hr>
                    <!--<p><strong>1996</strong></p>
                    <p><strong>Ph.D:</strong> Indian Institute of Technology, Kharagpur</p>-->
                    <p><?php echo htmlspecialchars($faculty['qualification']); ?></p>
                    <button class="edit-button" onclick="openModal('qualification')">Edit</button>
                    
                </div>
            </div>
            <!-- Qualification Information Modal -->
            <input type="checkbox" id="modal-qualification" class="modal-toggle">
            <div class="modal">
                <div class="modal-content">
                <form method="post">
                    <input type="text" id="qualification" name="qualification" placeholder="Qualification">
                    <button class="close-button" onclick="closeModal('qualification')" name="submit-qualification">Save Changes</button>
                </form>
                </div>
            </div>

 

            <!-- Publication section -->
            <div class="bg-section">
                <div class="info">
                    <hr>
                    <h3><i class="fas fa-book"></i> Publications</h3>
                    <hr>
                    <!--<p><strong>Preface</strong></p>
                    <p>Devismes S.;Mandal P.S.;Saradhi V.;Prasad B.;Molla A.R.;Sharma G.</p>
                    <p><strong><br>Three decades of depth-dependent groundwater response to climate variability and human regime in the transboundary Indus-Ganges-Brahmaputra-Meghna mega river basin aquifers</strong></p>
                    <p>Malakar P.;Mukherjee A.;Bhanja S.N.;Ganguly A.R.;Ray R.K.;Zahid A.;Sarkar S.;Saha D.;Chattopadhyay S.</p>-->
                    <?php foreach ($publications as $publication): ?>
                    <p><strong><?php echo htmlspecialchars($publication['publication_title']); ?></strong></p>
                    <p><?php echo htmlspecialchars($publication['publisher']); ?></p>
                    <p>Type: <?php echo htmlspecialchars($publication['publication_type']); ?></p>
                    <p>Year: <?php echo htmlspecialchars($publication['publication_date']); ?></p>
                    <?php if (!empty($publication['publication_link'])): ?>
                    <p>Link: <a href="<?php echo htmlspecialchars($publication['publication_link']); ?>" target="_blank">Read More</a></p>
                    <?php endif; ?>
                    <br>
                    <?php endforeach; ?>
                    <button class="edit-button" onclick="openModal('publication')">Edit</button>
                </div>
            </div>
                 
            <!-- Publication Information Modal -->
            <input type="checkbox" id="modal-publication" class="modal-toggle">
            <div class="modal">
                <div class="modal-content">
                <form method="post">
                    <input type="text" id="publication-title" name="publication-title" placeholder="Title">
                    <input type="text" id="publisher" name="publisher" placeholder="Publisher">
                    <input type="text" id="publication-type" name="publication-type" placeholder="Publication Type">
                    <input type="text" id="publication-date" name="publication-date" placeholder="Publication Year">
                    <input type="text" id="publication-link" name="publication-link" placeholder="Publication Link">
                    <button class="close-button" onclick="closeModal('publication')" name="submit-publication">Save Changes</button>
                </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function openModal(section) {
            const modalId = `modal-${section}`;
            document.getElementById(modalId).checked = true;
        }

        function closeModal(section) {
            const modalId = `modal-${section}`;
            document.getElementById(modalId).checked = false;
        }
    </script>

</body>
</html>