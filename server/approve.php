<?php
// session_start();
require_once "connections.php"; // Include database connection

if (isset($_GET['username']) && $_GET['username']) {
    $username = $_GET['username']; // Ensure it's an integer
    $status = 1;

    // Now, update the user status
    $updateQuery = $conn->prepare("UPDATE users SET status = ? WHERE username = ?");
    $updateQuery->bind_param("is", $status, $username);

    if ($updateQuery->execute()) {
        $_SESSION['status'] = 'success';
        $_SESSION['success'] = $username . ' successfully approved';
        echo "<script> window.location = '/users'; </script>";
        exit();
    }
} else {
    $_SESSION['status'] = 'failed';
    $_SESSION['failed'] = "User not found!";
    echo "<script> window.location = '/users'; </script>";
    exit();
}
?>