<?php
/**
 * Employee ID Validation Script
 * 
 * This script checks if a given employee ID exists in the database.
 * Used for form validation to prevent duplicate employee IDs and
 * verify existing employees during operations.
 * 
 * @method POST
 * @param {string} employee_id - The employee ID to validate
 * @return JSON {exists: boolean} Returns whether the employee ID exists
 */

require_once '../connections.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Check if employee_id is provided
    if (!isset($_POST['employee_id'])) {
        echo json_encode(['exists' => false, 'error' => 'No employee ID provided']);
        exit();
    }

    // Step 2: Sanitize input
    $employee_id = trim($_POST['employee_id']);
    
    // Step 3: Validate input
    if (empty($employee_id)) {
        echo json_encode(['exists' => false, 'error' => 'Employee ID cannot be empty']);
        exit();
    }

    try {
        // Step 4: Prepare and execute query
        $stmt = $conn->prepare("SELECT employee_id FROM employee WHERE employee_id = ?");
        $stmt->bind_param("s", $employee_id);
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

