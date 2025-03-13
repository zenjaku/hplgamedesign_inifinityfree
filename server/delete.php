<?php
// session_start();
require_once "connections.php"; // Include database connection

if (isset($_GET['username']) && !empty($_GET['username'])) {
    $username = trim($_GET['username']); // Trim spaces for safety

    // Check if the user exists before deleting
    $checkUser = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $checkUser->bind_param("s", $username);
    $checkUser->execute();
    $result = $checkUser->get_result();

    if ($result->num_rows > 0) {
        // User exists, proceed with deletion
        $deleteUser = $conn->prepare("DELETE FROM users WHERE username = ?");
        $deleteUser->bind_param("s", $username);

        if ($deleteUser->execute()) {
            $_SESSION['status'] = 'success';
            $_SESSION['success'] = "$username successfully removed.";
        } else {
            $_SESSION['status'] = 'failed';
            $_SESSION['failed'] = "Failed to delete user.";
        }
    } else {
        $_SESSION['status'] = 'failed';
        $_SESSION['failed'] = "User not found!";
    }

    // Redirect after processing
    echo "<script> window.location = '/users'; </script>";
    exit();
} else {
    $_SESSION['status'] = 'failed';
    $_SESSION['failed'] = "Invalid request!";
    echo "<script> window.location = '/users'; </script>";
    exit();
}
?>
