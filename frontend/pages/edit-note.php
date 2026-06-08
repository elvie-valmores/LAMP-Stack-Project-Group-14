<?php
session_start();
require_once __DIR__ . "/../../backend/config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$note_id = intval($_GET["id"] ?? 0);

if ($note_id <= 0) {
    die("Invalid note ID.");
}

$stmt = $conn->prepare("
    SELECT id, user_id, category_id, title, course, description, file_path
    FROM notes
    WHERE id = ? AND user_id = ?
");

$stmt->bind_param("ii", $note_id, $user_id);
$stmt->execute();

$note = $stmt->get_result()->fetch_assoc();

if (!$note) {
    die("Note not found or you do not have permission to edit it.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Note - UCF Study Hub</title>
    <link rel="stylesheet" href="/frontend/assets/css/style.css">
    <meta name="description" content="UCF Study Hub helps students upload, browse, search, and manage study notes and academic resources.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<main id="main-content">

<nav class="navbar">
    <div class="logo" style="margin-right: 120px;">UCF Study Hub</div>

    <div class="nav-links">
        <a href="/frontend/index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="browse-notes.php">Browse Notes</a>
        <a href="my-notes.php">My Notes</a>
                    <a href="contacts.php">Contacts</a>
        <a href="profile.php">Profile</a>
        <a href="../../backend/logout.php">Logout</a>
    </div>
</nav>

<section class="dashboard">
    <h1>Edit Note</h1>
    <p>Update your note using the API with AJAX.</p>

    <form id="editNoteForm" class="auth-form">
        <input type="hidden" id="noteId" value="<?php echo $note['id']; ?>">
        <input type="hidden" id="userId" value="<?php echo $user_id; ?>">

        <input 
            type="text" 
            id="title" 
            placeholder="Note Title"
            value="<?php echo htmlspecialchars($note['title']); ?>"
            required
        >

        <input 
            type="text" 
            id="course" 
            placeholder="Course Name"
            value="<?php echo htmlspecialchars($note['course']); ?>"
            required
        >

        <select id="categoryId" required>
            <option value="1" <?php if ($note['category_id'] == 1) echo "selected"; ?>>Computer Science</option>
            <option value="2" <?php if ($note['category_id'] == 2) echo "selected"; ?>>Information Technology</option>
            <option value="3" <?php if ($note['category_id'] == 3) echo "selected"; ?>>Math</option>
            <option value="4" <?php if ($note['category_id'] == 4) echo "selected"; ?>>Science</option>
            <option value="5" <?php if ($note['category_id'] == 5) echo "selected"; ?>>Business</option>
            <option value="6" <?php if ($note['category_id'] == 6) echo "selected"; ?>>General Education</option>
        </select>

        <textarea 
            id="description" 
            placeholder="Short Description"
            required
        ><?php echo htmlspecialchars($note['description']); ?></textarea>

        <input 
            type="text" 
            id="filePath" 
            placeholder="File path, example: uploads/final.pdf"
            value="<?php echo htmlspecialchars($note['file_path']); ?>"
        >

        <button type="submit">Update Note</button>

        <p id="message"></p>
    </form>
</section>

<script>
document.getElementById("editNoteForm").addEventListener("submit", async function(event) {
    event.preventDefault();

    const message = document.getElementById("message");

    const noteData = {
        id: document.getElementById("noteId").value,
        user_id: document.getElementById("userId").value,
        category_id: document.getElementById("categoryId").value,
        title: document.getElementById("title").value.trim(),
        course: document.getElementById("course").value.trim(),
        description: document.getElementById("description").value.trim(),
        file_path: document.getElementById("filePath").value.trim()
    };

    try {
        const response = await fetch("/api/updateNote.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(noteData)
        });

        const data = await response.json();

        if (data.success) {
            message.className = "success";
            message.textContent = data.message;

            setTimeout(function() {
                window.location.href = "my-notes.php";
            }, 800);
        } else {
            message.className = "error";
            message.textContent = data.message;
        }
    } catch (error) {
        message.className = "error";
        message.textContent = "Could not connect to the Update Note API.";
    }
});
</script>

</main>
</body>
</html>
