<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: /login");
    exit();
}

require_once "../../backend/config/database.php";

$user_id = $_SESSION["user_id"];
$note_id = intval($_GET["id"] ?? 0);

if ($note_id <= 0) {
    header("Location: /my-notes");
    exit();
}

$stmt = $conn->prepare("
    SELECT id, user_id, category_id, title, course, description, file_path
    FROM notes
    WHERE id = ? AND user_id = ?
");
$stmt->bind_param("ii", $note_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: /my-notes");
    exit();
}

$note = $result->fetch_assoc();

$categories = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Edit your uploaded UCF Study Hub note and update study materials.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Note - UCF Study Hub</title>
    <link rel="stylesheet" href="/frontend/assets/css/style.css">

<style>
/* Direct Edit Note page design fix */
.edit-note-page {
    max-width: 980px;
    margin: 70px auto;
    padding: 0 24px;
}

.edit-note-page .section-header {
    text-align: center;
    margin-bottom: 35px;
}

.edit-note-page .section-header h1 {
    color: #ffd000;
    font-size: 4rem;
    margin-bottom: 10px;
}

.edit-note-page .section-header p {
    color: #ddd;
    font-size: 1.35rem;
}

.edit-note-card {
    background: linear-gradient(145deg, #171717, #101010);
    border: 1px solid rgba(255, 208, 0, 0.35);
    border-radius: 26px;
    padding: 44px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45);
}

#editNoteForm {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

#editNoteForm input,
#editNoteForm select,
#editNoteForm textarea {
    width: 100%;
    box-sizing: border-box;
    padding: 22px 24px;
    border-radius: 18px;
    border: 1px solid rgba(255, 204, 0, 0.4);
    background: #111;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 600;
    outline: none;
}

#editNoteForm textarea {
    min-height: 180px;
    resize: vertical;
}

#editNoteForm input:focus,
#editNoteForm select:focus,
#editNoteForm textarea:focus {
    border-color: #ffd000;
    box-shadow: 0 0 0 3px rgba(255, 208, 0, 0.18);
}

.edit-file-box {
    padding: 24px;
    border-radius: 18px;
    border: 1px dashed rgba(255, 204, 0, 0.5);
    background: rgba(255, 255, 255, 0.03);
}

.edit-file-box label {
    display: block;
    color: #ffd000;
    font-size: 1.3rem;
    font-weight: 900;
    margin-bottom: 14px;
}

.edit-file-box input[type="file"] {
    background: transparent !important;
    border: none !important;
    padding: 0 !important;
}

.edit-file-box .file-help {
    margin-top: 14px;
    color: #ddd;
    font-size: 0.95rem;
    word-break: break-word;
}

#editNoteForm button {
    width: 100%;
    padding: 24px;
    border: none;
    border-radius: 22px;
    background: linear-gradient(135deg, #ffd000, #f5b800);
    color: #000;
    font-size: 1.45rem;
    font-weight: 900;
    cursor: pointer;
}

#editNoteForm button:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 30px rgba(255, 208, 0, 0.25);
}

#message {
    color: #ffd000;
    font-weight: 800;
    text-align: center;
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .edit-note-page {
        margin: 40px auto;
    }

    .edit-note-page .section-header h1 {
        font-size: 2.7rem;
    }

    .edit-note-card {
        padding: 26px;
    }
}
</style>

</head>
<body>
<main id="main-content">

<nav class="navbar">
    <div class="logo">UCF Study Hub</div>
    <div class="nav-links">
        <a href="/">Home</a>
        <a href="/dashboard">Dashboard</a>
        <a href="/browse">Browse Notes</a>
        <a href="/my-notes">My Notes</a>
        <a href="/contacts">Contacts</a>
        <a href="/profile">Profile</a>
        <a href="/backend/logout.php">Logout</a>
    </div>
</nav>

<section class="edit-note-page">
    <div class="section-header">
        <h1>Edit Note</h1>
        <p>Update your note details or choose a new file.</p>
    </div>

    <div class="edit-note-card">
        <form id="editNoteForm" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($note["id"]); ?>">
            <input type="hidden" name="current_file_path" value="<?php echo htmlspecialchars($note["file_path"]); ?>">

            <input
                type="text"
                name="title"
                placeholder="Note Title"
                value="<?php echo htmlspecialchars($note["title"]); ?>"
                required
            >

            <input
                type="text"
                name="course"
                placeholder="Course"
                value="<?php echo htmlspecialchars($note["course"]); ?>"
                required
            >

            <select name="category_id" required>
                <?php while ($category = $categories->fetch_assoc()): ?>
                    <option
                        value="<?php echo htmlspecialchars($category["id"]); ?>"
                        <?php echo ($category["id"] == $note["category_id"]) ? "selected" : ""; ?>
                    >
                        <?php echo htmlspecialchars($category["name"]); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <textarea name="description" placeholder="Description" required><?php echo htmlspecialchars($note["description"]); ?></textarea>

            <div class="edit-file-box">
                <label for="note_file">Choose New File Optional</label>
                <input type="file" id="note_file" name="note_file">
                <p class="file-help">
                    Current file:
                    <span><?php echo htmlspecialchars($note["file_path"]); ?></span>
                </p>
            </div>

            <button type="submit">Update Note</button>
            <p id="message"></p>
        </form>
    </div>
</section>

</main>

<script>
document.getElementById("editNoteForm").addEventListener("submit", async function(event) {
    event.preventDefault();

    const form = document.getElementById("editNoteForm");
    const formData = new FormData(form);
    const message = document.getElementById("message");

    try {
        const response = await fetch("/api/updateNote.php", {
            method: "POST",
            body: formData
        });

        const result = await response.json();

        message.textContent = result.message;

        if (result.success) {
            setTimeout(() => {
                window.location.href = "/my-notes";
            }, 800);
        }
    } catch (error) {
        message.textContent = "Could not connect to the Update Note API.";
    }
});
</script>

</body>
</html>
