<?php
include '../connections.php';

$page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
$limit = 10;  // Number of records per page
$offset = ($page - 1) * $limit;

// Sanitize input for search
$name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';

// Base query - modified to properly check allocation status
$sql = "SELECT * FROM employee WHERE status = 'RESIGNED'";

// Add pagination
$sql .= " ORDER BY updated_at DESC LIMIT $limit OFFSET $offset";

// Execute the query
$query = mysqli_query($conn, $sql);

// Fetch the data
$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = [
        'employee_id' => $row['employee_id'],
        'fname' => $row['fname'],
        'lname' => $row['lname'],
    ];
}

// Count total rows for pagination (without LIMIT and OFFSET)
$countSql = "SELECT COUNT(DISTINCT employee_id) AS total 
             FROM employee WHERE status = 'RESIGNED'";

$totalQuery = mysqli_query($conn, $countSql);
$totalRows = mysqli_fetch_assoc($totalQuery)['total'];
$totalPages = ceil($totalRows / $limit);

// Return data with pagination info
echo json_encode(['data' => $data, 'totalPages' => $totalPages]);
?>