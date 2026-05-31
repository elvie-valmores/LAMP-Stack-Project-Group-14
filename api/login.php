<?php

$inData = getRequestInfo();

// Make sure required fields are sent
if (
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


// Find user by email

$stmt = $conn->prepare(
    "SELECT id, full_name, password
     FROM users
     WHERE email = ?"
);

$stmt->bind_param(
    "s",
    $inData["email"]
);

$stmt->execute();
$result = $stmt->get_result();


// Verify user exists
if ($row = $result->fetch_assoc())
{
    // Compare entered password to stored hash
    if (
        password_verify(
            $inData["password"],
            $row["password"]
        )
    )
    {
        returnWithInfo(
            $row["full_name"],
            $row["id"]
        );
    }
    else
    {
        returnWithError(
            "Invalid username or password"
        );
    }
}
else
{
    returnWithError(
        "Invalid username or password"
    );
}


// Close db resources
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
        "id" => 0,
        "fullName" => "",
        "error" => $err
    ]);

    sendResultInfoAsJson($retValue);
}


// Success response
function returnWithInfo(
    $fullName,
    $id
)
{
    $retValue = json_encode([
        "id" => $id,
        "fullName" => $fullName,
        "error" => ""
    ]);

    sendResultInfoAsJson($retValue);
}

?>

