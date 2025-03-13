<?php
/**
 * Location Management Script
 * 
 * This script handles the fetching of Philippine geographical data:
 * - Regions
 * - Provinces
 * - Cities/Municipalities
 * 
 * @method POST
 * @param {string} action - Type of location data to fetch ('getRegions', 'getProvinces', 'getCities')
 * @param {string} region_code - [Optional] Region code for fetching provinces
 * @param {string} province_code - [Optional] Province code for fetching cities
 * @return JSON Array of location data based on the requested action
 */

include '../connections.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle region list request
    if (isset($_POST['action']) && $_POST['action'] === 'getRegions') {
        // Prepare and execute query for regions
        $stmt = $conn->prepare("SELECT * FROM tblregion ORDER BY region_m ASC");
        $stmt->execute();
        $result = $stmt->get_result();

        // Build response array
        $regions = [];
        while ($row = $result->fetch_assoc()) {
            $regions[] = [
                'region_c' => $row['region_c'],
                'region_m' => $row['region_m'],
                'region_n' => $row['region_n']
            ];
        }
        echo json_encode($regions);
        $stmt->close();
    }

    // Handle province list request
    if (isset($_POST['action']) && $_POST['action'] === 'getProvinces' && isset($_POST['region_code'])) {
        // Sanitize input
        $region_code = mysqli_real_escape_string($conn, $_POST['region_code']);

        // Prepare and execute query for provinces
        $stmt = $conn->prepare("SELECT * FROM tblprovince WHERE region_c = ?");
        $stmt->bind_param("s", $region_code);
        $stmt->execute();
        $result = $stmt->get_result();

        // Build response array
        $provinces = [];
        while ($row = $result->fetch_assoc()) {
            $provinces[] = [
                'province_c' => $row['province_c'],
                'province_m' => $row['province_m'],
                'province_n' => $row['province_n']
            ];
        }
        echo json_encode($provinces);
        $stmt->close();
    }

    // Handle city/municipality list request
    if (isset($_POST['action']) && $_POST['action'] === 'getCities' && isset($_POST['province_code'])) {
        // Sanitize input
        $province_code = mysqli_real_escape_string($conn, $_POST['province_code']);

        // Prepare and execute query for cities/municipalities
        $stmt = $conn->prepare("SELECT * FROM tblcitymun WHERE province_c = ?");
        $stmt->bind_param("s", $province_code);
        $stmt->execute();
        $result = $stmt->get_result();

        // Build response array
        $cities = [];
        while ($row = $result->fetch_assoc()) {
            $cities[] = [
                'citymun_c' => $row['citymun_c'],
                'citymun_m' => $row['citymun_m'],
                'citymun_n' => $row['citymun_n']
            ];
        }
        echo json_encode($cities);
        $stmt->close();
    }

    // Clean up connection
    $conn->close();
}
?>
