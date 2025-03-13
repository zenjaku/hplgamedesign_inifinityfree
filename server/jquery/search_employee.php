<?php
/**
 * Employee Search Script
 * 
 * This script provides three search functions for employees:
 * 1. searchEmployee - Finds unallocated employees
 * 2. searchOriginalEmployee - Finds employees with active allocations
 * 3. searchTransferEmployee - Finds employees eligible for transfer
 * 
 * @method POST
 * @param {string} searchQuery/originalQuery/transferQuery - Search term for employee
 * @return JSON Array of matching employee records
 */

// session_start();

require_once "../connections.php"; // Adjust path if needed

/**
 * Search for unallocated employees
 * 
 * Finds employees who either have no allocations or their last allocation is inactive
 * 
 * @param string $query Search term to match against employee_id, fname, or lname
 * @param mysqli $conn Database connection object
 * @return void Outputs JSON encoded array of matching employees
 */
function searchEmployee($query, $conn)
{
    $stmt = $conn->prepare("
        SELECT e.employee_id, e.fname, e.lname
        FROM employee e
        WHERE 
            (
                (SELECT a.status 
                 FROM allocation a
                 WHERE a.employee_id = e.employee_id
                 ORDER BY a.created_at DESC
                 LIMIT 1) = 0
                OR NOT EXISTS (
                    SELECT 1 
                    FROM allocation a
                    WHERE a.employee_id = e.employee_id
                )
            )
            AND (e.employee_id = ? OR e.fname = ? OR e.lname = ?)
        ORDER BY e.employee_id
    ");

    $stmt->bind_param("sss", $query, $query, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $employees = [];

    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }

    echo json_encode($employees);
}

/**
 * Search for employees with active allocations
 * 
 * Finds employees who have current active allocations
 * 
 * @param string $query Search term to match against employee_id, fname, or lname
 * @param mysqli $conn Database connection object
 * @return void Outputs JSON encoded array of matching employees
 */
function searchOriginalEmployee($query, $conn)
{
    $stmt = $conn->prepare("
        SELECT e.employee_id, e.fname, e.lname
        FROM employee e
        LEFT JOIN allocation a ON e.employee_id = a.employee_id
        WHERE 
            (
                (
                    a.created_at = (
                        SELECT MAX(a2.created_at)
                        FROM allocation a2
                        WHERE a2.employee_id = e.employee_id
                    )
                    AND a.status = 1
                ) 
                OR a.employee_id IS NULL
            )
            AND (e.employee_id = ? OR e.fname = ? OR e.lname = ?)
        ORDER BY e.employee_id
    ");

    $stmt->bind_param("sss", $query, $query, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $employees = [];

    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }

    echo json_encode($employees);
}

/**
 * Search for employees eligible for transfer
 * 
 * Finds employees who have no active allocations or are eligible for transfer
 * 
 * @param string $query Search term to match against employee_id, fname, or lname
 * @param mysqli $conn Database connection object
 * @return void Outputs JSON encoded array of matching employees
 */
function searchTransferEmployee($query, $conn)
{
    $stmt = $conn->prepare("
        SELECT e.employee_id, e.fname, e.lname
        FROM employee e
        WHERE 
            (
                (SELECT a.status 
                FROM allocation a
                WHERE a.employee_id = e.employee_id
                ORDER BY a.created_at DESC
                LIMIT 1) = 0
                OR NOT EXISTS (
                    SELECT 1 
                    FROM allocation a
                    WHERE a.employee_id = e.employee_id
                )
            )
            AND (e.employee_id = ? OR e.fname = ? OR e.lname = ?)
        ORDER BY e.employee_id
    ");

    $stmt->bind_param("sss", $query, $query, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $employees = [];

    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }

    echo json_encode($employees);
}

// Handle Requests
if (isset($_POST['searchQuery'])) {
    searchEmployee(trim($_POST['searchQuery']), $conn);
} elseif (isset($_POST['originalQuery'])) {
    searchOriginalEmployee(trim($_POST['originalQuery']), $conn);
} elseif (isset($_POST['transferQuery'])) {
    searchTransferEmployee(trim($_POST['transferQuery']), $conn);
}

?>