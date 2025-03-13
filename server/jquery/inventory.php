<?php
/**
 * Inventory Management Script
 * 
 * This script handles the fetching and filtering of computer inventory data.
 * It provides pagination, search functionality, and status tracking for computers.
 * 
 * @method POST
 * @param {string} name - Search term for computer name or ID
 * @param {string} pcFilter - Filter by availability status ('Available' or 'Not Available')
 * @param {number} page - Page number for pagination (default: 1)
 * @return JSON {data: Array, totalPages: number} Returns computer data and pagination info
 */

include '../connections.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize pagination parameters
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $perPage = 10;
    $offset = ($page - 1) * $perPage;

    // Sanitize input parameters
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $pcFilter = isset($_POST['pcFilter']) ? mysqli_real_escape_string($conn, $_POST['pcFilter']) : '';

    // Build base query with search conditions
    $sql = "SELECT * FROM computer 
            WHERE (cname LIKE ? OR cname_id LIKE ?)";
    
    // Add status filter if specified
    if ($pcFilter === 'Available') {
        $sql .= " AND status = 0";
    } elseif ($pcFilter === 'Not Available') {
        $sql .= " AND status = 1";
    }

    // Add grouping and pagination
    $sql .= " GROUP BY cname LIMIT ? OFFSET ?";
    
    // Prepare and execute the main query
    $stmt = $conn->prepare($sql);
    $searchParam = $name . '%';
    $stmt->bind_param("ssii", $searchParam, $searchParam, $perPage, $offset);
    $stmt->execute();
    $query = $stmt->get_result();

    // Build response data array
    $data = [];
    while ($row = $query->fetch_assoc()) {
        $data[] = [
            'cname_id' => $row['cname_id'],
            'cname' => $row['cname'],
            'status' => $row['status'] == 1 ? 'Not Available' : 'Available'
        ];
    }

    // Count total records for pagination
    $countSql = "SELECT COUNT(DISTINCT cname) AS total 
                 FROM computer 
                 WHERE (cname LIKE ? OR cname_id LIKE ?)";
    
    if ($pcFilter === 'Available') {
        $countSql .= " AND status = 0";
    } elseif ($pcFilter === 'Not Available') {
        $countSql .= " AND status = 1";
    }

    // Execute count query
    $countStmt = $conn->prepare($countSql);
    $countStmt->bind_param("ss", $searchParam, $searchParam);
    $countStmt->execute();
    $totalRows = $countStmt->get_result()->fetch_assoc()['total'];
    $totalPages = max(ceil($totalRows / $perPage), 1);

    // Return appropriate JSON response
    echo empty($data) ? 
        json_encode(['message' => 'No assets found', 'totalPages' => 1]) : 
        json_encode(['data' => $data, 'totalPages' => $totalPages]);

    // Clean up
    $stmt->close();
    $countStmt->close();
    $conn->close();
}
?>
