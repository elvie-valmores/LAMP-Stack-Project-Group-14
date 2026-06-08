<?php

// AddContact.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

session_start();
require_once __DIR__ . "/../backend/config/database.php";

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo json_encode([
        "success" => false,
        "message" => "User not authenticated."
    ]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$firstName = trim($data['firstName'] ?? "");
$lastName = trim($data['lastName'] ?? "");
$email = trim($data['email'] ?? "");
$phone = trim($data['phone'] ?? "");
$notes = trim($data['notes'] ?? "");

// Validation
if ($firstName === "" || $lastName === "") {
    echo json_encode([
        "success" => false,
        "message" => "First name and last name are required."
    ]);
    exit();
}

if (strlen($firstName) > 50 || strlen($lastName) > 50) {
    echo json_encode([
        "success" => false,
        "message" => "Names must be 50 characters or less."
    ]);
    exit();
}

if ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid email format."
    ]);
    exit();
}

if ($phone !== "" && !preg_match('/^[\d\-\+\(\)\s]{7,20}$/', $phone)) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid phone format."
    ]);
    exit();
}

$insert_stmt = $conn->prepare("
    INSERT INTO contacts (user_id, first_name, last_name, email, phone, notes)
    VALUES (?, ?, ?, ?, ?, ?)
");

$insert_stmt->bind_param("isssss", $userId, $firstName, $lastName, $email, $phone, $notes);

if ($insert_stmt->execute()) {
    $contactId = $insert_stmt->insert_id;
    echo json_encode([
        "success" => true,
        "message" => "Contact added successfully.",
        "data" => [
            "contactId" => $contactId
        ]
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Failed to add contact."
    ]);
}

$insert_stmt->close();

?>