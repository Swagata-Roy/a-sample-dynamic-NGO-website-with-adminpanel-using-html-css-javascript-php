<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'config/db_conn.php';

    // Initialize failed attempts tracking
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }
    if (!isset($_SESSION['last_failed_time'])) {
        $_SESSION['last_failed_time'] = time();
    }

    // Calculate the penalty time
    $attempts = $_SESSION['login_attempts'];
    $penalty_time = 0;

    if ($attempts > 5) {
        $penalty_time = pow(2, $attempts - 6) * 30; // 30s, 1m, 2m, 4m, etc.
        $time_since_last_attempt = time() - $_SESSION['last_failed_time'];

        if ($time_since_last_attempt < $penalty_time) {
            $remaining_time = $penalty_time - $time_since_last_attempt;
            die("Too many failed login attempts. Please wait " . $remaining_time . " seconds before trying again.");
        }
    }

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8'));
    }

    // Sanitize inputs
    $login = trim($conn->real_escape_string($_POST['login']));
    $password = $_POST['password'];

    // Prepare and execute the SQL statement
    $sql = "SELECT id, username, email, password FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Failed to prepare statement: " . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8'));
    }
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check login credentials
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Reset login attempts after successful login
            $_SESSION['login_attempts'] = 0;
            $_SESSION['last_failed_time'] = 0;

            // Set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            header("Location: adminpanel.php");
            exit();
        } else {
            // Increment login attempts on failure
            $_SESSION['login_attempts']++;
            $_SESSION['last_failed_time'] = time();
            $error = "Invalid login credentials";
        }
    } else {
        // Increment login attempts on failure
        $_SESSION['login_attempts']++;
        $_SESSION['last_failed_time'] = time();
        $error = "Invalid login credentials";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bless Foundation</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<div class="login-container fade-in">
    <h1>Login</h1>
    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php } ?>
    <form method="POST" action="">
        <input type="text" name="login" placeholder="Username or Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Log In</button>
    </form>
    <a href="#" id="forgotPassword" class="forgot-password">Forgot Password?</a>
</div>

<div id="forgotPasswordModal" class="modal">
    <div class="modal-content fade-in">
        <span class="close">&times;</span>
        <p>Please contact your system administrator.</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('forgotPasswordModal');
        var forgotPasswordLink = document.querySelector('.forgot-password');
        var closeModal = document.querySelector('.close');

        forgotPasswordLink.onclick = function() {
            modal.style.display = 'block';
            modal.classList.add('fade-in');
        }

        closeModal.onclick = function() {
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    });
</script>
</body>
</html>