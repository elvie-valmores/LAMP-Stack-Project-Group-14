<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . "/../backend/config/database.php";

$user_id = intval($_GET["user_id"] ?? 0);

if ($user_id <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "User ID is required.",
        "notes" => []
    ]);
    exit();
}

$stmt = $conn->prepare("
    SELECT 
        notes.id,
        notes.title,
        notes.course,
        notes.description,
        notes.file_path,
        notes.uploaded_at,
        notes.updated_at,
        categories.name AS category
    FROM notes
    LEFT JOIN categories ON notes.category_id = categories.id
    WHERE notes.user_id = ?
    ORDER BY notes.updated_at DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$notes = [];

while ($row = $result->fetch_assoc()) {
    $notes[] = $row;
}

echo json_encode([
    "success" => true,
    "count" => count($notes),
    "notes" => $notes
]);
?>
