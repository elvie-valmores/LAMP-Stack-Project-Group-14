<?php
session_start();
require_once __DIR__ . "/../../backend/config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$search = "";
$result = null;

if (isset($_GET["search"]) && trim($_GET["search"]) !== "") {
    $search = trim($_GET["search"]);
    $keyword = "%" . $search . "%";

    $stmt = $conn->prepare("
        SELECT notes.*, users.full_name
        FROM notes
        JOIN users ON notes.user_id = users.id
        WHERE notes.title LIKE ? OR notes.course LIKE ?
        ORDER BY notes.uploaded_at DESC
    ");

    $stmt->bind_param("ss", $keyword, $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Browse Notes - UCF Study Hub</title>
    <link rel="stylesheet" href="/frontend/assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">UCF Study Hub</div>

    <div class="nav-links">
        <a href="/frontend/index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="upload-note.php">Upload Note</a>
        <a href="profile.php">Profile</a>
        <a href="../../backend/logout.php">Logout</a>
    </div>
</nav>

<section class="dashboard">
    <h1>Browse Notes</h1>
    <p>Search and download shared study materials.</p>

    <form method="GET" class="auth-form" style="max-width: 100%; margin-bottom: 30px;">
        <input 
            type="text" 
            name="search" 
            placeholder="Search by title or course"
            value="<?php echo htmlspecialchars($search); ?>"
        >

        <button type="submit">Search</button>
    </form>

    <div class="features">

        <?php if ($result === null): ?>

            <div class="feature-card">
                <h3>Search for Notes</h3>
                <p>Enter a note title or course name to find study materials.</p>
            </div>

        <?php elseif ($result->num_rows > 0): ?>

            <?php while ($note = $result->fetch_assoc()): ?>

                <div class="feature-card">
                    <h3><?php echo htmlspecialchars($note["title"]); ?></h3>

                    <p>
                        <strong>Course:</strong>
                        <?php echo htmlspecialchars($note["course"]); ?>
                    </p>

                    <p>
                        <?php echo htmlspecialchars($note["description"]); ?>
                    </p>

                    <p>
                        <strong>Uploaded by:</strong>
                        <?php echo htmlspecialchars($note["full_name"]); ?>
                    </p>

                    <a class="btn primary" href="/<?php echo htmlspecialchars($note["file_path"]); ?>" download>
                        Download
                    </a>
                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="feature-card">
                <h3>No Notes Found</h3>
                <p>Try searching with another title or course name.</p>
            </div>

        <?php endif; ?>

    </div>
</section>

</body>
</html>
