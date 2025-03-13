<?php
if (isset($_POST['build-pc'])) {
    $cname = $_POST['cname'];  // Single value (not an array)
    $assets_id = $_POST['assets_id'];  // Array of asset IDs

    // Ensure $assets_id is properly serialized
    $serialized_assets_id = serialize($assets_id);

    // Generate timestamp
    $microtime = microtime(true);
    $created_at = date('Y-m-d H:i:s', (int) $microtime) . '.' . substr($microtime, -3);

    // Create cname_id
    $cname_id = $cname . '_' . $created_at;

    // Prepare the statement
    $store = "INSERT INTO computer (cname_id, cname, assets_id, created_at) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($store);

    if (!$stmt) {
        $_SESSION['status'] = 'failed';
        $_SESSION['failed'] = 'Database error: ' . $conn->error;
        echo "<script> window.location = '/build'; </script>";
        exit();
    }

    // Bind serialized assets_id
    $stmt->bind_param("ssss", $cname_id, $cname, $serialized_assets_id, $created_at);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    $_SESSION['status'] = 'success';
    $_SESSION['success'] = 'Assets saved successfully';
    echo "<script> window.location = '/build'; </script>";
    exit();
}
?>
