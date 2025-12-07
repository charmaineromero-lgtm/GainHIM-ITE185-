<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) die("No ID.");

$journal_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch the journal
$stmt = $conn->prepare("SELECT * FROM journal WHERE journal_id = ? AND id = ?");
$stmt->bind_param("ii", $journal_id, $user_id);
$stmt->execute();
$journal = $stmt->get_result()->fetch_assoc();

if (!$journal) die("Not found.");

// Delete on confirm
if (isset($_POST['confirm'])) {
    $conn->query("DELETE FROM journal_tags WHERE journal_id=$journal_id");

    $del = $conn->prepare("DELETE FROM journal WHERE journal_id=? AND id=?");
    $del->bind_param("ii", $journal_id, $user_id);
    $del->execute();

    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Delete Entry</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    body {
        margin:0;padding:40px 0;
        background:linear-gradient(to bottom right,#ffbcbc,#ffffff);
        font-family:'Poppins',sans-serif;
    }

    .container {
        width:90%;max-width:600px;background:#fff;
        padding:30px;margin:auto;border-radius:18px;
        box-shadow:0 10px 22px rgba(0,0,0,0.1);text-align:center;
    }

    h2 {font-weight:600;margin-bottom:10px;color:#c62828;}
    p {color:#555;margin-bottom:25px;}

    .btn {
        padding:12px 20px;border:none;border-radius:10px;
        font-size:16px;cursor:pointer;margin:5px;width:45%;
    }

    .delete-btn {background:#ff5252;color:#fff;}
    .delete-btn:hover {background:#e04141;}

    .cancel-btn {background:#ccc;}
    .cancel-btn:hover {background:#b3b3b3;}
</style>
</head>

<body>

<div class="container">

    <h2>Delete Entry?</h2>

    <p><strong><?= htmlspecialchars($journal['title']) ?></strong></p>
    <p>This action cannot be undone.</p>

    <form method="POST">
        <button class="btn delete-btn" name="confirm">Yes, Delete</button>
        <a href="view.php?id=<?= $journal_id ?>" class="btn cancel-btn" style="display:inline-block;text-align:center;line-height:44px;text-decoration:none;color:#333;">Cancel</a>
    </form>

</div>

</body>
</html>
