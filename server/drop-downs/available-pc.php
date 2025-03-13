<?php
/**
 * Available PC Fetching Script
 * 
 * This script fetches available computers based on asset compatibility.
 * It checks if a computer already has similar components installed
 * (e.g., won't show PCs that already have a GPU when adding a GPU).
 * 
 * @param {string} assets_id - Asset ID from URL to check compatibility
 * @return Array List of compatible computers
 */

// Step 1: Get and validate asset ID
$asset_id = $_GET['assets_id'] ?? '';
if (empty($asset_id)) {
    die("Invalid asset ID.");
}

// Step 2: Fetch asset type information
$assetTypeQuery = "SELECT assets FROM assets WHERE assets_id = ?";
$stmt = mysqli_prepare($conn, $assetTypeQuery);
mysqli_stmt_bind_param($stmt, "s", $asset_id);
mysqli_stmt_execute($stmt);
$assetTypeResult = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($assetTypeResult) === 0) {
    die("Asset not found.");
}

$assetTypeData = mysqli_fetch_assoc($assetTypeResult);
$assetType = $assetTypeData['assets'] ?? null;

// Step 3: Determine if asset type requires exclusivity
$excludeComputers = in_array($assetType, ['MOTHERBOARD', 'PROCESSOR', 'GPU', 'POWER SUPPLY']);

// Step 4: Fetch all computers
$cnameIdQuery = "SELECT computer.cname, computer.cname_id, computer.assets_id FROM computer ORDER BY computer.cname";
$computerResult = mysqli_query($conn, $cnameIdQuery);

$availableAssets = [];

// Step 5: Process each computer
while ($row = mysqli_fetch_assoc($computerResult)) {
    $assets_ids = !empty($row['assets_id']) ? unserialize($row['assets_id']) : [];
    if (!is_array($assets_ids)) {
        $assets_ids = [];
    }

    // Add computers with no assets
    if (empty($assets_ids)) {
        $availableAssets[] = $row;
        continue;
    }

    // Step 6: Check asset compatibility
    $assetTypesInComputer = [];
    foreach ($assets_ids as $id) {
        $assetTypeQuery = "SELECT assets FROM assets WHERE assets_id = ?";
        $stmt = mysqli_prepare($conn, $assetTypeQuery);
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $assetTypeResult = mysqli_stmt_get_result($stmt);

        if ($assetTypeResult && mysqli_num_rows($assetTypeResult) > 0) {
            $assetTypeData = mysqli_fetch_assoc($assetTypeResult);
            $assetTypesInComputer[] = $assetTypeData['assets'];
        }
    }

    // Skip if computer already has similar component
    if ($excludeComputers && in_array($assetType, $assetTypesInComputer)) {
        continue;
    }

    $availableAssets[] = $row;
}
?>