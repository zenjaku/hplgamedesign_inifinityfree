<?php
include_once 'server/connections.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        try {
            // Initialize error array
            $errors = [];

            // Validate employee_id (assuming it should be alphanumeric)
            $employee_id = filter_input(INPUT_POST, 'employee_id', FILTER_SANITIZE_STRING);
            if (!preg_match('/^[A-Za-z0-9-]+$/', $employee_id)) {
                $errors[] = "Invalid employee ID format";
            }

            // Validate name fields
            $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
            $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
            if (empty($fname) || empty($lname) || strlen($fname) > 50 || strlen($lname) > 50) {
                $errors[] = "Name fields are required and must be less than 50 characters";
            }

            // Validate email
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            if (!$email) {
                $errors[] = "Invalid email format";
            }

            // Validate department
            $dept = filter_input(INPUT_POST, 'dept', FILTER_SANITIZE_STRING);
            if (empty($dept)) {
                $errors[] = "Department is required";
            }

            // Validate status
            $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);
            if ($status === false || $status === null) {
                $errors[] = "Invalid status value";
            }

            // If there are validation errors, throw an exception
            if (!empty($errors)) {
                throw new Exception(implode(", ", $errors));
            }

            // Generate timestamp with milliseconds
            $created_at = (new DateTime())->format('Y-m-d H:i:s.v');

            // Check if employee_id already exists
            $check_query = "SELECT employee_id FROM employee WHERE employee_id = ?";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param("s", $employee_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                throw new Exception("Employee ID already exists");
            }

            // Insert the user data into the database
            $query = "INSERT INTO employee (employee_id, fname, lname, dept, email, status, created_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param(
                "sssssss",
                $employee_id,
                $fname,
                $lname,
                $dept,
                $email,
                $status,
                $created_at
            );

            if (!$stmt->execute()) {
                throw new Exception("Database error: " . $stmt->error);
            }

            // Success response
            $_SESSION['status'] = 'success';
            $_SESSION['success'] = 'Information saved successfully';

        } catch (Exception $e) {
            // Error response
            $_SESSION['status'] = 'failed';
            $_SESSION['failed'] = $e->getMessage();
        }

        // Use header instead of JavaScript for redirect
        echo "<script>window.location = '/register;</script>";
        exit();
    }
}
?>