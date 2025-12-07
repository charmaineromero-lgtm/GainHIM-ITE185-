<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("No journal ID provided.");
}

$journal_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch journal
$stmt = $conn->prepare("SELECT * FROM journal WHERE journal_id = ? AND id = ?");
$stmt->bind_param("ii", $journal_id, $user_id);
$stmt->execute();
$journal = $stmt->get_result()->fetch_assoc();

if (!$journal) die("Journal entry not found.");

// Fetch tags
$tagStmt = $conn->prepare("
    SELECT t.tag_name 
    FROM tags t
    JOIN journal_tags jt ON t.tag_id = jt.tag_id
    WHERE jt.journal_id = ?
");
$tagStmt->bind_param("i", $journal_id);
$tagStmt->execute();
$tags = $tagStmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($journal['title']) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    body {
        margin: 0;
        padding: 40px 0;
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(to bottom right, #bdeaff, #ffffff);
    }

    .container {
        width: 90%;
        max-width: 800px;
        background: #fff;
        padding: 30px;
        margin: auto;
        border-radius: 18px;
        box-shadow: 0 10px 22px rgba(0,0,0,0.1);
    }

    h2 {
        font-weight: 600;
        margin-bottom: 10px;
        text-align: center;
    }

    .date {
        text-align: center;
        color: #777;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .content-box {
        background: #f7fbff;
        padding: 20px;
        border-radius: 14px;
        line-height: 1.7;
        font-size: 16px;
        color: #333;
        white-space: pre-wrap;
    }

    .tags {
        margin-top: 20px;
    }

    .tag {
        display: inline-block;
        padding: 6px 12px;
        background: #dff2ff;
        border-radius: 12px;
        margin-right: 6px;
        margin-bottom: 6px;
        color: #005f96;
        font-size: 14px;
        font-weight: 500;
    }

    .actions {
        margin-top: 25px;
        text-align: center;
    }

    .btn {
        padding: 10px 18px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 15px;
        margin: 5px;
        display: inline-block;
        transition: 0.2s;
    }

    .edit-btn {
        background: #4db7ff;
        color: white;
    }
    .edit-btn:hover {
        background: #3aa3e8;
    }

    .delete-btn {
        background: #ff5252;
        color: white;
    }
    .delete-btn:hover {
        background: #e04141;
    }

    .back-btn {
        background: #ccc;
        color: #333;
    }
    .back-btn:hover {
        background: #b7b7b7;
    }
</style>
</head>

<body>

<div class="container">
    <h2><?= htmlspecialchars($journal['title']) ?></h2>
    <div class="date"><?= $journal['created_at'] ?></div>

    <div class="content-box">
        <?= nl2br(htmlspecialchars($journal['content'])) ?>
    </div>

    <div class="tags">
        <?php while ($tag = $tags->fetch_assoc()): ?>
            <span class="tag"><?= htmlspecialchars($tag['tag_name']) ?></span>
        <?php endwhile; ?>
    </div>

    <div class="actions">
        <a href="edit.php?id=<?= $journal_id ?>" class="btn edit-btn">Edit</a>
        <a href="delete.php?id=<?= $journal_id ?>" class="btn delete-btn">Delete</a>
        <a href="dashboard.php" class="btn back-btn">Back</a>
    </div>

</div>

</body>
</html>
