<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . "/../backend/config/database.php";

$search = trim($_GET["search"] ?? "");

if ($search === "") {
    echo json_encode([
        "success" => false,
        "message" => "Search term is required.",
        "notes" => []
    ]);
    exit();
}

$keyword = "%" . $search . "%";

$stmt = $conn->prepare("
    SELECT 
        notes.id,
        notes.title,
        notes.course,
        notes.description,
        notes.file_path,
        notes.uploaded_at,
        notes.updated_at,
        users.full_name AS uploaded_by,
        categories.name AS category
    FROM notes
    JOIN users ON notes.user_id = users.id
    LEFT JOIN categories ON notes.category_id = categories.id
    WHERE notes.title LIKE ?
       OR notes.course LIKE ?
       OR notes.description LIKE ?
       OR categories.name LIKE ?
    ORDER BY notes.updated_at DESC
");

$stmt->bind_param("ssss", $keyword, $keyword, $keyword, $keyword);
$stmt->execute();

$result = $stmt->get_result();

$notes = [];

while ($row = $result->fetch_assoc()) {
    $notes[] = $row;
}

echo json_encode([
    "success" => true,
    "search" => $search,
    "count" => count($notes),
    "notes" => $notes
]);
?>
