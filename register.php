<?php
session_start();
require 'config.php'; // database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Basic validation
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM user_tbl WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $conn->prepare("INSERT INTO user_tbl (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                header("Location: login.php?registered=1");
                exit;
            } else {
                $error = "Error creating account.";
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="auth-container">

    <!-- LOGO -->
    <img src="logo-v2.png" class="logo" alt="Case Study Logo">

    <h2>Create Account</h2>

    <?php if (!empty($error)) : ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <input type="password" name="confirm_password" placeholder="Confirm Password" required>

        <button type="submit">Register</button>
    </form>

    <div class="auth-link">
        <p>Already have an account? <a href="login.php">Login here</a></p>
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
