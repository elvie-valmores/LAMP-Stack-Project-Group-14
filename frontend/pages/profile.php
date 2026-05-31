<?php
session_start();
require_once __DIR__ . "/../../backend/config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT id, full_name, email, created_at
    FROM users
    WHERE id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total_notes
    FROM notes
    WHERE user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notes = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile - UCF Study Hub</title>
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
        <a href="../../backend/logout.php">Logout</a>
    </div>
</nav>

<section class="dashboard">

    <h1>My Profile</h1>
    <p>Account information and activity.</p>

    <div class="feature-card">

        <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>

        <p>
            <strong>Email:</strong>
            <?php echo htmlspecialchars($user['email']); ?>
        </p>

        <p>
            <strong>Joined:</strong>
            <?php echo htmlspecialchars($user['created_at']); ?>
        </p>

        <p>
            <strong>Uploaded Notes:</strong>
            <?php echo $notes['total_notes']; ?>
        </p>

    </div>

</section>

</body>
</html>
