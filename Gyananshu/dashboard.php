<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;

    

}
include('db.php'); // Replace with the path to your database connection file

    // Fetch the faculty ID based on the session username
    $username = $_SESSION['username'];
    $query = "SELECT faculty_id FROM faculty WHERE first_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($faculty_id);
    $stmt->fetch();
    $stmt->close();
// Include here your database connection if you need to fetch more user data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/contact.css">
    <title>Dashboard - Gyananshu</title>
</head>
<body>
    <nav class="navbar">
        <ul class="nav-list">
            <div class="logo"><img src="images/logog.jpg" alt="Gyananshu Logo">
            </div>
            <li><a href="index.html">HOME</a></li>
            <li><a href="aboutus.html">ABOUT US</a></li>
            <li><a href="faculty.php?faculty_id=<?php echo $faculty_id; ?>">MY PROFILE</a></li>
            <li><a href="logout.php">LOGOUT</a></li>
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
    <section style="background-image: url('images/bg4.png'); background-size: cover; background-position: center; color: white; text-align: center; padding: 100px 20px;">
        <h1>Welcome, Prof. <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <p>Explore your personalized dashboard to connect with the academic community, manage your profile, and access exclusive resources.</p>
        <a href="cont.html" class="btn-contact" style="margin-left: 525px; margin-right: 525px; margin-top: 70px;height: 40px;font-size: 20px">Contact Me</a>
    </section>
</body>