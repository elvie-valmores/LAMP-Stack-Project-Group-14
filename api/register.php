<?php
session_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
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

$full_name = trim($data["full_name"] ?? "");
$email = trim($data["email"] ?? "");
$password = $data["password"] ?? "";

if ($full_name === "" || $email === "" || $password === "") {
    echo json_encode([
        "success" => false,
        "message" => "Full name, email, and password are required."
    ]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid email address."
    ]);
    exit();
}

$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Email already exists."
    ]);
    exit();
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    INSERT INTO users (full_name, email, password)
    VALUES (?, ?, ?)
");

$stmt->bind_param("sss", $full_name, $email, $hashedPassword);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Account created successfully.",
        "user_id" => $stmt->insert_id
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Registration failed."
    ]);
}
?>
