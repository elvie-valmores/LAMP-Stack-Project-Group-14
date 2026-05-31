<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, POST");
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

$id = intval($data["id"] ?? 0);
$user_id = intval($data["user_id"] ?? 0);
$category_id = isset($data["category_id"]) ? intval($data["category_id"]) : null;
$title = trim($data["title"] ?? "");
$course = trim($data["course"] ?? "");
$description = trim($data["description"] ?? "");
$file_path = trim($data["file_path"] ?? "");

if ($id <= 0 || $user_id <= 0 || $title === "" || $course === "") {
    echo json_encode([
        "success" => false,
        "message" => "Note ID, user ID, title, and course are required."
    ]);
    exit();
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
    $id,
    $user_id
);

$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode([
        "success" => true,
        "message" => "Note updated successfully."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "No note updated. Check note ID and user ID."
    ]);
}
?>
