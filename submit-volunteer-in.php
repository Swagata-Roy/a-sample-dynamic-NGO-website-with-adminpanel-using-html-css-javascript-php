<?php
// Disable error reporting to prevent any HTML output
error_reporting(0);
ini_set('display_errors', 0);

// Ensure we're only outputting JSON
header('Content-Type: application/json');

$response = array('success' => false, 'message' => '');

try {
    // Database connection
    require 'config/db_conn.php';

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Verify hCaptcha
    $secret = $_SERVER['HCAPTCHA_SECRET'];
    $verifyResponse = file_get_contents('https://hcaptcha.com/siteverify?secret=' . $secret . '&response=' . $_POST['h-captcha-response']);
    $responseData = json_decode($verifyResponse);

    if (!$responseData->success) {
        throw new Exception("hCaptcha verification failed. Please try again.");
    }

    // Process and sanitize form data
    $firstName = trim($conn->real_escape_string($_POST['first_name']));
    $lastName = trim($conn->real_escape_string($_POST['last_name']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = preg_replace('/[^0-9+]/', '', $_POST['phone']);
    $gender = trim($conn->real_escape_string($_POST['gender']));
    $country = trim($conn->real_escape_string($_POST['country']));
    $biography = trim($conn->real_escape_string($_POST['biography']));
    $contribution = trim($conn->real_escape_string($_POST['contribution']));
    $hobbies = trim($conn->real_escape_string($_POST['hobbies']));

    // File upload function with MIME type verification
    function uploadFile($file, $uploadDir) {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new Exception("No file uploaded or upload failed.");
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file["tmp_name"]);
        finfo_close($finfo);

        $allowedMimeTypes = ['image/jpeg', 'image/png'];
        if (!in_array($mimeType, $allowedMimeTypes)) {
            throw new Exception("Invalid file type. Only JPG, JPEG, and PNG files are allowed.");
        }

        $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $uniqueName = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $imageFileType;
        $targetFile = $uploadDir . $uniqueName;

        if ($file["size"] > 2000000) {
            throw new Exception("File is too large. Maximum size is 2MB.");
        }

        if (!move_uploaded_file($file["tmp_name"], $targetFile)) {
            throw new Exception("Error uploading file.");
        }

        return $targetFile;
    }

    // Upload files
    $uploadDir = "uploads/volunteer_in/";
    $picturePath = uploadFile($_FILES["picture"], $uploadDir);
    $identityPath = uploadFile($_FILES["identity"], $uploadDir);

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO volunteer_in (first_name, last_name, email, phone, gender, country, identity, picture, biography, contribution, hobbies)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }

    // Bind parameters to prevent SQL injection and handle special characters
    $stmt->bind_param("sssssssssss", $firstName, $lastName, $email, $phone, $gender, $country, $identityPath, $picturePath, $biography, $contribution, $hobbies);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Form submitted successfully.";
    } else {
        throw new Exception("Error: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    $response['message'] = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}

echo json_encode($response);
exit;
?>