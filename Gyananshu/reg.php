<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
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
    </nav>
    </body>
</html>
<?php include('server.php') ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" type="text/css" href="reg.css">
</head>
<body>
    <form class="registration-form" action="reg.php" method="post">
        <h1> Registration Form<br><br></h1>
        <!-- Google Login Button -->
       <div class="form-group">
          <button id="googleLogin" type="button">Login with Google</button>
        </div>

<!-- LinkedIn Login Button -->
       <div class="form-group">
          <button id="linkedinLogin" type="button">Login with LinkedIn</button>
       </div>
       <div class="or-divider">
        <span>OR</span>
    </div>
        
    <div class="form-group">
        <label for="title">Title:</label>
        <select id="title" name="title">
            <option value="Mr">Mr.</option>
            <option value="Ms">Ms.</option>
            <option value="Mrs">Mrs.</option>
            <option value="Dr">Dr.</option>
        </select>

        <div class="form-group">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" required>
        </div>
        <div class="form-group">
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" required>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required>
        </div>
        <div class="form-group">
            <label for="gender">Select Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
        </div>
        <div class="form-group">
            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required><br>
            <!--
                <input type="password" id="password" name="password" minlength="4" required>
            <input type="password" id="confirmPassword" name="confirmPassword" minlength="4" required>
            -->
        </div>
        <div class="form-group">
            <label for="department">Department:</label>
            <input type="text" id="department" name="department" required>
        </div>
        <div class="form-group">
            <label for="designation">Designation:</label>
            <input type="text" id="designation" name="designation" required>
        </div>
       
        <div class="form-group">
            <label for="yearJoining">Year of Joining:</label>
            <input type="number" id="yearJoining" name="yearJoining" required>
        </div>
        <button type="submit" name="reg_user">Submit</button>
    </form>
</body>
</html>


