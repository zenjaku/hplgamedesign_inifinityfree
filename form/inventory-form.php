<?php

if (isset($_POST['addAssets'])) {
    $assets = $_POST['assets'];  // Array
    $brands = $_POST['brand'];  // Array
    $models = $_POST['model'];  // Array
    $sns = $_POST['sn'];  // Array

    $microtime = microtime(true); // Get current microtime as a float
    $milliseconds = sprintf('%03d', ($microtime - floor($microtime)) * 1000); // Extract milliseconds
    $created_at = date('Y-m-d H:i:s', (int) $microtime) . '.' . $milliseconds;

    // Prepare the statement for inserting assets
    $store = "INSERT INTO assets (assets_id, assets, brand, model, sn, created_at) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($store);

    if (!$stmt) {
        $_SESSION['status'] = 'failed';
        $_SESSION['failed'] = 'Database error: ' . $conn->error;
        echo "<script> window.location = '/add'; </script>";
        exit();
    }

    // Loop through each set of inputs
    for ($i = 0; $i < count($assets); $i++) {
        $assetName = strtoupper($assets[$i]); // Convert to uppercase
        $brandName = strtoupper($brands[$i]); // Convert to uppercase
        $modelName = strtoupper($models[$i]); // Convert to uppercase
        $serialNumber = strtoupper($sns[$i]); // Convert to uppercase

        // Use the same $created_at value for all assets
        $createdAt = $created_at;

        // Generate a unique asset ID (check for existing asset_id)
        $assets_id = $assetName . '_' . rand(000000, 999999);

        // Ensure uniqueness of the assets_id (loop to check if it exists in the database)
        $checkAssetsIdQuery = "SELECT COUNT(*) FROM assets WHERE assets_id = ?";
        $checkStmt = $conn->prepare($checkAssetsIdQuery);
        $checkStmt->bind_param("s", $assets_id);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        // If assets_id already exists, generate a new one
        while ($count > 0) {
            $assets_id = $assetName . '_' . rand(000000, 999999); // Regenerate the ID
            $checkStmt = $conn->prepare($checkAssetsIdQuery);
            $checkStmt->bind_param("s", $assets_id);
            $checkStmt->execute();
            $checkStmt->bind_result($count);
            $checkStmt->fetch();
            $checkStmt->close();
        }

        // Bind parameters for each row
        $stmt->bind_param("ssssss", $assets_id, $assetName, $brandName, $modelName, $serialNumber, $createdAt);
        $stmt->execute();  // Execute the statement
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    $_SESSION['status'] = 'success';
    $_SESSION['success'] = 'Assets saved successfully';
    echo "<script> window.location = '/add'; </script>";
    exit();
}

?>