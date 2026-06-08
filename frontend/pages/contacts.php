<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts | UCF Study Hub</title>
    <meta name="description" content="Manage personal contacts inside UCF Study Hub. Add, search, edit, and delete contacts.">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main id="main-content">

<div class="contacts-page">
    <nav class="contacts-nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="upload-note.php">Upload Note</a>
        <a href="my-notes.php">My Notes</a>
        <a href="profile.php">Profile</a>
    </nav>
    <div class="contacts-header">
        <h1>Contact Manager</h1>
        <p>Add, search, edit, and delete your personal contacts.</p>
    </div>

    <div class="contacts-card">
        <h2>Add New Contact</h2>

        <form id="addContactForm">
            <div class="form-row">
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name">
            </div>

            <div class="form-row">
                <input type="email" name="email" placeholder="Email">
                <input type="text" name="phone" placeholder="Phone">
            </div>

            <input type="text" name="address" placeholder="Address">
            <textarea name="notes" placeholder="Notes"></textarea>

            <button type="submit">Add Contact</button>
        </form>
    </div>

    <div class="contacts-card">
        <h2>Search Contacts</h2>
        <div class="search-row">
            <input type="text" id="searchInput" placeholder="Search by name, email, phone, address, or notes">
            <button type="button" id="searchBtn">Search</button>
            <button type="button" id="clearSearchBtn">Clear</button>
        </div>
    </div>

    <div class="contacts-card">
        <h2>My Contacts</h2>
        <div id="contactsList"></div>
    </div>
</div>

<script>
const contactsList = document.getElementById("contactsList");
const searchInput = document.getElementById("searchInput");
const addContactForm = document.getElementById("addContactForm");
const searchBtn = document.getElementById("searchBtn");
const clearSearchBtn = document.getElementById("clearSearchBtn");

function escapeHtml(text) {
    if (text === null || text === undefined) return "";
    return String(text)
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}

function renderContacts(contacts) {
    if (!contacts || contacts.length === 0) {
        contactsList.innerHTML = "<p>No contacts found.</p>";
        return;
    }

    contactsList.innerHTML = contacts.map(contact => `
        <div class="contact-item">
            <div class="contact-info">
                <h3>${escapeHtml(contact.first_name)} ${escapeHtml(contact.last_name)}</h3>
                <p><strong>Email:</strong> ${escapeHtml(contact.email)}</p>
                <p><strong>Phone:</strong> ${escapeHtml(contact.phone)}</p>
                <p><strong>Address:</strong> ${escapeHtml(contact.address)}</p>
                <p><strong>Notes:</strong> ${escapeHtml(contact.notes)}</p>
            </div>

            <div class="contact-actions">
                <button onclick="editContact(
                    '${contact.id}',
                    '${escapeHtml(contact.first_name)}',
                    '${escapeHtml(contact.last_name)}',
                    '${escapeHtml(contact.email)}',
                    '${escapeHtml(contact.phone)}',
                    '${escapeHtml(contact.address)}',
                    '${escapeHtml(contact.notes)}'
                )">Edit</button>

                <button class="delete-btn" onclick="deleteContact(${contact.id})">Delete</button>
            </div>
        </div>
    `).join("");
}

function loadContacts() {
    fetch("../../api/getContacts.php", {
        method: "POST"
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderContacts(data.contacts);
        } else {
            contactsList.innerHTML = `<p>${data.message}</p>`;
        }
    })
    .catch(() => {
        contactsList.innerHTML = "<p>Error loading contacts.</p>";
    });
}

addContactForm.addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(addContactForm);

    fetch("../../api/addContact.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            addContactForm.reset();
            loadContacts();
        }
    });
});

function searchContacts() {
    const formData = new FormData();
    formData.append("query", searchInput.value);

    fetch("../../api/searchContacts.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderContacts(data.contacts);
        }
    });
}

searchBtn.addEventListener("click", searchContacts);

searchInput.addEventListener("keydown", function(e) {
    if (e.key === "Enter") {
        searchContacts();
    }
});

clearSearchBtn.addEventListener("click", function() {
    searchInput.value = "";
    loadContacts();
});

function editContact(id, firstName, lastName, email, phone, address, notes) {
    const newFirstName = prompt("First Name:", firstName);
    if (newFirstName === null || newFirstName.trim() === "") return;

    const newLastName = prompt("Last Name:", lastName) || "";
    const newEmail = prompt("Email:", email) || "";
    const newPhone = prompt("Phone:", phone) || "";
    const newAddress = prompt("Address:", address) || "";
    const newNotes = prompt("Notes:", notes) || "";

    const formData = new FormData();
    formData.append("contact_id", id);
    formData.append("first_name", newFirstName);
    formData.append("last_name", newLastName);
    formData.append("email", newEmail);
    formData.append("phone", newPhone);
    formData.append("address", newAddress);
    formData.append("notes", newNotes);

    fetch("../../api/editContact.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        loadContacts();
    });
}

function deleteContact(id) {
    if (!confirm("Are you sure you want to delete this contact?")) return;

    const formData = new FormData();
    formData.append("contact_id", id);

    fetch("../../api/deleteContact.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        loadContacts();
    });
}

loadContacts();
</script>

</main>
</body>
</html>
