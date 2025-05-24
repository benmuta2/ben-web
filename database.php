<?php
$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'ben');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if email already exists
$check = $conn->prepare("SELECT email FROM user WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "Email already registered.";
} else {
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        // Show success message and redirect after 3 seconds
        echo "<!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv='refresh' content='3;url=dashboard.php'>
            <title>Registration Successful</title>
        </head>
        <body>
            <h2>Registration Successful!</h2>
            <p>You will be redirected to your dashboard shortly...</p>
        </body>
        </html>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$check->close();
$conn->close();
?>
