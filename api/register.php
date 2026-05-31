<?php

$inData = getRequestInfo();

// Make sure required fields are sent
if (
    !isset($inData["fullName"]) ||
    !isset($inData["email"]) ||
    !isset($inData["password"])
)
{
    returnWithError(
        "Missing required fields"
    );
    exit();
}

require_once __DIR__ . "/../backend/config/database.php";


// Hash password before storing it
$hashedPassword = password_hash(
    $inData["password"],
    PASSWORD_DEFAULT
);


$stmt = $conn->prepare(
    "INSERT INTO users (full_name, email, password)
     VALUES (?, ?, ?)"
);

$stmt->bind_param(
    "sss",
    $inData["fullName"],
    $inData["email"],
    $hashedPassword
);

if ($stmt->execute())
{
    returnWithSuccess(
        "User registered successfully."
    );
}
else
{
    returnWithError(
        "Registration failed. Email may already exist."
    );
}

$stmt->close();
$conn->close();



// Read JSON request body
function getRequestInfo()
{
    return json_decode(
        file_get_contents('php://input'),
        true
    );
}


// Send JSON response
function sendResultInfoAsJson($obj)
{
    header('Content-type: application/json');
    echo $obj;
}


// Error response
function returnWithError($err)
{
    $retValue = json_encode([
        "error" => $err
    ]);

    sendResultInfoAsJson($retValue);
}


// Success response
function returnWithSuccess($message)
{
    $retValue = json_encode([
        "message" => $message,
        "error" => ""
    ]);

    sendResultInfoAsJson($retValue);
}

?>