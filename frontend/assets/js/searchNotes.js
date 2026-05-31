document.addEventListener("DOMContentLoaded", function () {
    const searchForm = document.getElementById("searchForm");
    const searchInput = document.getElementById("searchInput");
    const notesContainer = document.getElementById("notesContainer");

    if (!searchForm || !searchInput || !notesContainer) {
        return;
    }

    searchForm.addEventListener("submit", async function (event) {
        event.preventDefault();

        const searchValue = searchInput.value.trim();

        notesContainer.innerHTML = "";

        if (searchValue === "") {
            notesContainer.innerHTML = `
                <div class="feature-card">
                    <h3>Search Required</h3>
                    <p>Please enter a note title, course, description, or category.</p>
                </div>
            `;
            return;
        }

        try {
            const response = await fetch(`/api/searchNotes.php?search=${encodeURIComponent(searchValue)}`);
            const data = await response.json();

            if (!data.success) {
                notesContainer.innerHTML = `
                    <div class="feature-card">
                        <h3>Error</h3>
                        <p>${data.message}</p>
                    </div>
                `;
                return;
            }

            if (data.count === 0) {
                notesContainer.innerHTML = `
                    <div class="feature-card">
                        <h3>No Notes Found</h3>
                        <p>Try searching with another title, course, or category.</p>
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
                    <p><strong>Uploaded by:</strong> ${escapeHtml(note.uploaded_by)}</p>
                    ${
                        note.file_path
                            ? `<a class="btn primary" href="/${escapeHtml(note.file_path)}" download>Download</a>`
                            : ""
                    }
                `;

                notesContainer.appendChild(card);
            });
        } catch (error) {
            notesContainer.innerHTML = `
                <div class="feature-card">
                    <h3>Connection Error</h3>
                    <p>Could not connect to the search API.</p>
                </div>
            `;
        }
    });
});

function escapeHtml(text) {
    return String(text)
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}
