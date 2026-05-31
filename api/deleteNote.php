<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE, POST");
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

if ($id <= 0 || $user_id <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "Note ID and user ID are required."
    ]);
    exit();
}

$stmt = $conn->prepare("
    DELETE FROM notes
    WHERE id = ? AND user_id = ?
");

$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode([
        "success" => true,
        "message" => "Note deleted successfully."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "No note deleted. Check note ID and user ID."
    ]);
}
?>
