<?php
session_start();

// Check if the user is not logged in and redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance App</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../images/mafkoora_logo.png" alt="Mafkoora Logo">
        </div>
        <div class="logout">
            <a href="../logout.php">Logout</a>
        </div>
    </header>
</body>
</html>
