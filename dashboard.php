<?php
require 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h1>Welcome!</h1>

    <p class="subtitle">
        Hello, <?= htmlspecialchars($_SESSION["username"]) ?>!
    </p>

    <p>You have successfully signed in.</p>

    <a class="logout" href="logout.php">
        Logout
    </a>

</div>

</body>
</html>
