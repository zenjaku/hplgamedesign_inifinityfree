<?php
// Fetch Computer Specifications
if (isset($_GET['cname_id']) && !empty($_GET['cname_id'])) {
    $cname_id = $_GET['cname_id'];

    // Fetch computer record
    $fetchComputer = $conn->prepare("SELECT cname, assets_id FROM computer WHERE cname_id = ?");
    $fetchComputer->bind_param("s", $cname_id);
    $fetchComputer->execute();
    $result = $fetchComputer->get_result();
    $computerData = $result->fetch_assoc();

    $assetsResult = false;

    if ($computerData) {
        $cname = $computerData['cname']; // Get computer name
        $assets_ids = unserialize($computerData['assets_id']); // Unserialize assets_id

        if (!empty($assets_ids)) {
            // Prepare placeholders for query
            $placeholders = implode(',', array_fill(0, count($assets_ids), '?'));

            // Fetch asset details
            $fetchAssets = $conn->prepare("
                SELECT assets_id, assets, sn, brand, model 
                FROM assets 
                WHERE assets_id IN ($placeholders)
            ");

            // Bind parameters dynamically
            $fetchAssets->bind_param(str_repeat('s', count($assets_ids)), ...$assets_ids);
            $fetchAssets->execute();
            $assetsResult = $fetchAssets->get_result();

            if (!$assetsResult) {
                die("Query failed: " . $conn->error);
            }
            // $rows = $assetsResult->fetch_all(MYSQLI_ASSOC);
        }
        // else {
        //     $rows = [];
        // }
    } else {
        $cname = 'N/A';
        $rows = [];
    }
}
?>