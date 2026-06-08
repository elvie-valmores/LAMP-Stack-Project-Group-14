<?php
session_start();

$isLoggedIn = isset($_SESSION["user_id"]);
?>

<!DOCTYPE html>
<html>
<head>
    <title>UCF Study Hub</title>
    <link rel="stylesheet" href="/frontend/assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo" style="margin-right: 120px;">UCF Study Hub</div>

    <div class="nav-links">
        <a href="/frontend/index.php">Home</a>

        <?php if ($isLoggedIn): ?>
            <a href="/frontend/pages/dashboard.php">Dashboard</a>
            <a href="/frontend/pages/browse-notes.php">Browse Notes</a>
            
            <a href="/frontend/pages/my-notes.php">My Notes</a>
                    <a href="/frontend/pages/contacts.php">Contacts</a>
            <a href="/frontend/pages/profile.php">Profile</a>
            <a href="/backend/logout.php">Logout</a>
        <?php else: ?>
            <a href="/frontend/pages/login.php">Browse Notes</a>
            <a href="/frontend/pages/login.php">Login</a>
            <a href="/frontend/pages/register.php" class="nav-btn">Register</a>
        <?php endif; ?>
    </div>
</nav>

<section class="hero">
    <div class="hero-subtitle">Learn. Share. Succeed.</div>

    <h1>Study smarter with shared UCF notes.</h1>

    <p>
        Upload study guides, browse class notes, and find helpful resources created by students.
    </p>

    <div class="hero-buttons">
        <?php if ($isLoggedIn): ?>
            <a href="/frontend/pages/upload-note.php" class="btn primary">Upload Note</a>
            <a href="/frontend/pages/browse-notes.php" class="btn secondary">Browse Notes</a>
                <a href="/frontend/pages/contacts.php" class="btn primary">Contact Manager</a>
        <?php else: ?>
            <a href="/frontend/pages/register.php" class="btn primary">Get Started</a>
            <a href="/frontend/pages/login.php" class="btn secondary">Browse Notes</a>
        <?php endif; ?>
    </div>
</section>

<section class="stats-section">
    <h2>Study Hub Stats</h2>

    <div class="stats-grid">
        <div class="stat-box">
            <h3>128</h3>
            <p>Uploaded Notes</p>
        </div>

        <div class="stat-box">
            <h3>64</h3>
            <p>Active Students</p>
        </div>

        <div class="stat-box">
            <h3>21</h3>
            <p>Courses Covered</p>
        </div>
    </div>
</section>

<section class="features">
    <div class="feature-card">
        <h3>Upload Notes</h3>
        <p>Share PDFs, class notes, and study guides with other students.</p>
    </div>

    <div class="feature-card">
        <h3>Search Fast</h3>
        <p>Find notes by course name, subject, keyword, or category using the search API.</p>
    </div>

    <div class="feature-card">
        <h3>Manage Files</h3>
        <p>Edit, delete, and organize your own uploaded study resources.</p>
    </div>
</section>

</body>
</html>
