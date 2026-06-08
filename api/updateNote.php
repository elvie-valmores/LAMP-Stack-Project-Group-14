<?php
session_start();
header("Content-Type: application/json");

require_once "../backend/config/database.php";

if (!isset($_SESSION["user_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "User not logged in."
    ]);
    exit();
}

$user_id = $_SESSION["user_id"];

$note_id = intval($_POST["id"] ?? 0);
$category_id = intval($_POST["category_id"] ?? 0);
$title = trim($_POST["title"] ?? "");
$course = trim($_POST["course"] ?? "");
$description = trim($_POST["description"] ?? "");
$file_path = trim($_POST["current_file_path"] ?? "");

if ($note_id <= 0 || $category_id <= 0 || $title === "" || $course === "" || $description === "") {
    echo json_encode([
        "success" => false,
        "message" => "Please fill in all required fields."
    ]);
    exit();
}

/* Make sure the note belongs to this user */
$check = $conn->prepare("SELECT file_path FROM notes WHERE id = ? AND user_id = ?");
$check->bind_param("ii", $note_id, $user_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Note not found."
    ]);
    exit();
}

$existing_note = $result->fetch_assoc();

if ($file_path === "") {
    $file_path = $existing_note["file_path"];
}

/* If user picked a new file, upload it */
if (isset($_FILES["note_file"]) && $_FILES["note_file"]["error"] === UPLOAD_ERR_OK) {
    $upload_dir = "../uploads/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0775, true);
    }

    $original_name = basename($_FILES["note_file"]["name"]);
    $safe_name = time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "_", $original_name);
    $target_path = $upload_dir . $safe_name;

    if (move_uploaded_file($_FILES["note_file"]["tmp_name"], $target_path)) {
        $file_path = "uploads/" . $safe_name;
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to upload the new file."
        ]);
        exit();
    }
}

$stmt = $conn->prepare("
    UPDATE notes
    SET category_id = ?, title = ?, course = ?, description = ?, file_path = ?
    WHERE id = ? AND user_id = ?
");

$stmt->bind_param(
    "issssii",
    $category_id,
    $title,
    $course,
    $description,
    $file_path,
    $note_id,
    $user_id
);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Note updated successfully."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Failed to update note."
    ]);
}
?>
