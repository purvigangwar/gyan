<?php include('server.php') ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <form class="login-form" method="post" action="login.php">
        
            <h2>Login</h2>
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
               

            </div>
            <button type="submit" class="btn" name="login_user">Login</button>
            <p class="forgot-password">Forgot your password? <a href="forgot_password.php">Reset it</a>.</p>
            <p>Don't have an account? <a href="reg.php">Register</a></p>
        </form>
    </div>
</body>
</html>