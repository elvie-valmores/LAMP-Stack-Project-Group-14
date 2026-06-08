<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
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
                    <a href="contacts.php">Contacts</a>
        <a href="profile.php">Profile</a>
        <a href="../../backend/logout.php">Logout</a>
    </div>
</nav>

<section class="dashboard">
    <h1>Browse Notes</h1>
    <p>Search for shared study notes by title, course, category, or keyword.</p>

    <form id="searchForm" class="auth-form" style="max-width: 100%; margin-bottom: 30px;">
        <input 
            type="text" 
            id="searchInput" 
            placeholder="Search by title, course, description, or category"
        >

        <button type="submit">Search</button>
    </form>

    <div id="notesContainer" class="features">
        <div class="feature-card">
            <h3>Search for Notes</h3>
            <p>Enter a note title, course, description, or category to search the database.</p>
        </div>
    </div>
</section>

<script src="/frontend/assets/js/searchNotes.js"></script>

</body>
</html>
