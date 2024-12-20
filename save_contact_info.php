<?php
// Database connection
require 'config/db_conn.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $phone_link = $_POST['phone_link'];
    $email = $_POST['email'];
    $email_link = $_POST['email_link'];

    // Check if a record already exists
    $check_sql = "SELECT * FROM contact_info LIMIT 1";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        // Update existing record
        $sql = "UPDATE contact_info SET phone = ?, phone_link = ?, email = ?, email_link = ? WHERE id = 1";
    } else {
        // Insert new record
        $sql = "INSERT INTO contact_info (phone, phone_link, email, email_link) VALUES (?, ?, ?, ?)";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $phone, $phone_link, $email, $email_link);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Contact info updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error updating contact info: " . $conn->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

$conn->close();
?>