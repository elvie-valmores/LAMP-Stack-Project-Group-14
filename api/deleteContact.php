<?php
session_start();
header("Content-Type: application/json");
require_once "../backend/config/database.php";

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit();
}

$user_id = $_SESSION["user_id"];
$contact_id = intval($_POST["contact_id"] ?? 0);

if ($contact_id <= 0) {
    echo json_encode(["success" => false, "message" => "Contact ID is required."]);
    exit();
}

$stmt = $conn->prepare("DELETE FROM contacts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $contact_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Contact deleted successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete contact."]);
}
?>
