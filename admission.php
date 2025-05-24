<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name   = $_POST['name'];
    $email  = $_POST['email'];
    $phone  = $_POST['phone'];
    $course = $_POST['course'];
    $notes  = $_POST['notes'];

    $conn = new mysqli('localhost', 'root', '', 'musembi');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email already exists
    $check = $conn->prepare("select email from admissions where email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('You have already submitted an application with this email.'); window.location.href='index.html';</script>";
    } else {
        // Proceed to insert new application
        $stmt = $conn->prepare("insert into admissions (name, email, phone, course, notes) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $course, $notes);

        if ($stmt->execute()) {
            // Optional: email to admin
            mail("bensonmusembi871@gmail.com", "New Admission", "Name: $name\nEmail: $email\nPhone: $phone\nCourse: $course\nNotes: $notes");

            echo "<script>alert('Your application has been received!'); window.location.href='index.html';</script>";
        } else {
            echo "<script>alert('Error submitting form. Please try again.'); window.history.back();</script>";
        }

        $stmt->close();
    }

    $check->close();
    $conn->close();
}
?>

