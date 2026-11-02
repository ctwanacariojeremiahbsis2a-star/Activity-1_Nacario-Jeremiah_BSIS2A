<?php
require 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT id, username, password
         FROM users WHERE username = ?"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if ($password === $user["password"]) {

            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            header("Location: dashboard.php");
            exit();

        } else {
            $message = "Incorrect username or password.";
        }

    } else {
        $message = "Incorrect username or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h1>Welcome Back</h1>
    <p class="subtitle">Sign in to your account.</p>

    <?php if (isset($_GET["registered"])): ?>
        <p class="success">
            Account created successfully! Please sign in.
        </p>
    <?php endif; ?>

    <?php if (!empty($message)): ?>
        <p class="message">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>Username</label>
        <input type="text" name="username"
               placeholder="Enter username" required>

        <label>Password</label>
        <input type="password" name="password"
               placeholder="Enter password" required>

        <button type="submit">Sign In</button>

    </form>

    <p class="switch">
        Don't have an account?
        <a href="register.php">Register</a>
    </p>

</div>

</body>
</html>
