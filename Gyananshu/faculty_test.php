<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty</title>
    <link rel="stylesheet" href="faculty.css">
</head>
<body>
    <!-- Header Section -->
    <nav class="navbar">
        <ul class="nav-list">
            <div class="logo"><img src="images/logog.jpg" alt="logo"></div>
            <li><a href="index.html">HOME</a></li>
            <li><a href="about.html">ABOUT US</a></li>
            <li><a href="login.html">LOGIN</a></li>
            <li><a href="registration.html">REGISTRATION</a></li>
        </ul>
        <div class="rightnav">
            <input type="text" class="search-bar" placeholder="Search">
            <button><svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 1024 1024" height="1.5em"
                width="1.5em" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M909.6 854.5L649.9 594.8C690.2 542.7 712 479 712 412c0-80.2-31.3-155.4-87.9-212.1-56.6-56.7
                  -132-87.9-212.1-87.9s-155.5 31.3-212.1 87.9C143.2 256.5 112 331.8 112 412c0 80.1 31.3 155.5 87.9 212.1C256.5 680.8 331.8 
                  712 412 712c67 0 130.6-21.8 182.7-62l259.7 259.6a8.2 8.2 0 0 0 11.6 0l43.6-43.5a8.2 8.2 0 0 0 0-11.6zM570.4 570.4C528 612.7 
                  471.8 636 412 636s-116-23.3-158.4-65.6C211.3 528 188 471.8 188 412s23.3-116.1 65.6-158.4C296 211.3 352.2 188 412 188s116.1 23.2
                   158.4 65.6S636 352.2 636 412s-23.3 116.1-65.6 158.4z">
                </path>
              </svg></button>
        </div>
    </nav>

    
    <div class="outer-section">
        <div class="inner-section">
            <a href="#" class="edit-button"><i class="fas fa-edit"></i> Edit Page</a>
            <!-------Faculty Information----->

            <div class="faculty">
                <?php
                // Database connection
                $host = "localhost"; // MySQL host
                $username = "root"; // MySQL username
                $password = ""; // MySQL password
                $database = "gyananshu"; // Database name

                $conn = mysqli_connect($host, $username, $password, $database);

                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // Fetch faculty image URL based on faculty ID
                $faculty_id = $_GET['faculty_id']; // Assuming faculty_id is passed via URL
                $sql = "SELECT image_url FROM faculty WHERE faculty_id = $faculty_id";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $image_url = $row['image_url'];
                    echo '<div class="image">';
                    echo '<img src="' . $image_url . '" alt="Faculty Image">';
                    echo '</div>';
                } else {
                    echo '<div class="upload-section">';
                    echo '<h2>Upload Faculty Image</h2>';
                    echo '<form action="upload.php" method="post" enctype="multipart/form-data">';
                    echo '<input type="file" name="file" id="file" accept="image/*">';
                    echo '<input type="submit" value="Upload Image" name="submit">';
                    echo '</form>';
                    echo '</div>';
                }

                mysqli_close($conn);
                ?>

                <div class="details">
                    <h2>Dr Sudeshna Sarkar</h2>
                    <h3>Affiliation</h3>
                    <p>Department of Computer Science</p>
                    <h3>Biography</h3>
                    <p>Dr Sudeshna Sarkar Obtained Ph.D.(Development of Fast VLSI RBSD Pipelined Arithmetic Logic Unit) form UttarPradesh Technical University, 
                        M. Tech. (Digital Systems), B. Tech (EC) Hons from T.I.E.T., Patiala. Gold Medal for Pre-Engg. Examination, GND University has published around 15
                        research papers and articles. Her area of interest is VLSI Design, Computer Architecture. Currently she is Principal, women Enginneering College, 
                        IET Alwar, Rajasthan, India. Member of IEEE, IETE,</p>
                </div>
            </div>

            <!---Experties-->
            <div class="bg-section" id="expertise">
                <div class="info">
                    <hr>
                    <h3><i class="fas fa-briefcase"></i>Expertise</h3>
                    <hr>
                    <ul>
                        <li>Computer Science</li>
                        <li>Natural Language Processing</li>
                        <li>Machine Learning</li>
                        <li>Data Mining</li>
                    </ul>
                </div>
            </div>
            
            <!--Personal Information-->
            <div class="bg-section">
                <div class="info">
                    <hr>
                    <h3><i class="fas fa-user"></i>Personal Information</h3>
                    <hr>
                    <h4>Dr Sudeshna Sarkar</h4>
                    <p>Female</p>
                    <p>Department Of Computer Science And Engineering</p>
                    <p>Indian Institute Of Technology Kharagpur</p>
                    <p>West Medinipur, West Bengal, India - 721302</p>
                </div>
            </div>


            <!-- Experience Information -->
            <div class="bg-section">
                <div class="info">
                    <hr>
                    <h3><i class="fas fa-chart-line"></i>Experience</h3>
                    <hr>
                    <p><strong>1998 - Present</strong>
                    <p>Professor</p>
                    <p>Department of Computer Science and Engineering</p>
                    <p>Indian Institute of Technology Kharagpur, Paschim Medinipur</p>
                </div>
            </div>

            <!-- Qualification Information -->
            <div class="bg-section">
                <div class="info">
                    <hr>
                    <h3><i class="fas fa-graduation-cap"></i>Qualification</h3>
                    <hr>
                    <p><strong>1996</strong></p>
                    <p><strong>Ph.D:</strong> Indian Institute of Technology, Kharagpur</p>
                </div>
            </div>

            <!---publication section--->
            <div class="bg-section">
                <div class="info">
                    <hr>
                    <h3><i class="fas fa-book"></i> Publications</h3>
                    <hr>
                    <p><strong>Preface</strong></p>
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
                    <p>Malakar P.;Mukherjee A.;Bhanja S.N.;Ganguly A.R.;Ray R.K.;Zahid A.;Sarkar S.;Saha D.;Chattopadhyay S.</p>

                </div>
            </div>
        </div>
    </div>
</body>
</html>
