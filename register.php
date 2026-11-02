<?php
require 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    if (empty($username) || empty($password) || empty($confirm)) {
        $message = "Please fill in all fields.";

    } elseif ($password !== $confirm) {
        $message = "Passwords do not match.";

    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters.";

    } else {

        $check = $conn->prepare(
            "SELECT id FROM users WHERE username = ?"
        );

        $check->bind_param("s", $username);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $message = "Username already exists.";

        } else {

            $hashedPassword = $password;

            $stmt = $conn->prepare(
                "INSERT INTO users (username, password)
                 VALUES (?, ?)"
            );

            $stmt->bind_param(
                "ss",
                $username,
                $hashedPassword
            );

            if ($stmt->execute()) {
                header("Location: index.php?registered=1");
                exit();
            } else {
                $message = "Registration failed.";
            }
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h1>Create Account</h1>
    <p class="subtitle">Register to get started.</p>

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

        <label>Confirm Password</label>
        <input type="password" name="confirm"
               placeholder="Confirm password" required>

        <button type="submit">Register</button>

    </form>

    <p class="switch">
        Already have an account?
        <a href="index.php">Sign In</a>
    </p>

</div>

</body>
</html>
