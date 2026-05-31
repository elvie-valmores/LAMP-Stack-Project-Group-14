<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . "/../backend/config/database.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid JSON input."
    ]);
    exit();
}

$user_id = intval($data["user_id"] ?? 0);
$category_id = isset($data["category_id"]) ? intval($data["category_id"]) : null;
$title = trim($data["title"] ?? "");
$course = trim($data["course"] ?? "");
$description = trim($data["description"] ?? "");
$file_path = trim($data["file_path"] ?? "");

if ($user_id <= 0 || $title === "" || $course === "") {
    echo json_encode([
        "success" => false,
        "message" => "User ID, title, and course are required."
    ]);
    exit();
}

$stmt = $conn->prepare("
    INSERT INTO notes (user_id, category_id, title, course, description, file_path)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "iissss",
    $user_id,
    $category_id,
    $title,
    $course,
    $description,
    $file_path
);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Note added successfully.",
        "note_id" => $stmt->insert_id
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Failed to add note."
    ]);
}
?>
