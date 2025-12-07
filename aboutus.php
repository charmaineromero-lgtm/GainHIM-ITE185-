<?php
session_start();
require 'config.php'; // database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<meta charset="UTF-8">
<title>About Us - Gain Him</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
body {margin:0;font-family:'Poppins',sans-serif;background:linear-gradient(to bottom right,#bdeaff,#ffffff);padding:0;}
.top{text-align:center;padding:30px 10px;}
.logo{width:140px;object-fit:contain;}
.welcome{font-size:22px;margin-top:10px;font-weight:600;}
.scripture{color:#444;font-style:italic;margin-top:5px;font-size:15px;}
.menu-bar{text-align:center;margin:20px 0;}
.menu-bar a {
    text-decoration:none;
    color:white;
    background:#4cb1d1;
    padding:10px 20px;
    border-radius:8px;
    font-weight:500;
    transition:0.3s;
    margin-right:10px;
}
.menu-bar a:hover{background:#3ca0bf;}
.container{width:90%;max-width:800px;margin:20px auto;background:white;padding:30px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.1);}
h1{font-size:26px;margin-bottom:15px;}
p{font-size:16px;line-height:1.6;color:#333;}
.home{text-align:center;margin-top:30px;}
.home a{color:#dd4b39;text-decoration:none;font-weight:500;}
.home a:hover{text-decoration:underline;}

body {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
    background: url('bg_casestudy.jpg') no-repeat center center fixed;
    background-size: cover;
}

</style>


</head>

<body>
<div class="top">
    <img src="logo-v2.png" class="logo" alt="Logo">
    <div class="welcome">Welcome, <?= htmlspecialchars($username); ?>!</div>
    <div class="scripture">"Your words were found and I ate them..." — Jeremiah 15:16</div>
</div>

<!-- Menu Bar -->
<div class="menu-bar">
    <a href="dashboard.php">Go Back</a>
</div>

<div class="container">
    <h1>About Gain Him</h1>
    <p>
        <strong>“Gain Him”</strong> is a web application designed to support
        Christians on campus in their pursuit of a deeper relationship with God. Inspired by
        Jeremiah 15:16, the website encourages users to 'eat' the Lord's word by reflecting
        on their enjoyments through writing. This process of journaling helps Christians on
        campus not only find joy in God's word but also gain a richer understanding and
        knowledge concerning the truth.
    </p>



</div>

</body>
</html>
