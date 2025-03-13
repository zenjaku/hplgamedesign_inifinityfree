<?php
// Retrieve the employee ID from the GET request, default to an empty string if not provided
$id = $_GET['employee_id'] ?? '';

// Check if the employee ID is empty and redirect to the employee page if it is
if (empty($id)) {
    header('Location: /employee');
    exit;
}

// Define the status to be updated
$status = 'RESIGNED';
$microtime = microtime(true); // Get current microtime as a float
$milliseconds = sprintf('%03d', ($microtime - floor($microtime)) * 1000); // Extract milliseconds
$created_at = date('Y-m-d H:i:s', (int) $microtime) . '.' . $milliseconds;
$updated_at = $created_at; // Use the same timestamp for consistency

// Ensure that the database connection ($conn) is established before executing queries
// $conn should be a valid MySQLi connection instance

// Prepare the SQL statement to prevent SQL injection vulnerabilities
$updateStatus = $conn->prepare("UPDATE employee SET status = ?, updated_at = ? WHERE employee_id = ?");

// Bind the parameters to the SQL statement
// "ss" indicates that both parameters are strings (status and employee_id)
$updateStatus->bind_param("sss", $status, $updated_at, $id); // Use "si" if employee_id is an integer

// Execute the query and handle the outcome
if ($updateStatus->execute()) {
    // If the update is successful, set session variables for success notification
    $_SESSION['status'] = 'success';
    $_SESSION['success'] = 'Information saved successfully';

    // Redirect to the employee page using JavaScript
    echo "<script>window.location = '/employee';</script>";
} else {
    // If the update fails, set session variables for failure notification
    $_SESSION['status'] = 'failed';
    $_SESSION['failed'] = 'Error updating status: ' . $updateStatus->error;

    // Redirect to the employee page using JavaScript
    echo "<script>window.location = '/employee';</script>";
}

// Ensure script execution is stopped after processing
exit;
?>