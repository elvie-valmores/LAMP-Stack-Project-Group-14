<?php

// SearchContacts.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

session_start();
require_once __DIR__ . "/../backend/config/database.php";

// Get userId from session (more secure than client-side)
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo json_encode([
        "success" => false,
        "message" => "User not authenticated."
    ]);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);
$search = trim($data['search'] ?? "");

if ($search === "") {
    echo json_encode([
        "success" => false,
        "message" => "Search term is required.",
        "contacts" => []
    ]);
    exit();
}

$keyword = "%" . $search . "%";

$stmt = $conn->prepare("
    SELECT
        id AS contactId,
        first_name AS firstName,
        last_name AS lastName,
        email,
        phone,
        notes,
        created_at,
        updated_at
    FROM contacts
    WHERE user_id = ?
    AND (first_name LIKE ?
         OR last_name LIKE ?
         OR email LIKE ?
         OR phone LIKE ?)
    ORDER BY updated_at DESC
");

$stmt->bind_param("issss", $userId, $keyword, $keyword, $keyword, $keyword);

if (!$stmt->execute()) {
    echo json_encode([
        "success" => false,
        "message" => "Database query failed."
    ]);
    exit();
}

$result = $stmt->get_result();
$contacts = [];

while ($row = $result->fetch_assoc()) {
    $contacts[] = $row;
}

$stmt->close();

echo json_encode([
    "success" => true,
    "search" => $search,
    "count" => count($contacts),
    "data" => $contacts
]);

?>