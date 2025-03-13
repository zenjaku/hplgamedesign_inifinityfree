<?php
$assets_id = $_GET['assets_id']; // The ID to be removed

// Fetch the asset name from the assets table
$assetStmt = $conn->prepare("SELECT assets FROM assets WHERE assets_id = ?");
$assetStmt->bind_param("s", $assets_id);
$assetStmt->execute();
$assetResult = $assetStmt->get_result();
$assetRow = $assetResult->fetch_assoc();
$assetName = $assetRow ? $assetRow['assets'] : "Unknown Asset"; // Default if not found

// Fetch the current serialized assets_id from the computer table
$stmt = $conn->prepare("SELECT assets_id FROM computer");
$stmt->execute();
$result = $stmt->get_result();

$success = false;

while ($row = $result->fetch_assoc()) {
    $storedAssets = unserialize($row['assets_id']); // Unserialize the stored array

    // Ensure $storedAssets is an array before proceeding
    if (is_array($storedAssets)) {
        // Remove the specific assets_id from the array
        $updatedAssets = array_diff($storedAssets, [$assets_id]);

        // Re-serialize the array
        $newSerializedAssets = serialize($updatedAssets);

        // Update the database with the modified serialized array
        $updateStmt = $conn->prepare("UPDATE computer SET assets_id = ? WHERE assets_id = ?");
        $updateStmt->bind_param("ss", $newSerializedAssets, $row['assets_id']);

        if ($updateStmt->execute()) {
            $success = true;
        }
    }
}

if ($success) {
    $_SESSION['status'] = 'success';
    $_SESSION['success'] = "$assetName has been successfully removed.";
} else {
    $_SESSION['status'] = 'failed';
    $_SESSION['failed'] = "Can't remove $assetName, please try again later.";
}

echo "<script> window.history.back(); </script>";
exit();

?>