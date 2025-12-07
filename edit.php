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

// Fetch journal
$stmt = $conn->prepare("SELECT * FROM journal WHERE journal_id = ? AND id = ?");
$stmt->bind_param("ii", $journal_id, $user_id);
$stmt->execute();
$journal = $stmt->get_result()->fetch_assoc();

if (!$journal) die("Not found.");

// Fetch all tags
$allTags = $conn->query("SELECT * FROM tags ORDER BY tag_name ASC");

// Fetch selected tags
$tagStmt = $conn->prepare("SELECT tag_id FROM journal_tags WHERE journal_id = ?");
$tagStmt->bind_param("i", $journal_id);
$tagStmt->execute();
$res = $tagStmt->get_result();

$existingTags = [];
while ($t = $res->fetch_assoc()) $existingTags[] = $t['tag_id'];

// Update on submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $tags = $_POST['tags'] ?? [];

    $update = $conn->prepare("UPDATE journal SET title=?, content=? WHERE journal_id=? AND id=?");
    $update->bind_param("ssii", $title, $content, $journal_id, $user_id);
    $update->execute();

    // Reset tags
    $conn->query("DELETE FROM journal_tags WHERE journal_id=$journal_id");

    foreach ($tags as $tag_id) {
        $ins = $conn->prepare("INSERT INTO journal_tags (journal_id, tag_id) VALUES (?,?)");
        $ins->bind_param("ii", $journal_id, $tag_id);
        $ins->execute();
    }

    header("Location: view.php?id=$journal_id");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Entry</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    body {
        margin:0;
        padding:40px 0;
        background:linear-gradient(to bottom right,#bdeaff,#ffffff);
        font-family:'Poppins',sans-serif;
    }
    .container {
        width:90%;
        max-width:800px;
        background:#fff;
        padding:30px;
        margin:auto;
        border-radius:18px;
        box-shadow:0 10px 22px rgba(0,0,0,0.1);
    }

    h2 {text-align:center;font-weight:600;margin-bottom:20px;}

    input,textarea,select {
        width:100%;padding:12px;border:1px solid #ccc;
        border-radius:10px;margin-top:5px;font-size:15px;
        font-family:inherit;
    }

    textarea {height:200px;}

    .btn {
        width:100%;padding:12px;margin-top:20px;border:none;
        border-radius:10px;font-size:16px;font-weight:500;
        cursor:pointer;transition:.2s;
    }

    .save-btn {background:#4cb1d1;color:#fff;}
    .save-btn:hover {background:#3ba1c0;}

    .cancel {
        text-align:center;margin-top:15px;display:block;
        color:#2266aa;text-decoration:none;
    }
</style>
</head>

<body>

<div class="container">
<h2>Edit Journal Entry</h2>

<form method="POST">

<label>Title</label>
<input type="text" name="title" value="<?= htmlspecialchars($journal['title']) ?>" required>

<label>Your Enjoyment</label>
<textarea name="content" required><?= htmlspecialchars($journal['content']) ?></textarea>

<label>Tags</label>
<select name="tags[]" multiple style="height:130px;">
    <?php while ($tag = $allTags->fetch_assoc()): ?>
        <option value="<?= $tag['tag_id'] ?>"
            <?= in_array($tag['tag_id'], $existingTags) ? 'selected' : '' ?>>
            <?= $tag['tag_name'] ?>
        </option>
    <?php endwhile; ?>
</select>

<button class="btn save-btn">Save Changes</button>

</form>

<a href="dashboard.php?id=<?= $journal_id ?>" class="cancel">← Cancel</a>

</div>

</body>
</html>
