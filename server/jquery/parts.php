<?php
include '../connections.php';

$page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
$limit = 10;  // Number of records per page
$offset = ($page - 1) * $limit;

// Sanitize input for search
$name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
$assetFilter = isset($_POST['assetFilter']) ? mysqli_real_escape_string($conn, $_POST['assetFilter']) : '';
$statusFilter = isset($_POST['statusFilter']) ? mysqli_real_escape_string($conn, $_POST['statusFilter']) : '';

// Step 1: Fetch all computer records to get installed asset IDs
$computerQuery = mysqli_query($conn, "SELECT cname, cname_id, assets_id FROM computer");
$computerAssets = [];
$installedAssetIds = [];
while ($row = mysqli_fetch_assoc($computerQuery)) {
    $assetsArray = unserialize($row['assets_id']);
    if (is_array($assetsArray)) {
        foreach ($assetsArray as $asset) {
            $computerAssets[$asset] = [
                'cname' => $row['cname'],
                'cname_id' => $row['cname_id']
            ];
            $installedAssetIds[] = $asset;
        }
    }
}

// Step 2: Fetch all unique asset names for the filter
$assetNamesQuery = mysqli_query($conn, "SELECT DISTINCT assets FROM assets");
$assetNames = [];
while ($row = mysqli_fetch_assoc($assetNamesQuery)) {
    $assetNames[] = $row['assets'];
}

// Step 3: Build the SQL query with status filter
$sql = "SELECT assets_id, assets, brand, model, sn, status FROM assets WHERE (assets LIKE '$name%' OR brand LIKE '$name%' OR model LIKE '$name%' OR sn LIKE '$name%')";

// Apply asset filter
if ($assetFilter !== '') {
    $sql .= " AND assets = '$assetFilter'";
}

// Apply status filter
if ($statusFilter !== '') {
    switch ($statusFilter) {
        case 'Installed':
            if (!empty($installedAssetIds)) {
                $installedList = implode("','", $installedAssetIds);
                $sql .= " AND assets_id IN ('$installedList')";
            } else {
                $sql .= " AND 0"; // No installed assets, return nothing
            }
            break;
        case 'Available':
            $sql .= " AND status = 0";
            if (!empty($installedAssetIds)) {
                $installedList = implode("','", $installedAssetIds);
                $sql .= " AND assets_id NOT IN ('$installedList')";
            }
            break;
        case 'Defective':
            $sql .= " AND status = 1";
            break;
    }
}

$sql .= " LIMIT $limit OFFSET $offset";
$query = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $assetID = $row['assets_id'];

    // Determine the status and associated computer
    $row['cname'] = '';
    $row['cname_id'] = '';

    if (isset($computerAssets[$assetID])) {
        $row['status'] = 'Installed';
        $row['cname'] = $computerAssets[$assetID]['cname'];
        $row['cname_id'] = $computerAssets[$assetID]['cname_id'];
    } elseif ($row['status'] == 1) {
        $row['status'] = 'Defective';
    } else {
        $row['status'] = 'Available';
    }

    $data[] = $row;
}

// Step 4: Count total records with the same filters
$countSql = "SELECT COUNT(*) AS total FROM assets WHERE (assets LIKE '$name%' OR brand LIKE '$name%' OR model LIKE '$name%' OR sn LIKE '$name%')";

if ($assetFilter !== '') {
    $countSql .= " AND assets = '$assetFilter'";
}

// Apply status filter to count query
if ($statusFilter !== '') {
    switch ($statusFilter) {
        case 'Installed':
            if (!empty($installedAssetIds)) {
                $installedList = implode("','", $installedAssetIds);
                $countSql .= " AND assets_id IN ('$installedList')";
            } else {
                $countSql .= " AND 0";
            }
            break;
        case 'Available':
            $countSql .= " AND status = 0";
            if (!empty($installedAssetIds)) {
                $installedList = implode("','", $installedAssetIds);
                $countSql .= " AND assets_id NOT IN ('$installedList')";
            }
            break;
        case 'Defective':
            $countSql .= " AND status = 1";
            break;
    }
}

$totalQuery = mysqli_query($conn, $countSql);
$totalRows = mysqli_fetch_assoc($totalQuery)['total'];
$totalPages = ceil($totalRows / $limit);

// Return data with pagination info and asset names
echo json_encode(['data' => $data, 'totalPages' => $totalPages, 'assetNames' => $assetNames]);
?>