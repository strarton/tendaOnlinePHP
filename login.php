<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start(); // Start a session to manage user login state
require_once 'ManagerDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        // Initialize the database connection
        $dbManager = new ManagerDB("localhost", "BDVdAWEB", "root", "1234");

        // Check user credentials using the ManagerDB method
        $user = $dbManager->checkCredentials($email, $password);

        if ($user) {
            // Store user information in the session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['nom'],
                'email' => $user['mail']
            ];

            // Use the isAdmin method to check if the user is an admin
            if ($dbManager->isAdmin($user['id'])) {
                header("Location: catalogo_admin.php");
                exit;
            } else {
                // Redirect non-admin users to the default catalog page
                header("Location: catalogo.php");
                exit;
            }
        } else {
            // Invalid credentials
            $error = "Invalid email or password!";
        }
    } catch (Exception $e) {
        // Handle errors
        $error = "An error occurred: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
