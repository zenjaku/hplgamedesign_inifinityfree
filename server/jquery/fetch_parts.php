<?php
/**
 * Parts Fetching Script
 * 
 * This script retrieves available computer parts that are not currently allocated.
 * It handles pagination and filtering of parts based on search criteria.
 * 
 * @method POST
 * @param {string} name - Search term for filtering parts
 * @param {array} exclude - Array of part IDs to exclude from results
 * @param {number} page - Page number for pagination (default: 1)
 * @return JSON {data: Array, totalPages: number} Returns parts data and pagination info
 */

include '../connections.php';

// Initialize pagination parameters
$limit = 10;  // Number of items per page
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$offset = ($page - 1) * $limit;

// Sanitize input parameters
$name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
$exclude = $_POST['exclude'] ?? [];

// Get currently allocated parts from computer table
$addedPartsQuery = "SELECT assets_id FROM computer";
$addedPartsResult = mysqli_query($conn, $addedPartsQuery);
$addedParts = [];

// Process allocated parts
while ($row = mysqli_fetch_assoc($addedPartsResult)) {
    $unserialized = unserialize($row['assets_id']);
    if (is_array($unserialized)) {
        $addedParts = array_merge($addedParts, $unserialized);
    }
}

// Merge excluded items with already allocated parts
$exclude = array_merge($exclude, $addedParts);

// Prepare SQL conditions for filtering
$conditions = [];
if (!empty($exclude)) {
    $excludeIds = "'" . implode("','", array_map(function($id) use ($conn) {
        return mysqli_real_escape_string($conn, $id);
    }, $exclude)) . "'";
    $conditions[] = "assets_id NOT IN ($excludeIds)";
}

if (!empty($name)) {
    $conditions[] = "sn LIKE '$name%'";
}

// Add condition to exclude records where status is 1
$conditions[] = "status != 1";

// Build and execute main query
$sql = "SELECT * FROM assets";
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Get total count for pagination
$totalCountQuery = "SELECT COUNT(*) AS total FROM assets";
if (!empty($conditions)) {
    $totalCountQuery .= " WHERE " . implode(" AND ", $conditions);
}
$totalCountResult = mysqli_query($conn, $totalCountQuery);
$totalRows = mysqli_fetch_assoc($totalCountResult)['total'];
$totalPages = ($totalRows > 0) ? ceil($totalRows / $limit) : 1;

// Add pagination to main query
$sql .= " ORDER BY assets_id ASC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Build response data
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Return JSON response
echo json_encode([
    'data' => $data,
    'totalPages' => $totalPages
]);

// Clean up resources
mysqli_free_result($result);
mysqli_free_result($totalCountResult);
mysqli_close($conn);
?>