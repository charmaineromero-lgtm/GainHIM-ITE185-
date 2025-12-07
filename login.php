<?php
session_start();
require 'config.php'; // database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare query (prevent SQL injection)
    $stmt = $conn->prepare("SELECT id, username, password FROM user_tbl WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if email exists
    if ($stmt->num_rows === 1) {

        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {

            // Login success → store session
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            header("Location: dashboard.php");
            exit;

        } else {
            $error = "Incorrect password.";
        }

    } else {
        $error = "Email not found.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="auth-container">

    <!-- LOGO -->
    <img src="logo-v2.png" class="logo" alt="Case Study Logo">

    <h2>Login</h2>

    <?php if (!empty($error)) : ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['registered'])) : ?>
        <div class="message success">Registration successful! Please log in.</div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>
    </form>

    <div class="auth-link">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
        <!-- Forgot password removed -->
    </div>

</div>

<script>
// pop in
window.onload = () => {
    document.body.classList.add("page-loaded");
};

// pop out when clicking links
document.querySelectorAll("a").forEach(link => {
    if (link.href && !link.href.includes("#")) {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            document.body.classList.add("pop-out");

            setTimeout(() => {
                window.location = this.href;
            }, 300);
        });
    }
});
</script>

</body>
</html>
