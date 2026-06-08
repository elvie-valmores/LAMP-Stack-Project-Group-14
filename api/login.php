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

$email = trim($data["email"] ?? "");
$password = $data["password"] ?? "";

if ($email === "" || $password === "") {
    echo json_encode([
        "success" => false,
        "message" => "Email and password are required."
    ]);
    exit();
}

$stmt = $conn->prepare("
    SELECT id, full_name, email, password
    FROM users
    WHERE email = ?
");

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid email or password."
    ]);
    exit();
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user["password"])) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid email or password."
    ]);
    exit();
}

$_SESSION["user_id"] = $user["id"];
$_SESSION["full_name"] = $user["full_name"];
$_SESSION["email"] = $user["email"];

echo json_encode([
    "success" => true,
    "message" => "Login successful.",
    "user" => [
        "id" => $user["id"],
        "full_name" => $user["full_name"],
        "email" => $user["email"]
    ]
]);
?>
