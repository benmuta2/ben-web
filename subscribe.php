<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'musembi');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email already subscribed
    $check = $conn->prepare("select email from subscribe where email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "You are already subscribed.";
    } else {
        $stmt = $conn->prepare("insert into subscribe (email) values (?)");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            echo "Thank you for subscribing!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $check->close();
    $conn->close();
}
?>
