<?php
/**
 * Employee ID Validation Script
 * 
 * This script checks if a given employee ID exists in the database.
 * It receives the employee_id via POST request and returns a JSON response
 * indicating whether the ID exists or not.
 * 
 * @return JSON {exists: boolean} Returns true if employee_id exists, false otherwise
 */

require_once '../connections.php'; // Include your database connection file

// Check if employee_id is provided in POST request
if (isset($_POST['employee_id'])) {
    $employee_id = $_POST['employee_id'];

    // Prepare and execute query to check employee_id existence
    $stmt = $conn->prepare("SELECT employee_id FROM employee WHERE employee_id = ?");
    $stmt->bind_param("s", $employee_id);
    $stmt->execute();
    $stmt->store_result();

    // Return JSON response based on query result
    if ($stmt->num_rows > 0) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }

    // Clean up database resources
    $stmt->close();
    $conn->close();
} else {
    // Return false if no employee_id was provided
    echo json_encode(['exists' => false]);
}
?>
