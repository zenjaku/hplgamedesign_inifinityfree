<?php
/**
 * Transfer Asset Script
 * 
 * This script handles two main functions:
 * 1. Fetches employees who have active allocations (transfer from)
 * 2. Fetches employees who are eligible to receive transfers (transfer to)
 * 
 * @param {string} employee_id - Optional employee ID filter
 * @return Array Lists of source and target employees for transfers
 */

// Step 1: Get and sanitize employee ID
$id = $_GET['employee_id'] ?? '';
$id = mysqli_real_escape_string($conn, $id);

// Step 2: Query employees with active allocations (source)
$transferEmployeeIDQuery = "
    SELECT e.employee_id, e.fname, e.lname
    FROM employee e
    LEFT JOIN allocation a ON e.employee_id = a.employee_id
    WHERE a.created_at = (
        SELECT MAX(a2.created_at)
        FROM allocation a2
        WHERE a2.employee_id = e.employee_id
    )
    AND a.status = 1
    ORDER BY e.employee_id
";

$transferEmployeeIDResult = mysqli_query($conn, $transferEmployeeIDQuery);
$transferEmployeeID = mysqli_fetch_all($transferEmployeeIDResult, MYSQLI_ASSOC);

// Step 3: Query employees eligible for transfers (target)
$transferIDQuery = "
    SELECT e.employee_id, e.fname, e.lname
    FROM employee e
    WHERE 
        (
            (SELECT a.status 
             FROM allocation a
             WHERE a.employee_id = e.employee_id
             ORDER BY a.created_at DESC
             LIMIT 1) = 0
        )
        OR NOT EXISTS (
            SELECT 1 
            FROM allocation a
            WHERE a.employee_id = e.employee_id
        )
    ORDER BY e.employee_id
";

$transferIDResult = mysqli_query($conn, $transferIDQuery);
$transfer = mysqli_fetch_all($transferIDResult, MYSQLI_ASSOC);

// Step 4: Get currently active transfers
$fetchTransferData = $conn->query("SELECT t_employee_id FROM transferred WHERE status = 1");
$transferIDs = array_column($fetchTransferData->fetch_all(MYSQLI_ASSOC), 't_employee_id');

$original = null;
foreach ($transferIDs as $id) {
    $original = $id;
}
?>