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
    <title>Upload Note - UCF Study Hub</title>
    <link rel="stylesheet" href="/frontend/assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">UCF Study Hub</div>

    <div class="nav-links">
        <a href="/frontend/index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="browse-notes.php">Browse Notes</a>
        <a href="profile.php">Profile</a>
        <a href="../../backend/logout.php">Logout</a>
    </div>
</nav>

<section class="dashboard">
    <h1>Upload Note</h1>
    <p>Add a study resource using the API. The form sends JSON with AJAX.</p>

    <form id="addNoteForm" class="auth-form">
        <input type="hidden" id="userId" value="<?php echo $user_id; ?>">

        <input 
            type="text" 
            id="title" 
            placeholder="Note Title"
            required
        >

        <input 
            type="text" 
            id="course" 
            placeholder="Course Name, example: CIS 4004"
            required
        >

        <select id="categoryId" required>
            <option value="1">Computer Science</option>
            <option value="2">Information Technology</option>
            <option value="3">Math</option>
            <option value="4">Science</option>
            <option value="5">Business</option>
            <option value="6">General Education</option>
        </select>

        <textarea 
            id="description" 
            placeholder="Short Description"
            required
        ></textarea>

        <input 
            type="text" 
            id="filePath" 
            placeholder="File path, example: uploads/final.pdf"
        >

        <button type="submit">Add Note</button>

        <p id="message"></p>
    </form>
</section>

<script>
document.getElementById("addNoteForm").addEventListener("submit", async function(event) {
    event.preventDefault();

    const message = document.getElementById("message");

    const noteData = {
        user_id: document.getElementById("userId").value,
        category_id: document.getElementById("categoryId").value,
        title: document.getElementById("title").value.trim(),
        course: document.getElementById("course").value.trim(),
        description: document.getElementById("description").value.trim(),
        file_path: document.getElementById("filePath").value.trim()
    };

    try {
        const response = await fetch("/api/addNote.php", {
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

            document.getElementById("addNoteForm").reset();
        } else {
            message.className = "error";
            message.textContent = data.message;
        }
    } catch (error) {
        message.className = "error";
        message.textContent = "Could not connect to the Add Note API.";
    }
});
</script>

</body>
</html>
