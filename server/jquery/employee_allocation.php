<?php
/**
 * Asset Allocation Details Script
 * 
 * This script retrieves asset details associated with a computer name ID.
 * It handles the fetching of serialized asset IDs and their corresponding details.
 * 
 * @method POST
 * @param {string} assetID - The computer name ID to look up
 * @return JSON Array of asset details or error message
 */

include '../connections.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Sanitize input to prevent SQL injection
    $cname_id = mysqli_real_escape_string($conn, $_POST['assetID']);

    // Step 2: Fetch the serialized assets_id from the computer table
    $sql = "SELECT assets_id FROM computer WHERE cname_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cname_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $computerData = $result->fetch_assoc();

    if ($computerData) {
        // Step 3: Unserialize the assets_id array from the database
        $assets_ids = unserialize($computerData['assets_id']);

        if (!empty($assets_ids)) {
            // Step 4: Create SQL placeholders for the IN clause
            $placeholders = implode(',', array_fill(0, count($assets_ids), '?'));

            // Step 5: Prepare query to fetch asset details
            $fetchAssets = $conn->prepare("
                SELECT assets_id, assets, brand, model, sn 
                FROM assets 
                WHERE assets_id IN ($placeholders)
            ");

            // Step 6: Bind all asset IDs as parameters
            $fetchAssets->bind_param(str_repeat('s', count($assets_ids)), ...$assets_ids);
            $fetchAssets->execute();
            $assetsResult = $fetchAssets->get_result();

            // Step 7: Build response array
            $data = [];
            while ($row = $assetsResult->fetch_assoc()) {
                $data[] = [
                    'assets_id' => $row['assets_id'],
                    'assets' => $row['assets'],
                    'brand' => $row['brand'],
                    'model' => $row['model'],
                    'sn' => $row['sn']
                ];
            }

            // Return JSON response
            echo json_encode($data);
            
            // Clean up
            $fetchAssets->close();
        } else {
            echo json_encode(['message' => 'No assets found for this computer']);
        }
    } else {
        echo json_encode(['message' => 'Computer not found']);
    }

    // Clean up resources
    $stmt->close();
    $conn->close();
}
?>