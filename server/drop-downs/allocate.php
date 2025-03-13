<?php
/**
 * Allocation Management Script
 * 
 * This script handles the fetching of available employees and computers for allocation.
 * It ensures that only eligible employees and unallocated computers are displayed.
 * 
 * Key functions:
 * 1. Fetch unallocated employees
 * 2. Fetch available computers
 * 3. Handle computer name filtering
 */

// Step 1: Get and sanitize employee ID from URL
$id = $_GET['employee_id'] ?? '';
$id = mysqli_real_escape_string($conn, $id);

// Step 2: Query to fetch eligible employees (unallocated or returned)
$employeeIDQuery = "
    SELECT e.employee_id, e.fname, e.lname
    FROM employee e
    WHERE 
        (
            /* Check if last allocation is inactive */
            (SELECT a.status 
             FROM allocation a
             WHERE a.employee_id = e.employee_id
             ORDER BY a.created_at DESC
             LIMIT 1) = 0
        )
        OR 
        /* Check if employee has no allocations */
        NOT EXISTS (
            SELECT 1 
            FROM allocation a
            WHERE a.employee_id = e.employee_id
        )
    ORDER BY e.employee_id
";

// Execute employee query
$employeeIDResult = mysqli_query($conn, $employeeIDQuery);
$employeeIDs = mysqli_fetch_all($employeeIDResult, MYSQLI_ASSOC);

// Step 3: Get and sanitize computer name filter
$asset_name = $_GET['cname_id'] ?? '';
$assetName = mysqli_real_escape_string($conn, $asset_name);

// Step 4: Query to fetch available computers (status = 0 means available)
$assetsQuery = "
    SELECT status, cname, cname_id 
    FROM computer
    WHERE status = 0
    GROUP BY cname
    ORDER BY cname
";

// Execute computer query
$assetsResult = mysqli_query($conn, $assetsQuery);

// Step 5: Base query for asset search with optional name filter
$assetsSearch = "SELECT * FROM assets WHERE 1";
if (!empty($assetName) && $assetName !== 'Computer Name') {
    $assetsSearch .= " AND cname = '$assetName'";
}

// Step 6: Query to fetch available computer IDs
$cnameIdQuery = "
    SELECT 
        computer.cname, 
        computer.cname_id, 
        computer.assets_id
    FROM computer
    WHERE NOT EXISTS (
        SELECT 1
        FROM allocation
        WHERE allocation.cname_id = computer.cname_id
    )
    GROUP BY computer.cname_id
    ORDER BY computer.cname
";

// Execute computer ID query
$computerResult = mysqli_query($conn, $cnameIdQuery);

// Step 7: Build available assets array
$availableAssets = [];
while ($row = mysqli_fetch_assoc($computerResult)) {
    $availableAssets[] = $row;
}
?>