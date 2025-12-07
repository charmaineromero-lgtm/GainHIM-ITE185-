<?php
session_start();
require 'config.php'; // database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

/* --------------------------
   PARAMETERS
--------------------------- */
$tagFilter = isset($_GET['tag']) ? $_GET['tag'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

/* --------------------------
   FETCH USER JOURNAL ENTRIES
--------------------------- */
$whereClauses = "WHERE j.id = ?";
$params = [$user_id];
$types = "i";

if ($tagFilter) {
    $whereClauses .= " AND t.tag_name = ?";
    $params[] = $tagFilter;
    $types .= "s";
}

if ($search) {
    $whereClauses .= " AND (j.title LIKE ? OR j.content LIKE ?)";
    $likeSearch = "%$search%";
    $params[] = $likeSearch;
    $params[] = $likeSearch;
    $types .= "ss";
}

$journalQuery = $conn->prepare("
    SELECT j.journal_id, j.title, j.content, j.created_at,
           GROUP_CONCAT(t.tag_name SEPARATOR ', ') AS tags
    FROM journal j
    LEFT JOIN journal_tags jt ON j.journal_id = jt.journal_id
    LEFT JOIN tags t ON jt.tag_id = t.tag_id
    $whereClauses
    GROUP BY j.journal_id
    ORDER BY j.created_at DESC
    LIMIT ? OFFSET ?
");

$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$journalQuery->bind_param($types, ...$params);
$journalQuery->execute();
$journals = $journalQuery->get_result();

/* --------------------------
   FETCH STATS
--------------------------- */
$stats_total_entries = $journals->num_rows;

$tagCountQuery = $conn->prepare("
    SELECT COUNT(DISTINCT tag_id) AS tag_count
    FROM journal_tags jt
    JOIN journal j ON jt.journal_id = j.journal_id
    WHERE j.id = ?
");
$tagCountQuery->bind_param("i", $user_id);
$tagCountQuery->execute();
$stats_total_tags = $tagCountQuery->get_result()->fetch_assoc()['tag_count'];

/* --------------------------
   FETCH TOP TAGS
--------------------------- */
$topTagsQuery = $conn->prepare("
    SELECT t.tag_name, COUNT(jt.tag_id) AS usage_count
    FROM tags t
    JOIN journal_tags jt ON t.tag_id = jt.tag_id
    JOIN journal j ON jt.journal_id = j.journal_id
    WHERE j.id = ?
    GROUP BY t.tag_id
    ORDER BY usage_count DESC
    LIMIT 5
");
$topTagsQuery->bind_param("i", $user_id);
$topTagsQuery->execute();
$topTags = $topTagsQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Gain Him Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
body {margin:0;font-family:'Poppins',sans-serif;background:linear-gradient(to bottom right,#bdeaff,#ffffff);padding:0;}
.top{text-align:center;padding:30px 10px;}
.logo{width:140px;object-fit:contain;}
.welcome{font-size:22px;margin-top:10px;font-weight:600;}
.scripture{color: #e9e9dbff;;font-style:italic;margin-top:5px;font-size:15px;}
.container{width:90%;max-width:1000px;margin:20px auto;}
.stats{display:flex;justify-content:space-between;gap:20px;margin-bottom:30px;}
.stat-box{background:white;flex:1;padding:20px;border-radius:12px;text-align:center;box-shadow:0px 4px 15px rgba(0,0,0,0.1);}
.stat-title{font-size:14px;color:#666;}
.stat-num{font-size:26px;font-weight:bold;margin-top:5px;}
.add-btn{background:#4cb1d1;color:white;padding:12px 20px;display:inline-block;border-radius:10px;margin-bottom:20px;text-decoration:none;font-weight:500;}
.add-btn:hover{background:#3ca0bf;}
.journal-card{background:white;padding:20px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.1);margin-bottom:20px;}
.journal-title{font-size:20px;font-weight:600;}
.journal-meta{font-size:13px;color:#777;margin:5px 0;}
.tag{display:inline-block;background:#e2f4ff;color:#0077a3;padding:4px 10px;border-radius:8px;font-size:12px;margin-right:5px;}
.actions a{margin-right:10px;text-decoration:none;color:#2266aa;font-weight:500;}
.actions a:hover{text-decoration:underline;}
.logout{display:block;text-align:center;margin-top:40px;color:#dd4b39;font-weight:500;text-decoration:none;}
.logout:hover{text-decoration:underline;}
form{margin-bottom:20px;text-align:center;}
input[type=text]{padding:8px;width:90%;border-radius:8px;border:1px solid #ccc;}
button{padding:8px 16px;border-radius:8px;background:#4cb1d1;color:white;border:none;cursor:pointer;}
button:hover{background:#3ca0bf;}

.menu-bar a {
    text-decoration: none;
    color: white;
    background: #4cb1d1;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 500;
    transition: 0.3s;
}

.menu-bar a:hover {
    background: #3ca0bf;
}
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    padding: 0;
    background: url('bg_casestudy.jpg') no-repeat center center fixed;
    background-size: cover;
    position: relative;
    z-index: 0;
}

body::before {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(87, 128, 133, 0.3); /* adjust opacity here (0.3 = 30% transparent black) */
    z-index: -1;
}

</style>
</head>

<body>
<div class="top">
    <img src="logo-v2.png" class="logo" alt="Logo">
    <div class="welcome">Welcome, <?= htmlspecialchars($username); ?>!</div>
    <div class="scripture">"Your words were found and I ate them..." — Jeremiah 15:16</div>
</div>

<div class="container">

    <!-- Search -->
    <form method="GET">
        <input type="text" name="search" placeholder="Search journals..." value="<?= htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Stats Section -->
    <div class="stats">
        <div class="stat-box">
            <div class="stat-title">Total Journal Entries</div>
            <div class="stat-num"><?= $stats_total_entries; ?></div>
        </div>
        <div class="stat-box">
            <div class="stat-title">Unique Tags Used</div>
            <div class="stat-num"><?= $stats_total_tags; ?></div>
        </div>
    </div>

    <!-- Top Tags -->
    <?php if ($topTags->num_rows): ?>
        <div style="margin-bottom:20px;">
            <strong>Top Tags:</strong>
            <?php while($tag = $topTags->fetch_assoc()): ?>
                <span class="tag"><a href="?tag=<?= urlencode($tag['tag_name']); ?>"><?= htmlspecialchars($tag['tag_name']); ?> (<?= $tag['usage_count']; ?>)</a></span>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <!-- Add Entry Button -->
    <a href="add_journal.php" class="add-btn">+ Add New Journal</a>

    <!-- Journal List -->
    <?php if ($journals->num_rows === 0): ?>
        <p>No journal entries found.</p>
    <?php else: ?>
        <?php while ($j = $journals->fetch_assoc()): ?>
            <div class="journal-card">
                <div class="journal-title"><?= htmlspecialchars($j['title']); ?></div>
                <div class="journal-meta">Created: <?= $j['created_at']; ?></div>

                <?php if ($j['tags']): ?>
                    <div>
                        <?php foreach (explode(",", $j['tags']) as $tag): ?>
                            <span class="tag"><a href="?tag=<?= urlencode(trim($tag)); ?>"><?= htmlspecialchars(trim($tag)); ?></a></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="actions">
                    <a href="view.php?id=<?= $j['journal_id']; ?>">View</a>
                    <a href="edit.php?id=<?= $j['journal_id']; ?>">Edit</a>
                    <a href="delete.php?id=<?= $j['journal_id']; ?>">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

</div>
<!-- Menu Bar -->
<div class="menu-bar" style="text-align:center;margin:20px 0;">
    <a href="aboutus.php" style="margin-right:10px;">About Us</a>
    <a href="logout.php">Logout</a>
</div>

</body>
</html>
