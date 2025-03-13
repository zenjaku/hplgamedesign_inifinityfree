<?php
/**
 * Fetch Assets Script
 * 
 * This script retrieves available assets that are not currently selected.
 * Used in the asset selection dropdown to prevent duplicate selections.
 * 
 * @method GET
 * @param {string[]} selected_assets - Array of already selected asset combinations
 * @return string HTML options for select dropdown
 */

include '../connections.php';

// Decode and sanitize the selected assets array from GET parameter
$selected_assets = json_decode($_GET['selected_assets'] ?? '[]', true);
$selected_assets = array_map(function($asset) use ($conn) {
    return mysqli_real_escape_string($conn, $asset);
}, $selected_assets);

// Prepare query to fetch distinct asset combinations
$stmt = $conn->prepare("SELECT DISTINCT assets, brand, model FROM assets");

// Add WHERE clause if there are selected assets to exclude
if (!empty($selected_assets)) {
    $placeholders = str_repeat('?,', count($selected_assets) - 1) . '?';
    $stmt = $conn->prepare("
        SELECT DISTINCT assets, brand, model 
        FROM assets 
        WHERE CONCAT(assets, ' ', brand, ' ', model) NOT IN ($placeholders)
    ");
    $stmt->bind_param(str_repeat('s', count($selected_assets)), ...$selected_assets);
}

$stmt->execute();
$result = $stmt->get_result();

// Build options string based on query results
if ($result->num_rows > 0) {
    $options = '<option selected disabled>Select Components</option>';
    while ($row = $result->fetch_assoc()) {
        $optionValue = htmlspecialchars($row['assets'] . ' ' . $row['brand'] . ' ' . $row['model']);
        $options .= "<option value='{$optionValue}'>{$optionValue}</option>";
    }
} else {
    $options = '<option selected disabled>No other assets found</option>';
}

// Clean up and return response
$stmt->close();
$conn->close();
echo $options;
?>
