<?php
/**
 * Logout Handler
 * Securely terminates user session and redirects to login
 */
include('connections.php');

// Logout process: Secure session termination
session_regenerate_id(true); // Prevent session fixation
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to a login or other page after logout
echo "<script> parent.location.href = '/login'; </script>";
exit();
?>