<?php
/**
 * Asset Details Fetching Script
 * 
 * This script ensures all "Available" assets are displayed first across pages.
 * Only after they are exhausted, "Installed" and "Defective" assets will appear.
 */

require_once '../connections.php';

// Sanitize and validate input parameters
$asset = isset($_POST['asset']) ? mysqli_real_escape_string($conn, $_POST['asset']) : '';
$limit = 10; // Number of records per page
$page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
$offset = ($page - 1) * $limit;

// Step 1: Fetch all assets and determine their status
$allAssetsStmt = $conn->prepare("SELECT * FROM assets WHERE assets = ?");
$allAssetsStmt->bind_param("s", $asset);
$allAssetsStmt->execute();
$result = $allAssetsStmt->get_result();

$availableAssets = [];
$installedAssets = [];
$defectiveAssets = [];

while ($row = $result->fetch_assoc()) {
    $statusText = "Available";
    $installedTo = "N/A";
    $assetId = $row['assets_id'];

    if ($row['status'] == 1) {
        // If status = 1, it's defective
        $statusText = "Defective";
        $defectiveAssets[] = [
            'brand' => $row['brand'],
            'model' => $row['model'],
            'sn' => $row['sn'],
            'status' => $statusText,
            'installed_to' => $installedTo
        ];
    } else {
        // Check if asset is installed
        $computerStmt = $conn->prepare("SELECT cname_id, assets_id FROM computer WHERE cname_id IS NOT NULL");
        $computerStmt->execute();
        $computerResult = $computerStmt->get_result();

        while ($compRow = $computerResult->fetch_assoc()) {
            $assets = unserialize($compRow['assets_id']);

            if (is_array($assets) && in_array($assetId, $assets)) {
                // If found in computer table, mark as installed
                $statusText = "Installed";
                $installedTo = $compRow['cname_id'];
                $installedAssets[] = [
                    'brand' => $row['brand'],
                    'model' => $row['model'],
                    'sn' => $row['sn'],
                    'status' => $statusText,
                    'installed_to' => $installedTo
                ];
                break;
            }
        }
        $computerStmt->close();

        if ($statusText === "Available") {
            // If not found in computer table, keep as available
            $availableAssets[] = [
                'brand' => $row['brand'],
                'model' => $row['model'],
                'sn' => $row['sn'],
                'status' => $statusText,
                'installed_to' => $installedTo
            ];
        }
    }
}

// Step 2: Merge all assets in the correct order
$orderedAssets = array_merge($availableAssets, $installedAssets, $defectiveAssets);

// Step 3: Paginate the sorted list
$totalAssets = count($orderedAssets);
$totalPages = ceil($totalAssets / $limit);
$paginatedAssets = array_slice($orderedAssets, $offset, $limit);

// Return JSON response
echo json_encode([
    'data' => $paginatedAssets,
    'totalPages' => $totalPages
]);

// Clean up
$allAssetsStmt->close();
$conn->close();
?>
