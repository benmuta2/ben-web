<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address.'); window.history.back();</script>";
        exit;
    }

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'musembi');

    if ($conn->connect_error) {
        die("<script>alert('Connection failed.'); window.history.back();</script>");
    }

    // Check if already subscribed
    $check = $conn->prepare("select email from subscribe where email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('You are already subscribed.'); window.location.href='index.html';</script>";
    } else {
        $stmt = $conn->prepare("insert into subscribe (email) values (?)");
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            // Send email notification to you
            $to = "bensonmusembi871@gmail.com";
            $subject = "New Newsletter Subscriber";
            $message = "You have a new subscriber:\n\nEmail: $email";
            $headers = "From: no-reply@abccollege.com";

            mail($to, $subject, $message, $headers);

            // Show scripted message to user
            echo "<script>alert('Thank you for subscribing to ABC College!'); window.location.href='index.html';</script>";
        } else {
            echo "<script>alert('Subscription failed. Please try again.'); window.history.back();</script>";
        }

        $stmt->close();
    }

    $check->close();
    $conn->close();
}
?>
