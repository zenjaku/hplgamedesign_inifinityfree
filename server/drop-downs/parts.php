<?php
/**
 * Parts Selection Script
 * 
 * This script fetches available parts for computer assembly,
 * excluding parts that have already been selected.
 * 
 * @param {array} selected_assets - Array of already selected assets
 * @return Array List of available parts
 */

// Step 1: Get and sanitize selected assets
$selected_assets = $_GET['selected_assets'] ?? [];
$selected_assets = array_map(function($asset) use ($conn) {
    return mysqli_real_escape_string($conn, $asset);
}, $selected_assets);

// Step 2: Build query to fetch available parts
$assetsQuery = "SELECT DISTINCT assets, brand, model FROM assets";

// Step 3: Exclude already selected parts
if (!empty($selected_assets)) {
    $assetsQuery .= " WHERE CONCAT(assets, ' ', brand, ' ', model) NOT IN ('" . implode("','", $selected_assets) . "')";
}

$assetsResult = mysqli_query($conn, $assetsQuery);
?>