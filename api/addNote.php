<?php
header("Content-Type: application/json");

require_once __DIR__ . "/../backend/config/database.php";

$user_id = intval($_POST["user_id"] ?? 0);
$category_id = intval($_POST["category_id"] ?? 0);
$title = trim($_POST["title"] ?? "");
$course = trim($_POST["course"] ?? "");
$description = trim($_POST["description"] ?? "");

if ($user_id <= 0 || $category_id <= 0 || $title === "" || $course === "") {
    echo json_encode([
        "success" => false,
        "message" => "User, category, title, and course are required."
    ]);
    exit();
}

$file_path = "";

if (isset($_FILES["note_file"]) && $_FILES["note_file"]["error"] === UPLOAD_ERR_OK) {
    $upload_dir = __DIR__ . "/../uploads/";

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
            "message" => "File upload failed."
        ]);
        exit();
    }
}

$stmt = $conn->prepare("
    INSERT INTO notes (user_id, category_id, title, course, description, file_path)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param("iissss", $user_id, $category_id, $title, $course, $description, $file_path);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Note added successfully.",
        "note_id" => $stmt->insert_id,
        "file_path" => $file_path
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Failed to add note."
    ]);
}
?>
