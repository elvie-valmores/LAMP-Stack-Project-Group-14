<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$name = $_SESSION["full_name"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - UCF Study Hub</title>
    <link rel="stylesheet" href="/frontend/assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">UCF Study Hub</div>

    <div class="nav-links">
        <a href="/frontend/index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="browse-notes.php">Browse Notes</a>
        <a href="upload-note.php">Upload Note</a>
        <a href="my-notes.php">My Notes</a>
        <a href="profile.php">Profile</a>
        <a href="../../backend/logout.php">Logout</a>
    </div>
</nav>

<section class="dashboard">
    <h1>Welcome, <?php echo htmlspecialchars($name); ?> 👋</h1>
    <p>Your student study dashboard is ready.</p>

    <div class="features">
        <div class="feature-card">
            <h3>Upload Notes</h3>
            <p>Add new study materials for other students.</p>
            <a href="upload-note.php" class="btn primary">Upload</a>
        </div>

        <div class="feature-card">
            <h3>Browse Notes</h3>
            <p>Search and view shared course materials.</p>
            <a href="browse-notes.php" class="btn secondary">Browse</a>
        </div>

        <div class="feature-card">
            <h3>My Profile</h3>
            <p>View your account and uploaded resources.</p>
            <a href="profile.php" class="btn secondary">Profile</a>
        </div>
    </div>
</section>

</body>
</html>
