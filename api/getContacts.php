<?php
session_start();
header("Content-Type: application/json");
require_once "../backend/config/database.php";

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit();
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("SELECT * FROM contacts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$contacts = [];
while ($row = $result->fetch_assoc()) {
    $contacts[] = $row;
}

echo json_encode(["success" => true, "contacts" => $contacts]);
?>
