<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the ManagerDB class file
require_once 'ManagerDB.php';  // Make sure the path to ManagerDB.php is correct

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];  // No hashing here
    $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $surname = filter_var($_POST['surname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $birthdate = $_POST['birthdate'];  // Get the birthdate from the form
    $dni = filter_var($_POST['dni'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);  // Updated sanitization method
    $id_lloc = $_POST['id_lloc'];  // Get and sanitize id_lloc from the form

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Check if all fields are filled
    if (empty($email) || empty($password) || empty($name) || empty($surname) || empty($phone) || empty($birthdate) || empty($dni) || empty($id_lloc)) {
        echo "All fields are required.";
        exit;
    }

    // Create the database connection
    try {
        $db = new ManagerDB("localhost", "BDVdAWEB", "root", "1234");
    } catch (Exception $e) {
        echo "Database connection failed: " . $e->getMessage();
        exit;
    }

    // Prepare the query to check if the email already exists
    $query = "SELECT * FROM usuaris WHERE mail = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        // Check if email already exists
        if ($stmt->rowCount() > 0) {
            echo "Email already exists. Please choose a different one.";
            exit;
        } else {
            echo "Email is available. Proceeding with registration...<br>";
        }
    } else {
        echo "Error while checking for existing email: " . implode(", ", $stmt->errorInfo());
        exit;
    }

    // Insert the new user into the database without hashing the password
    $insertQuery = "INSERT INTO usuaris (mail, contrasenya, nom, cognoms, telefon, data_naixement, dni, id_lloc) 
                    VALUES (:email, :password, :name, :surname, :phone, :birthdate, :dni, :id_lloc)";
    $insertStmt = $db->prepare($insertQuery);
    $insertStmt->bindParam(':email', $email);
    $insertStmt->bindParam(':password', $password);  // Use plain password here
    $insertStmt->bindParam(':name', $name);
    $insertStmt->bindParam(':surname', $surname);
    $insertStmt->bindParam(':phone', $phone);
    $insertStmt->bindParam(':birthdate', $birthdate);  // Bind the birthdate parameter
    $insertStmt->bindParam(':dni', $dni);  // Bind the DNI parameter
    $insertStmt->bindParam(':id_lloc', $id_lloc);  // Bind the id_lloc parameter

    // Check if the insert query executes successfully
    if ($insertStmt->execute()) {
        echo "Registration successful! You can now <a href='login.php'>login</a>.";
    } else {
        // Get the error message if query fails
        echo "Error occurred during registration: " . implode(", ", $insertStmt->errorInfo());
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>

    <!-- Registration Form -->
    <form method="POST" action="register.php">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="name">First Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="surname">Last Name:</label><br>
        <input type="text" id="surname" name="surname" required><br><br>

        <label for="phone">Phone:</label><br>
        <input type="text" id="phone" name="phone" required><br><br>

        <label for="birthdate">Birthdate:</label><br>
        <input type="date" id="birthdate" name="birthdate" required><br><br>

        <label for="dni">DNI:</label><br>
        <input type="text" id="dni" name="dni" required><br><br>

        <label for="id_lloc">Location ID:</label><br>
        <input type="text" id="id_lloc" name="id_lloc" required><br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>
