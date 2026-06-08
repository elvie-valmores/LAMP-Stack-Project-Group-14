<?php
session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: /dashboard");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - UCF Study Hub</title>
    <link rel="stylesheet" href="/frontend/assets/css/style.css">
    <meta name="description" content="UCF Study Hub helps students upload, browse, search, and manage study notes and academic resources.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<main id="main-content">

<nav class="navbar">
    <div class="logo">UCF Study Hub</div>

    <div class="nav-links">
        <a href="/">Home</a>
        <a href="/login">Login</a>
        <a href="/register" class="nav-btn">Register</a>
    </div>
</nav>

<section class="dashboard">
    <form id="registerForm" class="auth-form">
        <h1 class="auth-title">Create Account</h1>

        <input 
            type="text" 
            id="fullName" 
            placeholder="Full Name" 
            required
        >

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

        <button type="submit">Register</button>

        <p id="message"></p>

        <p>
            Already have an account?
            <a href="/login">Login</a>
        </p>
    </form>
</section>

<script>
document.getElementById("registerForm").addEventListener("submit", async function(event) {
    event.preventDefault();

    const message = document.getElementById("message");

    const registerData = {
        full_name: document.getElementById("fullName").value.trim(),
        email: document.getElementById("email").value.trim(),
        password: document.getElementById("password").value
    };

    message.className = "";
    message.textContent = "Creating account...";

    try {
        const response = await fetch("/api/register.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(registerData)
        });

        const data = await response.json();

        if (data.success) {
            message.className = "success";
            message.textContent = data.message;

            setTimeout(function() {
                window.location.href = "/login";
            }, 800);
        } else {
            message.className = "error";
            message.textContent = data.message;
        }
    } catch (error) {
        message.className = "error";
        message.textContent = "Could not connect to the Register API.";
    }
});
</script>

</main>
</body>
</html>
