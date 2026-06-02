<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . "/../../backend/config/database.php";

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT id, full_name, email, created_at
    FROM users
    WHERE id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$countStmt = $conn->prepare("
    SELECT COUNT(*) AS total_notes
    FROM notes
    WHERE user_id = ?
");

$countStmt->bind_param("i", $user_id);
$countStmt->execute();
$countResult = $countStmt->get_result()->fetch_assoc();

$total_notes = $countResult["total_notes"] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile - UCF Study Hub</title>
    <link rel="stylesheet" href="/frontend/assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="brand-logo">
        <div class="logo-badge">UCF</div>
        <div>
            <span>UCF</span>
            <strong>Study Hub</strong>
        </div>
    </div>

    <div class="nav-links">
        <a href="/frontend/index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="browse-notes.php">Browse Notes</a>
        <a href="upload-note.php">Upload Note</a>
        <a href="my-notes.php">My Notes</a>
        <a href="/backend/logout.php">Logout</a>
    </div>
</nav>

<section class="profile-page">
    <div class="profile-hero">
        <div>
            <p class="profile-kicker">Student Account</p>
            <h1>My Profile</h1>
            <p>View your account information and activity.</p>
        </div>

        <div class="profile-avatar">
            <?php echo strtoupper(substr($user["full_name"], 0, 1)); ?>
        </div>
    </div>

    <div class="profile-grid">
        <div class="profile-card main-profile-card">
            <div class="profile-card-header">
                <div class="profile-avatar small">
                    <?php echo strtoupper(substr($user["full_name"], 0, 1)); ?>
                </div>

                <div>
                    <h2><?php echo htmlspecialchars($user["full_name"]); ?></h2>
                    <p><?php echo htmlspecialchars($user["email"]); ?></p>
                </div>
            </div>

            <div class="profile-info">
                <div>
                    <span>Email</span>
                    <strong><?php echo htmlspecialchars($user["email"]); ?></strong>
                </div>

                <div>
                    <span>Joined</span>
                    <strong><?php echo htmlspecialchars($user["created_at"]); ?></strong>
                </div>

                <div>
                    <span>Uploaded Notes</span>
                    <strong><?php echo htmlspecialchars($total_notes); ?></strong>
                </div>
            </div>
        </div>

        <div class="profile-card">
            <h3>Quick Actions</h3>

            <div class="profile-actions">
                <a href="upload-note.php">Upload New Note</a>
                <a href="my-notes.php">View My Notes</a>
                <a href="browse-notes.php">Browse Notes</a>
            </div>
        </div>
    </div>
</section>

</body>
</html>
