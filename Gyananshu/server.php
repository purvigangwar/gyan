<?php
session_start();

// Initialize variables
$errors = array(); 

// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'gyananshu');

// REGISTER FACULTY
if (isset($_POST['reg_user'])) {
  // Receive all input values from the form
  $title = mysqli_real_escape_string($db, $_POST['title']);
  $firstName = mysqli_real_escape_string($db, $_POST['firstName']);
  $lastName = mysqli_real_escape_string($db, $_POST['lastName']);
  $dob = mysqli_real_escape_string($db, $_POST['dob']);
  $gender = mysqli_real_escape_string($db, $_POST['gender']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $departmentName = mysqli_real_escape_string($db, $_POST['department']);
  $designation = mysqli_real_escape_string($db, $_POST['designation']);
  $yearOfJoining = mysqli_real_escape_string($db, $_POST['yearJoining']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password']);
  $password_2 = mysqli_real_escape_string($db, $_POST['confirmPassword']);

  // Form validation
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
    array_push($errors, "The two passwords do not match");
  }

  // Check the database to make sure a faculty member does not already exist with the same email
  $faculty_check_query = "SELECT * FROM faculty WHERE faculty_email='$email' LIMIT 1";
  $result = mysqli_query($db, $faculty_check_query);
  $faculty = mysqli_fetch_assoc($result);
  
  if ($faculty) {
    if ($faculty['faculty_email'] === $email) {
      array_push($errors, "Email already exists");
    }
  }
  $department_query = "SELECT department_id FROM department WHERE department_name = '$departmentName' LIMIT 1";
  $department_result = mysqli_query($db, $department_query);
  $department = mysqli_fetch_assoc($department_result);
  
  if ($department) {
    $departmentId = $department['department_id'];
  } else {
    array_push($errors, "Department not found");
  }

  // Register faculty if there are no errors in the form
  if (count($errors) == 0) {
    //$password = md5($password_1); // Encrypt the password before saving in the database
    $password = password_hash($password_1, PASSWORD_DEFAULT);

    $query = "INSERT INTO faculty (password_faculty, department_id, title, first_name, last_name, date_of_birth, gender, faculty_email, designation,  year_of_join) 
          VALUES('$password', '$departmentId', '$title', '$firstName', '$lastName', '$dob', '$gender', '$email', '$designation', '$yearOfJoining')";
    mysqli_query($db, $query);
    $_SESSION['username'] = $firstName; // Adjust according to what you want to use as the session username
    $_SESSION['success'] = "You are now registered";
    header('location: login.php'); // Adjust if necessary to redirect to a different page
  }
}
if (isset($_POST['login_user'])) {
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($email)) {
    array_push($errors, "Email is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
    $query = "SELECT * FROM faculty WHERE faculty_email='$email' LIMIT 1";
    $results = mysqli_query($db, $query);
    if ($row = mysqli_fetch_assoc($results)) {
      if (password_verify($password, $row['password_faculty'])) {
        // Password matches, set the session
        $_SESSION['username'] = $row['first_name']; // Adjust according to your needs
        $_SESSION['success'] = "You are now logged in";
        header('location: dashboard.php'); // Adjust if necessary to redirect to a different page
      } else {
        // Password does not match
        array_push($errors, "email/password combination");
      }
    } else {
      // No user found
      array_push($errors, "No user found with that email");
    }
  }
  // Check if reset_password button is clicked
if (isset($_POST['reset_password'])) {
  $email = $_POST['email'];
  // Validate email and check in the database
  $query = "SELECT * FROM faculty WHERE email='$email'";
  $results = mysqli_query($db, $query);
  if (mysqli_num_rows($results) <= 0) {
      echo "No user found with that email.";
  } else {
      $token = bin2hex(random_bytes(50)); // Generate secure token
      $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expiry time
      // Store token and expiry in the database
      $updateQuery = "UPDATE faculty SET reset_token='$token', token_expiry='$expiry' WHERE email='$email'";
      mysqli_query($db, $updateQuery);
      // Send email with reset link
      $to = $email;
      $subject = "Reset your password on Gyananshu";
      $msg = "Hi there, click on this <a href=\"gyananshu.com/reset_password.php?token=" . $token . "\">link</a> to reset your password on our site";
      $msg = wordwrap($msg,70);
      $headers = "From: priyanshimishra10102004@gmail.com";
      mail($to, $subject, $msg, $headers);
      echo "Check your email for the reset link.";
  }
}

}

?>
