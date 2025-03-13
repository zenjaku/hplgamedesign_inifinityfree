<?php
/**
 * Computer Name Validation Script
 * 
 * This script checks if a given computer name (cname) exists in the database.
 * Used for form validation to prevent duplicate computer names during registration
 * or updates.
 * 
 * @method POST
 * @param {string} cname - The computer name to validate
 * @return JSON {exists: boolean} Returns whether the computer name exists
 */

require_once '../connections.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Check if cname is provided
    if (!isset($_POST['cname'])) {
        echo json_encode(['exists' => false, 'error' => 'No computer name provided']);
        exit();
    }

    // Step 2: Sanitize input
    $cname = trim($_POST['cname']);
    
    // Step 3: Validate input
    if (empty($cname)) {
        echo json_encode(['exists' => false, 'error' => 'Computer name cannot be empty']);
        exit();
    }

    try {
        // Step 4: Prepare and execute query
        $stmt = $conn->prepare("SELECT cname FROM computer WHERE cname = ?");
        $stmt->bind_param("s", $cname);
        $stmt->execute();
        $stmt->store_result();

        // Step 5: Return result
        echo json_encode(['exists' => ($stmt->num_rows > 0)]);

        // Step 6: Clean up
        $stmt->close();
    } catch (Exception $e) {
        // Handle any database errors
        echo json_encode(['exists' => false, 'error' => 'Database error occurred']);
    } finally {
        // Always close the connection
        $conn->close();
    }
} else {
    // Handle invalid request method
    echo json_encode(['exists' => false, 'error' => 'Invalid request method']);
}
?>