<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$name = $_SESSION["full_name"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard - UCF Study Hub</title>
    <link rel="stylesheet" href="/frontend/assets/css/style.css">
    <meta name="description" content="UCF Study Hub helps students upload, browse, search, and manage study notes and academic resources.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<main id="main-content">

<nav class="navbar">
    <div class="logo">UCF Study Hub</div>
    <div class="nav-links">
        <a href="../index.php">Home</a>
        <a href="browse-notes.php">Browse Notes</a>
        <a href="upload-note.php">Upload Note</a>
                    <a href="contacts.php">Contacts</a>
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
                    <a href="contacts.php">Contacts</a>
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

</main>
</body>
</html>
