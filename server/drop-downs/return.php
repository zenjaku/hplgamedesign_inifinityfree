<?php
/**
 * Return Asset Script
 * 
 * This script fetches employees who currently have active allocations
 * and are eligible for asset returns.
 * 
 * @param {string} employee_id - Optional employee ID filter
 * @return Array List of employees with active allocations
 */

// Step 1: Get and sanitize employee ID
$id = $_GET['employee_id'] ?? '';
$id = mysqli_real_escape_string($conn, $id);

// Step 2: Query to get employees with active allocations
$getAllocations = "
    SELECT e.employee_id, e.fname, e.lname
    FROM employee e
    LEFT JOIN allocation a ON e.employee_id = a.employee_id
    WHERE a.status = 1
    ORDER BY e.employee_id
";

$transferEmployeeIDResult = mysqli_query($conn, $getAllocations);
$employeeID = mysqli_fetch_all($transferEmployeeIDResult, MYSQLI_ASSOC);
?>