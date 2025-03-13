<?php
$searchEmployee = $_GET['searchEmployee'] ?? '';
$id = $_GET['employee_id'] ?? '';

$searchEmployee = mysqli_real_escape_string($conn, $searchEmployee);
$id = mysqli_real_escape_string($conn, $id);

$employeeID = mysqli_query($conn, "SELECT DISTINCT employee_id FROM employee ORDER BY employee_id");

$limit = 10;
$pagination = isset($_GET['page']) && (int) $_GET['page'] > 0 ? (int) $_GET['page'] : 1;
$offset = ($pagination - 1) * $limit;

$baseQuery = "SELECT * FROM employee WHERE 1";

// Add search conditions
if (!empty($searchEmployee)) {
    $searchTerms = explode(' ', $searchEmployee);
    $conditions = [];
    foreach ($searchTerms as $term) {
        $conditions[] = "fname LIKE '%$term%' OR lname LIKE '%$term%' OR employee_id LIKE '%$term%'";
    }
    $baseQuery .= " AND (" . implode(' OR ', $conditions) . ")";
}

// Handle exact ID search
if (!empty($id) && $id !== 'Employee ID') {
    $baseQuery .= " AND employee_id = '$id'";
}

// Total rows for pagination
$totalRowsResult = mysqli_query($conn, str_replace("*", "COUNT(*) AS total", $baseQuery));
$totalRows = mysqli_fetch_assoc($totalRowsResult)['total'] ?? 0;
$totalPages = $totalRows > 0 ? ceil($totalRows / $limit) : 1;

// Final query with LIMIT and OFFSET
$fetchData = $baseQuery . " ORDER BY employee_id ASC LIMIT $limit OFFSET $offset";
$searchResult = mysqli_query($conn, $fetchData);
?>