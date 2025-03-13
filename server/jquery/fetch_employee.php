<?php
/**
 * Employee Search and Pagination Script
 * 
 * This script handles employee search functionality with pagination and status filtering.
 * It returns employee details along with their allocation status.
 * 
 * @method POST
 * @param {string} name - Search term for employee name or ID
 * @param {string} status - Filter by allocation status (0: unallocated, 1: allocated)
 * @param {number} page - Page number for pagination
 * @return JSON {data: Array, totalPages: number} Returns employee data and pagination info
 */

include '../connections.php';

$page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
$limit = 10;  // Number of records per page
$offset = ($page - 1) * $limit;

// Sanitize input for search
$name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
$status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : '';

// Base query - modified to properly check allocation status
$sql = "SELECT DISTINCT e.*, 
        CASE WHEN a.employee_id IS NOT NULL AND a.status = 1 THEN 1 ELSE 0 END as is_allocated 
        FROM employee e 
        LEFT JOIN allocation a ON e.employee_id = a.employee_id 
        WHERE (e.fname LIKE '$name%' OR e.lname LIKE '$name%' OR e.employee_id LIKE '$name%')";

// Apply status filter if selected
if ($status !== '') {
    if ($status === "1") {
        $sql .= " AND EXISTS (SELECT 1 FROM allocation a2 
                 WHERE a2.employee_id = e.employee_id AND a2.status = 1)";
    } elseif ($status === "0") {
        $sql .= " AND NOT EXISTS (SELECT 1 FROM allocation a2 
                 WHERE a2.employee_id = e.employee_id AND a2.status = 1)";
    }
}

// Add pagination
$sql .= " ORDER BY a.created_at DESC LIMIT $limit OFFSET $offset";

// Execute the query
$query = mysqli_query($conn, $sql);

// Fetch the data
$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = [
        'employee_id' => $row['employee_id'],
        'fname' => $row['fname'],
        'lname' => $row['lname'],
        'email' => $row['email'],
        'dept' => $row['dept'],
        'status' => $row['status'],
        'allocated' => $row['is_allocated'],
    ];
}

// Count total rows for pagination (without LIMIT and OFFSET)
$countSql = "SELECT COUNT(DISTINCT e.employee_id) AS total 
             FROM employee e 
             LEFT JOIN allocation a ON e.employee_id = a.employee_id 
             WHERE (e.fname LIKE '$name%' OR e.lname LIKE '$name%' OR e.employee_id LIKE '$name%')";

if ($status !== '') {
    if ($status === "1") {
        $countSql .= " AND EXISTS (SELECT 1 FROM allocation a2 
                      WHERE a2.employee_id = e.employee_id AND a2.status = 1)";
    } elseif ($status === "0") {
        $countSql .= " AND NOT EXISTS (SELECT 1 FROM allocation a2 
                      WHERE a2.employee_id = e.employee_id AND a2.status = 1)";
    }
}

$totalQuery = mysqli_query($conn, $countSql);
$totalRows = mysqli_fetch_assoc($totalQuery)['total'];
$totalPages = ceil($totalRows / $limit);

// Return data with pagination info
echo json_encode(['data' => $data, 'totalPages' => $totalPages]);
?>