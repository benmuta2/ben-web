<?php
$email = $_POST['email'];
$password = $_POST['password'];

$conn = new mysqli('localhost', 'root', '', 'ben');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("select password from user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($hashed_password);

if ($stmt->fetch()) {
    if (password_verify($password, $hashed_password)) {
        echo "Login successful! Welcome.";
        // You could redirect to a dashboard here
        // header("Location: dashboard.html");
        // exit();
    } else {
        echo "Invalid password.";
    }
} else {
    echo "User not found.";
}

$stmt->close();
$conn->close();
?>
