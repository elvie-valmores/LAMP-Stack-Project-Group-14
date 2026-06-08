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
$first_name = trim($_POST["first_name"] ?? "");
$last_name = trim($_POST["last_name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$address = trim($_POST["address"] ?? "");
$notes = trim($_POST["notes"] ?? "");

if ($contact_id <= 0 || $first_name === "") {
    echo json_encode(["success" => false, "message" => "Contact ID and first name are required."]);
    exit();
}

$stmt = $conn->prepare("
    UPDATE contacts
    SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, notes = ?
    WHERE id = ? AND user_id = ?
");
$stmt->bind_param("ssssssii", $first_name, $last_name, $email, $phone, $address, $notes, $contact_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Contact updated successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update contact."]);
}
?>
