<?php
// Check if faculty_id is set in the URL query parameters and is not empty
/*if(isset($_GET['faculty_id']) && !empty($_GET['faculty_id'])) {
    $faculty_id = htmlspecialchars($_GET['faculty_id']); // Sanitize the input
} else {
    // Handle the case where faculty_id is not provided or is empty
    die("Faculty ID not provided.");

}*/

include 'db.php'; // Include your database connection

// Assuming you have the faculty ID
$faculty_id = $_GET['faculty_id'] ?? ''; // Replace with a valid default or error handling as needed

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
} else {
    echo "No publications found.";
    // Handle case where no publications are found
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
    $image_url = "images/default.jpg"; // Path to a default image
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty</title>
    <link rel="stylesheet" href="faculty.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Header Section -->
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

    
    <div class="outer-section">
        <div class="inner-section">
            <!--<a href="#" class="edit-button"><i class="fas fa-edit"></i> Edit Profile</a>-->
            <a href="edit.html?faculty_id=<?php echo $faculty_id; ?>" class="edit-button">Edit Profile</a>


            <!-------Faculty Information----->

            <div class="faculty">

               <!-- <div class="image">-->
                    <!--<img src="images/faculty1.jpg" alt="Faculty Image">-->
                    <!--
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                    Select image to upload:
                    <input type="file" name="facultyImage" id="facultyImage">
                    <input type="hidden" name="facultyId" value="<?php echo $faculty_id; ?>">
                    <input type="submit" value="Upload Image" name="submit">
                    </form>-->
                    <!--<?php if (!empty($image_url)): ?>
                    <img src="<?php echo htmlspecialchars($direct_image_url); ?>" alt="Faculty Image">
                    <?php else: ?>-->
                    <!-- Optionally display a default image if $image_url is empty -->
                    <!--<img src="images/default.jpg" alt="Default Image">
                    
                    <?php endif; ?>
                </div>--->
                <div class="image">
    <?php if (!empty($image_url)): ?>
    <!-- Display image from the direct URL stored in $image_url -->
    <img src="<?php echo htmlspecialchars($image_url); ?>" alt="Faculty Image">
    <?php else: ?>
    <!-- Optionally display a default image if $image_url is empty -->
    <img src="images/default.jpg" alt="Default Image">
    <?php endif; ?>
</div>

                <div class="details">
                   <!-- <h2>Dr Sudeshna Sarkar</h2>
                    <h3>Affiliation</h3>
                    <p>Department of Computer Science</p>
                    <h3>Biography</h3>
                    <p>Dr Sudeshna Sarkar Obtained Ph.D.(Development of Fast VLSI RBSD Pipelined Arithmetic Logic Unit) form UttarPradesh Technical University, 
                        M. Tech. (Digital Systems), B. Tech (EC) Hons from T.I.E.T., Patiala. Gold Medal for Pre-Engg. Examination, GND University has published around 15
                        research papers and articles. Her area of interest is VLSI Design, Computer Architecture. Currently she is Principal, women Enginneering College, 
                        IET Alwar, Rajasthan, India. Member of IEEE, IETE,</p>-->
                <h2><?php echo htmlspecialchars($faculty['title'] . " " . $faculty['first_name'] . " " . $faculty['last_name']); ?></h2>
                <h3>Affiliation</h3>
                <!-- Dynamically insert department name -->
                <p>Department of <?php echo htmlspecialchars($faculty['department_name']); ?>, Banasthali Vidyapith</p>
                <p><?php echo htmlspecialchars($faculty['designation']); ?></p>
                <!--<h3>Biography</h3>-->
                <!-- Dynamically insert biography, qualification, and expertise -->
                <!--<p><?php 
                    echo 
                        "Qualification: " . htmlspecialchars($faculty['qualification']) . "<br>" . 
                        "Expertise: " . htmlspecialchars($faculty['expertise']); 
                ?></p>-->
                </div>
                <!-- Dynamically insert faculty name -->
                
            </div>

            <!-- Table for Sections -->
            <!--<div class="container">
                <table class="section-table" >
                    <tr>
                        <th><a href="#expertise" class="active navlink"></a>
                            <i class="fas fa-briefcase"></i> Expertise</th>
                    </tr>
                    <tr>
                        <th><i class="fas fa-user"></i> Personal Information</th>
                    </tr>
                    <tr>
                        <th><i class="fas fa-chart-line"></i> Experience</th>
                    </tr>
                    <tr>
                        <th><i class="fas fa-graduation-cap"></i> Qualification</th>
                    </tr>
                    <tr>
                        <th><i class="fas fa-book"></i> Publications</th>
                    </tr>
                </table>
            </div>-->     


            <!---Experties-->
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
                </div>
            </div>
            
            <!--Personal Information-->
            <div class="bg-section">
                <div class="info">
                    <hr>
                    <h3><i class="fas fa-user"></i>Personal Information</h3>
                    <hr>
                    <!--<h4>Dr Sudeshna Sarkar</h4>
                    <p>Female</p>
                    <p>Department Of Computer Science And Engineering</p>
                    -->
                    <h4><?php echo htmlspecialchars($faculty['title'] . ' ' . $faculty['first_name'] . ' ' . $faculty['last_name']); ?></h4>
                    <p><?php echo htmlspecialchars($faculty['gender']); ?></p>
                    <p>Department of <?php echo htmlspecialchars($faculty['department_name']); ?></p>
                    <p>Banasthali Vidyapith</p>
                    <p>Tonk, Rajasthan, India - 304022</p>
                </div>
            </div>


            <!-- Experience Information -->
            <div class="bg-section">
                <div class="info">
                    <hr>
                    <h3><i class="fas fa-chart-line"></i>Experience</h3>
                    <hr>
                    <p><strong><?php echo htmlspecialchars($faculty['year_of_join']); ?> - Present</strong>
                    <p><?php echo htmlspecialchars($faculty['designation']); ?></p>
                    <p>Department of <?php echo htmlspecialchars($faculty['department_name']); ?></p>
                    <p>Banasthali Vidyapith, Tonk, Rajasthan</p>
                </div>
            </div>

            <!-- Qualification Information -->
            <div class="bg-section">
                <div class="info">
                    <hr>
                    <h3><i class="fas fa-graduation-cap"></i>Qualification</h3>
                    <hr>
                    <!--<p><strong>1996</strong></p>-->
                    <p><?php echo htmlspecialchars($faculty['qualification']); ?></p>
                </div>
            </div>

            <!---publication section--->
            <div class="bg-section">
                <div class="info">
                    <hr>
                    <h3><i class="fas fa-book"></i> Publications</h3>
                    <hr>
                    <!--<p><strong>Preface</strong></p>
                    <p>Devismes S.;Mandal P.S.;Saradhi V.;Prasad B.;Molla A.R.;Sharma G.</p>

                    <p><strong><br>Finding hierarchy of clusters</strong></p>
                    <p>Pal S.S.;Mukhopadhyay J.;Sarkar S.</p>

                    <p><strong><br>Delineating Variabilities of Groundwater Level Prediction Across the Agriculturally Intensive Transboundary Aquifers of South Asia</strong></p>
                    <p>Malakar P.;Bhanja S.N.;Dash A.A.;Saha D.;Ray R.K.;Sarkar S.;Zahid A.;Mukherjee A.</p>

                    <p><strong><br>Meta-ED: Cross-lingual Event Detection Using Meta-learning for Indian Languages</strong></p>
                    <p>Roy A.;Sharma I.;Sarkar S.;Goyal P.</p>

                    <p><strong><br>Dysarthric Speech Recognition using Depthwise Separable Convolutions: Preliminary Study</strong></p>
                    <p>Shahamiri S.R.;Mandal K.;Sarkar S.</p>

                    <p><strong><br>MorphBen: A Neural Morphological Analyzer for Bengali Language</strong></p>
                    <p>Das A.;Sarkar S.</p>

                    <p><strong><br>An Approach to Battery Pack Balancing Control Optimizing the Usable Capacity of the Battery Pack</strong></p>
                    <p>Mukherjee N.;Sarkar S.</p>

                    <p><strong><br>Estimating Cluster numbers in Landsat-8 images using Stability Analysis</strong></p>
                    <p>Chatterjee A.;Pal S.S.;Mukhopadhyay J.;Das P.P.;Sarkar S.</p>

                    <p><strong><br>Deep Learning-Based Forecasting of Groundwater Level Trends in India: Implications for Crop Production and Drinking Water Supply</strong></p>
                    <p>Malakar P.;Mukherjee A.;Bhanja S.N.;Sarkar S.;Saha D.;Ray R.K.</p>

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

                </div>
            </div>



        </div>
        </div>
    </div>
    


</body>
</html>
