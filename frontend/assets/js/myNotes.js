document.addEventListener("DOMContentLoaded", function () {
    loadMyNotes();
});

async function loadMyNotes() {
    const userId = document.getElementById("userId").value;
    const container = document.getElementById("myNotesContainer");

    try {
        const response = await fetch(`/api/getMyNotes.php?user_id=${encodeURIComponent(userId)}`);
        const data = await response.json();

        container.innerHTML = "";

        if (!data.success || data.notes.length === 0) {
            container.innerHTML = `
                <div class="feature-card">
                    <h3>No Notes Yet</h3>
                    <p>You have not uploaded any notes yet.</p>
                </div>
            `;
            return;
        }

        data.notes.forEach(function (note) {
            const card = document.createElement("div");
            card.className = "feature-card";

            card.innerHTML = `
                <h3>${escapeHtml(note.title)}</h3>
                <p><strong>Course:</strong> ${escapeHtml(note.course)}</p>
                <p><strong>Category:</strong> ${escapeHtml(note.category || "Uncategorized")}</p>
                <p>${escapeHtml(note.description || "No description provided.")}</p>

                <div style="display: flex; gap: 15px; margin-top: 20px;">
                    <a class="btn secondary" href="edit-note.php?id=${note.id}">
                        Edit
                    </a>

                    <button class="btn primary" onclick="deleteNote(${note.id}, ${userId})">
                        Delete
                    </button>
                </div>
            `;

            container.appendChild(card);
        });
    } catch (error) {
        container.innerHTML = `
            <div class="feature-card">
                <h3>Connection Error</h3>
                <p>Could not load your notes.</p>
            </div>
        `;
    }
}

async function deleteNote(noteId, userId) {
    const confirmDelete = confirm("Are you sure you want to delete this note?");

    if (!confirmDelete) {
        return;
    }

    try {
        const response = await fetch("/api/deleteNote.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id: noteId,
                user_id: userId
            })
        });

        const data = await response.json();

        if (data.success) {
            loadMyNotes();
        }
    } catch (error) {
        console.log("Delete failed.");
    }
}

function escapeHtml(text) {
    return String(text)
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}
