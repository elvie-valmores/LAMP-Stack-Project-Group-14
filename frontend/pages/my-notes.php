<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Notes - UCF Study Hub</title>
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
        <a href="profile.php">Profile</a>
        <a href="../../backend/logout.php">Logout</a>
    </div>
</nav>

<section class="dashboard">
    <h1>My Notes</h1>
    <p>View, edit, and delete your uploaded study notes.</p>

    <input type="hidden" id="userId" value="<?php echo $user_id; ?>">

    <div id="myNotesContainer" class="features">
        <div class="feature-card">
            <h3>Loading...</h3>
            <p>Please wait while your notes load.</p>
        </div>
    </div>
</section>

<script src="/frontend/assets/js/myNotes.js"></script>

</body>
</html>
