<?php
/**
 * Username Validation Script
 * 
 * This script checks if a given username already exists in the users table.
 * Used during user registration to prevent duplicate usernames.
 * 
 * @method POST
 * @param {string} username - The username to check
 * @return JSON {exists: boolean} Returns true if username exists, false otherwise
 */

require_once '../connections.php'; // Include your database connection file

// Check if username is provided in POST request
if (isset($_POST['username'])) {
    $username = $_POST['username'];

    // Prepare and execute query to check username existence
    // Using prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Return JSON response based on query result
    // exists: true if username is found in database
    // exists: false if username is available
    if ($stmt->num_rows > 0) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }

    // Clean up database resources
    $stmt->close();
    $conn->close();
} else {
    // Return false if no username was provided in the request
    echo json_encode(['exists' => false]);
}
?>