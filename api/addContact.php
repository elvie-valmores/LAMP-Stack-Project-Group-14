<?php
session_start();
header("Content-Type: application/json");
require_once "../backend/config/database.php";

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit();
}

$user_id = $_SESSION["user_id"];
$first_name = trim($_POST["first_name"] ?? "");
$last_name = trim($_POST["last_name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$address = trim($_POST["address"] ?? "");
$notes = trim($_POST["notes"] ?? "");

if ($first_name === "") {
    echo json_encode(["success" => false, "message" => "First name is required."]);
    exit();
}

$stmt = $conn->prepare("INSERT INTO contacts (user_id, first_name, last_name, email, phone, address, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssss", $user_id, $first_name, $last_name, $email, $phone, $address, $notes);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Contact added successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add contact."]);
}
?>
