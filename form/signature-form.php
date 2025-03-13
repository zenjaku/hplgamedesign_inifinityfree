<?php
// Enable error reporting for development (in production, you can disable this)
error_reporting(E_ALL);
ini_set('display_errors', 0);  // Don't display errors to users

// Load dependencies
require_once __DIR__ . '/../vendor/autoload.php';  // Adjusted path
include 'server/connections.php';

// Check database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Function to log errors into a file
function logError($message)
{
    $logFile = __DIR__ . '/upload_errors.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
}

// Function to handle file upload (both for employee and admin)// Function to handle file upload (both for employee and admin)
function handleFileUpload($file, $id, $type)
{
    global $conn, $cloudinary;

    // Check if there was an upload error
    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png'];

        if (in_array($fileExtension, $allowedTypes)) {
            try {

                // Backup: Save locally if Cloudinary fails
                $uploadDir = __DIR__ . '/signature/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $newFileName = $id . '_' . date('Y-m-d_H-i-s') . '.' . $fileExtension;
                $targetPath = $uploadDir . $newFileName;

                // Move file to the backup directory
                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $localImageUrl = 'form/signature/' . $newFileName;

                    // Update database with the local file URL
                    if ($type == 'users') {
                        $stmt = $conn->prepare("UPDATE $type SET signature = ? WHERE username = ?");
                        $stmt->bind_param("ss", $localImageUrl, $id);  // Bind string for both
                    } else {
                        $stmt = $conn->prepare("UPDATE $type SET signature = ? WHERE {$type}_id = ?");
                        $stmt->bind_param("si", $localImageUrl, $id);  // Bind string and integer for employees
                    }

                    if ($stmt->execute()) {
                        $_SESSION['success'] = 'Image uploaded successfully and saved locally as backup!';
                    } else {
                        // Log database error for local upload
                        logError("Database error while updating local signature for $type $id: " . $stmt->error);
                        $_SESSION['failed'] = 'Failed to save signature in the database!';
                    }
                } else {
                    // Log file system error
                    logError("Failed to upload file to server for $type $id.");
                    $_SESSION['failed'] = 'Failed to upload signature locally!';
                }

            } catch (Exception $e) {
                logError("Failed to upload file to server for $type $id.");
                $_SESSION['failed'] = 'Failed to upload signature locally!';
            }

        } else {
            // Log invalid file type error
            logError("Invalid file type uploaded for $type $id: $fileExtension");
            $_SESSION['failed'] = 'Invalid file type. Only JPG, JPEG, and PNG are allowed!';
        }
    } else {
        // Log upload error
        logError("File upload error for $type $id: " . $file['error']);
        $_SESSION['failed'] = 'There was an error uploading the file!';
    }
}


// Handle Employee Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['e-signature'])) {
    $employee_id = $_GET['employee_id'];
    $file = $_FILES['e-signature'];
    handleFileUpload($file, $employee_id, 'employee');
    // Redirect after upload
    
    echo "<script>window.location.href = '/admin-view?employee_id=$employee_id';</script>";
    exit();
}

// Handle Admin Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['a-signature'])) {
    $username = $_GET['username'];
    $file = $_FILES['a-signature'];
    handleFileUpload($file, $username, 'users');
    // Redirect after upload
    
    echo "<script>window.location.href = '/admin-view?username=$username';</script>";
    exit();
}
?>