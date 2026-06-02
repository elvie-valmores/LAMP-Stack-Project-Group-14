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
    <title>Upload Note - UCF Study Hub</title>
    <link rel="stylesheet" href="/frontend/assets/css/style.css">

    <style>
        .upload-page {
            width: min(86%, 1150px);
            margin: 70px auto;
        }

        .upload-card {
            background: linear-gradient(145deg, #101010, #171717);
            border: 1px solid rgba(255, 215, 0, 0.25);
            border-radius: 34px;
            padding: 60px;
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.45);
        }

        .upload-card h1 {
            font-size: clamp(52px, 7vw, 92px);
            line-height: 1;
            color: #ffffff;
            margin-bottom: 18px;
        }

        .upload-card > p {
            color: #d8d8d8;
            font-size: 24px;
            line-height: 1.5;
            margin-bottom: 45px;
            max-width: 760px;
        }

        #addNoteForm {
            display: grid;
            grid-template-columns: 1fr;
            gap: 22px;
            max-width: 850px;
        }

        #addNoteForm input,
        #addNoteForm select,
        #addNoteForm textarea {
            width: 100%;
            background: #202020;
            border: 1px solid rgba(255, 215, 0, 0.25);
            color: #ffffff;
            padding: 20px 22px;
            border-radius: 18px;
            font-size: 18px;
            font-family: Arial, Helvetica, sans-serif;
            outline: none;
        }

        #addNoteForm textarea {
            min-height: 150px;
            resize: vertical;
        }

        #addNoteForm input::placeholder,
        #addNoteForm textarea::placeholder {
            color: #9a9a9a;
        }

        #addNoteForm input:focus,
        #addNoteForm select:focus,
        #addNoteForm textarea:focus {
            border-color: #ffd700;
            box-shadow: 0 0 0 4px rgba(255, 215, 0, 0.1);
        }

        .file-upload-box {
            background: #202020;
            border: 1px dashed rgba(255, 215, 0, 0.45);
            border-radius: 18px;
            padding: 24px;
        }

        .file-upload-box label {
            display: block;
            color: #ffd700;
            font-size: 20px;
            font-weight: 900;
            margin-bottom: 12px;
        }

        #addNoteForm input[type="file"] {
            border: none;
            padding: 0;
            background: transparent;
        }

        #addNoteForm input[type="file"]::file-selector-button {
            background: #ffd700;
            color: #000000;
            border: none;
            border-radius: 12px;
            padding: 13px 20px;
            font-weight: 900;
            margin-right: 18px;
            cursor: pointer;
        }

        #addNoteForm button {
            width: 100%;
            background: #ffd700;
            color: #000000;
            border: none;
            padding: 21px;
            border-radius: 18px;
            font-size: 24px;
            font-weight: 950;
            cursor: pointer;
            margin-top: 8px;
        }

        #addNoteForm button:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 45px rgba(255, 215, 0, 0.2);
        }

        #uploadMessage {
            font-size: 20px;
            font-weight: 900;
            text-align: center;
            margin-top: 8px;
        }

        @media (max-width: 800px) {
            .upload-card {
                padding: 32px;
            }

            .upload-card h1 {
                font-size: 48px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="logo">UCF Study Hub</div>

    <div class="nav-links">
        <a href="/frontend/index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="browse-notes.php">Browse Notes</a>
        <a href="upload-note.php">Upload Note</a>
        <a href="my-notes.php">My Notes</a>
        <a href="profile.php">Profile</a>
        <a href="/backend/logout.php">Logout</a>
    </div>
</nav>

<section class="upload-page">
    <div class="upload-card">
        <h1>Upload Note</h1>
        <p>Choose a file from your computer and upload it as a study resource for other students.</p>

        <form id="addNoteForm" enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION["user_id"]; ?>">

            <input type="text" name="title" placeholder="Note Title" required>

            <input type="text" name="course" placeholder="Course, example: COP 3502" required>

            <select name="category_id" required>
                <option value="">Select Subject</option>
                <option value="1">Computer Science</option>
                <option value="2">Information Technology</option>
                <option value="3">Math</option>
                <option value="4">Science</option>
                <option value="5">Business</option>
                <option value="6">General Education</option>
            </select>

            <textarea name="description" placeholder="Short Description" required></textarea>

            <div class="file-upload-box">
                <label>Upload File</label>
                <input type="file" name="note_file" accept=".pdf,.doc,.docx,.txt,.png,.jpg,.jpeg" required>
            </div>

            <button type="submit">Add Note</button>

            <p id="uploadMessage"></p>
        </form>
    </div>
</section>

<script>
document.getElementById("addNoteForm").addEventListener("submit", async function(event) {
    event.preventDefault();

    const message = document.getElementById("uploadMessage");
    const formData = new FormData(this);

    message.textContent = "Uploading note...";
    message.style.color = "#ffd700";

    try {
        const response = await fetch("/api/addNote.php", {
            method: "POST",
            body: formData
        });

        const text = await response.text();
        const data = JSON.parse(text);

        if (data.success) {
            message.textContent = data.message;
            message.style.color = "#00e676";
            this.reset();
        } else {
            message.textContent = data.message;
            message.style.color = "#ff4d4d";
        }
    } catch (error) {
        message.textContent = "Could not upload note. Check server/API.";
        message.style.color = "#ff4d4d";
    }
});
</script>

</body>
</html>
