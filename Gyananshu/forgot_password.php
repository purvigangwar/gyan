<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <!-- Display message if it exists -->
        <?php if(isset($_SESSION['message'])): ?>
            <div class="message">
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']); // Clear the message after displaying it
                ?>
            </div>
        <?php endif; ?>

        <form class="login-form" method="post" action="forgot_password.php">
            <h2>Reset Password</h2>
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" class="btn" name="reset_password">Send Reset Link</button>
        </form>
    </div>
</body>
</html>
