<?php
session_start();

// Debugging: Check if the script is accessed
error_log("Script accessed.");

// Check if the form is submitted
if (isset($_POST['addAsset'])) {
    error_log("Form submitted with addAsset.");

    // Validate inputs
    $cname_id = $_POST['cname_id'] ?? '';
    $assetID = $_SESSION['assetsID'] ?? '';

    // Debugging: Log inputs
    error_log("cname_id: " . $cname_id);
    error_log("assetID: " . $assetID);

    if (empty($cname_id) || empty($assetID)) {
        $_SESSION['status'] = 'failed';
        $_SESSION['failed'] = 'Invalid input!';
        echo "<script> window.location = '/parts'; </script>";
        exit();
    }

    try {
        // Fetch existing assets_id
        $query = $conn->prepare("SELECT assets_id FROM computer WHERE cname_id = ?");
        $query->bind_param("s", $cname_id);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows === 0) {
            $_SESSION['status'] = 'failed';
            $_SESSION['failed'] = 'Computer not found!';
            echo "<script> window.location = '/parts'; </script>";
            exit();
        }

        $row = $result->fetch_assoc();
        $existingAssets = unserialize($row['assets_id']);

        // Ensure assets_id is a valid array
        if ($existingAssets === false) {
            $existingAssets = [];
        }

        // Debugging: Log existing assets
        error_log("Existing assets: " . print_r($existingAssets, true));

        // Add new asset_id if not already present
        if (!in_array($assetID, $existingAssets)) {
            $existingAssets[] = $assetID;
        }

        // Debugging: Log updated assets
        error_log("Updated assets: " . print_r($existingAssets, true));

        // Serialize and update database
        $updatedAssets = serialize($existingAssets);
        $updateQuery = $conn->prepare("UPDATE computer SET assets_id = ? WHERE cname_id = ?");
        $updateQuery->bind_param("ss", $updatedAssets, $cname_id);

        if ($updateQuery->execute()) {
            $_SESSION['status'] = 'success';
            $_SESSION['success'] = 'Asset added successfully!';
        } else {
            $_SESSION['status'] = 'failed';
            $_SESSION['failed'] = 'Error updating asset: ' . $conn->error;
        }
    } catch (Exception $e) {
        // Handle any exceptions
        $_SESSION['status'] = 'failed';
        $_SESSION['failed'] = 'An error occurred: ' . $e->getMessage();
    } finally {
        // Unset the session variable regardless of success or failure
        unset($_SESSION['assetsID']);
        error_log("Session variable assetsID unset.");
    }

    echo "<script> window.location = '/parts'; </script>";
    exit();
} else {
    $_SESSION['status'] = 'failed';
    $_SESSION['failed'] = 'Invalid request!';
    echo "<script> window.location = '/parts'; </script>";
    exit();
}
?>