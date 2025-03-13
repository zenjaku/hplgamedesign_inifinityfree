<?php
/**
 * Return Allocations Script
 * 
 * This script retrieves details of assets allocated to an employee
 * for the return/deallocation process.
 * 
 * @method POST
 * @param {string} returnID - Employee ID to check allocations for
 * @return JSON Array of allocated assets or error message
 */

include '../connections.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $returnID = mysqli_real_escape_string($conn, $_POST['returnID']);

    // Step 1: Get serialized assets_id from the computer table
    // Only fetch computers that are currently allocated to the employee
    $computerQuery = "SELECT assets_id 
                     FROM computer 
                     WHERE cname_id IN (
                         SELECT cname_id 
                         FROM allocation 
                         WHERE employee_id = ? AND status = 1
                     )";
    $stmt = $conn->prepare($computerQuery);
    $stmt->bind_param("s", $returnID);
    $stmt->execute();
    $computerResult = $stmt->get_result();

    // Process all assets from allocated computers
    $allAssets = [];
    while ($row = $computerResult->fetch_assoc()) {
        // Unserialize the stored assets_id array
        $assetsArray = unserialize($row['assets_id']);
        if (is_array($assetsArray)) {
            $allAssets = array_merge($allAssets, $assetsArray);
        }
    }

    // Step 2: Check if any assets were found
    if (empty($allAssets)) {
        echo json_encode(['message' => 'No assets found']);
        exit();
    }

    // Step 3: Fetch details for all found assets
    // Create placeholders for the IN clause
    $placeholders = str_repeat('?,', count($allAssets) - 1) . '?';
    $assetQuery = "SELECT assets_id, assets, brand, model, sn 
                   FROM assets 
                   WHERE assets_id IN ($placeholders)";
    
    $stmt = $conn->prepare($assetQuery);
    $stmt->bind_param(str_repeat('s', count($allAssets)), ...$allAssets);
    $stmt->execute();
    $result = $stmt->get_result();

    // Step 4: Build response data array
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'assets_id' => $row['assets_id'],
            'assets' => $row['assets'],
            'brand' => $row['brand'],
            'model' => $row['model'],
            'sn' => $row['sn'],
        ];
    }

    // Return appropriate JSON response
    echo empty($data) ? 
        json_encode(['message' => 'No assets found']) : 
        json_encode($data);

    // Clean up
    $stmt->close();
    $conn->close();
}
?>