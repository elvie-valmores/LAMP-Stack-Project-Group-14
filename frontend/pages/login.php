<?php
session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - UCF Study Hub</title>
    <link rel="stylesheet" href="/frontend/assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">UCF Study Hub</div>

    <div class="nav-links">
        <a href="/frontend/index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php" class="nav-btn">Register</a>
    </div>
</nav>

<section class="dashboard">
    <form id="loginForm" class="auth-form">
        <h1 class="auth-title">Login</h1>

        <input 
            type="email" 
            id="email" 
            placeholder="Email Address" 
            required
        >

        <input 
            type="password" 
            id="password" 
            placeholder="Password" 
            required
        >

        <button type="submit">Login</button>

        <p id="message"></p>

        <p>
            Don't have an account?
            <a href="register.php">Register</a>
        </p>
    </form>
</section>

<script>
document.getElementById("loginForm").addEventListener("submit", async function(event) {
    event.preventDefault();

    const message = document.getElementById("message");

    const loginData = {
        email: document.getElementById("email").value.trim(),
        password: document.getElementById("password").value
    };

    message.className = "";
    message.textContent = "Logging in...";

    try {
        const response = await fetch("/api/login.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(loginData)
        });

        const data = await response.json();

        if (data.success) {
            message.className = "success";
            message.textContent = data.message;

            setTimeout(function() {
                window.location.href = "dashboard.php";
            }, 600);
        } else {
            message.className = "error";
            message.textContent = data.message;
        }
    } catch (error) {
        message.className = "error";
        message.textContent = "Could not connect to the Login API.";
    }
});
</script>

</body>
</html>
