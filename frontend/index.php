<?php
session_start();

$isLoggedIn = isset($_SESSION["user_id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="description" content="UCF Study Hub helps students upload, browse, search, and manage study notes, contacts, and academic resources.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCF Study Hub</title>
<style>
<?php echo file_get_contents(__DIR__ . "/assets/css/style.css"); ?>
</style>
</head>
<body>
<main id="main-content">

<nav class="navbar">
    <div class="logo" style="margin-right: 120px;">UCF Study Hub</div>

    <div class="nav-links">
        <a href="/">Home</a>

        <?php if ($isLoggedIn): ?>
            <a href="/dashboard">Dashboard</a>
            
            
            <a href="/my-notes">My Notes</a>
                    <a href="/contacts">Contacts</a>
            <a href="/profile">Profile</a>
            <a href="/backend/logout.php">Logout</a>
        <?php else: ?>
            
            <a href="/login">Login</a>
            <a href="/register" class="nav-btn">Register</a>
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
            <a href="/upload" class="btn primary">Upload Note</a>
            <a href="/browse" class="btn secondary">Browse Notes</a>
                <a href="/contacts" class="btn primary">Contact Manager</a>
        <?php else: ?>
            <a href="/register" class="btn primary">Get Started</a>
            <a href="/login" class="btn secondary">Browse Notes</a>
                <a href="/login" class="btn primary">Contact Manager</a>
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

</main>
</body>
</html>
