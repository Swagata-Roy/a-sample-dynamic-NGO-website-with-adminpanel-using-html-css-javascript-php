<?php
header('Content-Type: application/json');

require 'config/db_conn.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8')]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form data
    $name = trim($conn->real_escape_string($_POST['name']));
    $organization = trim($conn->real_escape_string($_POST['organization']));
    $phone = preg_replace('/[^0-9+]/', '', $_POST['phone']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = trim($conn->real_escape_string($_POST['message']));

    // Additional email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die(json_encode(["error" => "Invalid email format"]));
    }

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO messages (name, organization, phone, email, message) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die(json_encode(["error" => "Failed to prepare statement: " . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8')]));
    }

    $stmt->bind_param("sssss", $name, $organization, $phone, $email, $message);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(["success" => "Message sent successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . htmlspecialchars($stmt->error, ENT_QUOTES, 'UTF-8')]);
    }

    $stmt->close();
}

$conn->close();
?>