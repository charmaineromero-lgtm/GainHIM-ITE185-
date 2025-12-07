<?php
session_start();
require 'config.php'; // database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* --------------------------
   FETCH ALL TAGS
   -------------------------- */
$tagQuery = $conn->query("SELECT * FROM tags ORDER BY tag_name ASC");

/* --------------------------
   PROCESS FORM SUBMISSION
   -------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $selectedTags = $_POST['tags'] ?? [];

    // Insert into journal
    $stmt = $conn->prepare("INSERT INTO journal (id, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $title, $content);
    $stmt->execute();

    $journal_id = $stmt->insert_id;

    // Insert tags
    if (!empty($selectedTags)) {
        foreach ($selectedTags as $tag_id) {
            $tagStmt = $conn->prepare("INSERT INTO journal_tags (journal_id, tag_id) VALUES (?, ?)");
            $tagStmt->bind_param("ii", $journal_id, $tag_id);
            $tagStmt->execute();
        }
    }

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Journal Entry</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(to bottom right, #bdeaff, #ffffff);
        padding: 40px 0;
    }

    
    .container {
        width: 90%;
        max-width: 800px;
        margin: auto;
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    }

    h2 {
        text-align: center;
        font-weight: 600;
        margin-bottom: 20px;
    }

    label {
        font-weight: 500;
        display: block;
        margin-top: 15px;
    }

    input[type="text"],
    textarea,
    select {
        width: 100%;
        padding: 12px;
        margin-top: 6px;
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 15px;
        font-family: inherit;
    }

    textarea {
        height: 200px;
        resize: vertical;
    }

    .tag-select {
        height: 120px;
    }

    .btn-submit {
        background: #4cb1d1;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        margin-top: 20px;
        font-size: 16px;
        font-weight: 500;
        width: 100%;
        transition: 0.2s;
    }

    .btn-submit:hover {
        background: #3ca0bf;
    }

    .back-link {
        text-align: center;
        display: block;
        margin-top: 20px;
        color: #2266aa;
        text-decoration: none;
    }

    .back-link:hover {
        text-decoration: underline;
    }
</style>
</head>

<body>

<div class="container">

    <h2>Write a New Journal Entry</h2>

    <form method="POST">

        <label>Title</label>
        <input type="text" name="title" placeholder="Enter a title..." required>

        <label>Your Enjoyment</label>
        <textarea name="content" placeholder="What did you enjoy from the Lord today?" required></textarea>

        <label>Tags (optional)</label>
        <select name="tags[]" class="tag-select" multiple>
            <?php while ($tag = $tagQuery->fetch_assoc()): ?>
                <option value="<?= $tag['tag_id']; ?>"><?= $tag['tag_name']; ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit" class="btn-submit">Save Entry</button>

    </form>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>

</div>

</body>
</html>
