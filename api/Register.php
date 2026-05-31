<?php

$inData = getRequestInfo();


/*
--------------------------------------------------------------------------------
    Temp response while db not available
    Using the following in postman body
        {
              "firstName": "Test",
              "lastName": "User",
              "login": "testuser",
              "password": "password123"
          }
*/
returnWithError(
    "Register API ready. Waiting for database."
);

//-------------------------------------------------------------------------------
/*
    DATABASE VERSION

    Commented out for Postman testing until mySQl db done
    Using temp placeholders until db fields ready
*/

/*
$conn = new mysqli(
    "localhost",
    "YOUR_DB_USER",
    "YOUR_DB_PASSWORD",
    "YOUR_DATABASE_NAME"
);

if ($conn->connect_error)
{
    returnWithError("Database connection failed.");
    exit();
}


// Hash password before storing it
$hashedPassword = password_hash(
    $inData["password"],
    PASSWORD_DEFAULT
);


$stmt = $conn->prepare(
    "INSERT INTO Users (firstName, lastName, Login, Password)
     VALUES (?, ?, ?, ?)"
);

$stmt->bind_param(
    "ssss",
    $inData["firstName"],
    $inData["lastName"],
    $inData["login"],
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
        "Registration failed. Login may already exist."
    );
}

$stmt->close();
$conn->close();
*/


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