<?php
include '../connections.php';

$id = isset($_GET['assets_id']) ? $_GET['assets_id'] : '';

if (!empty($id)) {
    $conn->begin_transaction(); // Start transaction

    try {
        // Step 1: Update assets table to mark as defective
        $status = 1;
        $updateStatus = $conn->prepare("UPDATE assets SET status = ? WHERE assets_id = ?");
        $updateStatus->bind_param("is", $status, $id);
        $updateStatus->execute();
        $updateStatus->close();

        // Step 2: Remove asset from the computer table
        $getComputers = $conn->prepare("SELECT cname_id, assets_id FROM computer WHERE assets_id LIKE ?");
        $likeId = '%"' . $id . '"%'; // Check if the asset ID is in serialized format
        $getComputers->bind_param("s", $likeId);
        $getComputers->execute();
        $result = $getComputers->get_result();

        while ($row = $result->fetch_assoc()) {
            $cnameId = $row['cname_id'];
            $assets = unserialize($row['assets_id']);

            // Remove defective asset from the array
            if (($key = array_search($id, $assets)) !== false) {
                unset($assets[$key]);
            }

            // Re-serialize the updated asset list
            $updatedAssets = serialize(array_values($assets));

            // Update the computer record
            $updateComputer = $conn->prepare("UPDATE computer SET assets_id = ? WHERE cname_id = ?");
            $updateComputer->bind_param("ss", $updatedAssets, $cnameId);
            $updateComputer->execute();
            $updateComputer->close();
        }
        $getComputers->close();

        $conn->commit(); // Commit transaction

        $_SESSION['status'] = 'success';
        $_SESSION['success'] = "Asset has been tagged as defective and removed from any installed computer.";
    } catch (Exception $e) {
        $conn->rollback(); // Rollback in case of an error
        $_SESSION['status'] = 'failed';
        $_SESSION['failed'] = "An error occurred. Please try again later.";
    }

    echo "<script> window.location = '/parts'; </script>";
} else {
    $_SESSION['status'] = 'failed';
    $_SESSION['failed'] = "Invalid asset ID.";
    echo "<script> window.location = '/parts'; </script>";
}

$conn->close();
?>