<?php

$inData = getRequestInfo();

/*
/*
For postman testing before db
Using these in Postman
{
       "login": "testuser",
       "password": "password123"
   }
/*

if (
    $inData["login"] === "testuser" &&
    $inData["password"] === "password123"
)
{
    returnWithInfo(
        "Test",
        "User",
        1
    );
}
else
{
    returnWithError(
        "Invalid username or password"
    );
}
*/

//-----------------------


require_once __DIR__ . "/../backend/config/database.php";


// Find user by login name.

$stmt = $conn->prepare(
    "SELECT ID, firstName, lastName, Password
     FROM Users
     WHERE Login = ?"
);


// Bind login parameter safely
$stmt->bind_param(
    "s",
    $inData["login"]
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
            $row["Password"]
        )
    )
    {
        returnWithInfo(
            $row["firstName"],
            $row["lastName"],
            $row["ID"]
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

//----------------------------------

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


// Standard error response
function returnWithError($err)
{
    $retValue = json_encode([
        "id" => 0,
        "firstName" => "",
        "lastName" => "",
        "error" => $err
    ]);

    sendResultInfoAsJson($retValue);
}


// Standard success response
function returnWithInfo(
    $firstName,
    $lastName,
    $id
)
{
    $retValue = json_encode([
        "id" => $id,
        "firstName" => $firstName,
        "lastName" => $lastName,
        "error" => ""
    ]);

    sendResultInfoAsJson($retValue);
}

?>

