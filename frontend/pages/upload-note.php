<?php
session_start();
require_once __DIR__ . "/../../backend/config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $course = trim($_POST["course"]);
    $description = trim($_POST["description"]);
    $user_id = $_SESSION["user_id"];

    $upload_dir = __DIR__ . "/../../uploads/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = time() . "_" . basename($_FILES["note_file"]["name"]);
    $target_file = $upload_dir . $file_name;
    $db_file_path = "uploads/" . $file_name;

    if (move_uploaded_file($_FILES["note_file"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO notes (user_id, title, course, description, file_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $title, $course, $description, $db_file_path);

        if ($stmt->execute()) {
            $message = "Note uploaded successfully!";
        } else {
            $message = "Database error.";
        }
    } else {
        $message = "File upload failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Note - UCF Study Hub</title>
    <link rel="stylesheet" href="/frontend/assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">UCF Study Hub</div>
    <div class="nav-links">
        <a href="../index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="browse-notes.php">Browse Notes</a>
        <a href="profile.php">Profile</a>
        <a href="../../backend/logout.php">Logout</a>
    </div>
</nav>

<div class="form-page">
    <form method="POST" enctype="multipart/form-data" class="auth-form">
        <h2>Upload Note</h2>

        <?php if ($message): ?>
            <p class="success"><?php echo $message; ?></p>
        <?php endif; ?>

        <input type="text" name="title" placeholder="Note Title" required>
        <input type="text" name="course" placeholder="Course Name, example: COP 3330" required>
        <textarea name="description" placeholder="Short Description" required></textarea>
        <input type="file" name="note_file" required>

        <button type="submit">Upload Note</button>
    </form>
</div>

</body>
</html>
