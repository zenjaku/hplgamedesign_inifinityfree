<?php
include '../server/connections.php';
session_start();

// Start output buffering
ob_start();

// Generate SQL dump
$tables = $conn->query("SHOW TABLES");
$sqlDump = "";

while ($row = $tables->fetch_row()) {
    $tableName = $row[0];
    $createTable = $conn->query("SHOW CREATE TABLE `$tableName`")->fetch_row()[1];
    $sqlDump .= "$createTable;\n\n";

    $result = $conn->query("SELECT * FROM `$tableName`");
    while ($data = $result->fetch_assoc()) {
        $values = array_map(function ($value) use ($conn) {
            return "'" . $conn->real_escape_string($value) . "'";
        }, array_values($data));

        $columns = implode("`, `", array_keys($data));
        $valuesString = implode(", ", $values);
        $sqlDump .= "INSERT INTO `$tableName` (`$columns`) VALUES ($valuesString);\n";
    }
    $sqlDump .= "\n\n";
}

// Here we simulate saving the file and checking if it's successful
$backupFile = 'db_export_' . date('Y-m-d_H-i-s') . '.sql'; // Generate filename

// Simulate file saving process (you won't actually save the file, just check success)
$success = true; // In real case, you would use file_put_contents to save

// Simulating file creation success/failure
if ($success) {
    // File is "created" successfully, now send the download
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="' . $backupFile . '"');
    header('Content-Length: ' . strlen($sqlDump));

    // Clean the output buffer
    ob_clean();
    flush();

    // Output the SQL dump
    echo $sqlDump;

    // Set success message in session (for example purposes)
    $_SESSION['status'] = 'success';
    $_SESSION['success'] = "Database export initiated successfully!";

    // Close connection before redirect
    $conn->close();
} else {
    // File creation failed, set failure message in session
    $_SESSION['status'] = 'failed';
    $_SESSION['failed'] = 'Failed to create the database export file';
    // Redirect or perform other error handling
    header("Location: /inventory");
    exit();
}

exit(); // Ensure script stops here, preventing further execution





// // Start output buffering
// ob_start();

// // Generate SQL dump
// $tables = $conn->query("SHOW TABLES");
// $sqlDump = "";

// while ($row = $tables->fetch_row()) {
//     $tableName = $row[0];
//     $createTable = $conn->query("SHOW CREATE TABLE `$tableName`")->fetch_row()[1];
//     $sqlDump .= "$createTable;\n\n";

//     $result = $conn->query("SELECT * FROM `$tableName`");
//     while ($data = $result->fetch_assoc()) {
//         $values = array_map(function ($value) use ($conn) {
//             return "'" . $conn->real_escape_string($value) . "'";
//         }, array_values($data));

//         $columns = implode("`, `", array_keys($data));
//         $valuesString = implode(", ", $values);
//         $sqlDump .= "INSERT INTO `$tableName` (`$columns`) VALUES ($valuesString);\n";
//     }
//     $sqlDump .= "\n\n";
// }

// // Send the file as a download
// header('Content-Type: application/sql');
// header('Content-Disposition: attachment; filename="db_export.sql"');
// header('Content-Length: ' . strlen($sqlDump));
// ob_clean();
// flush();
// echo $sqlDump;
// $conn->close();
// exit();


// // Define the backup folder path
// $backupFolder = $_SERVER['DOCUMENT_ROOT'] . '/database/';

// // Ensure the directory exists or create it
// if (!is_dir($backupFolder)) {
//     mkdir($backupFolder, 0777, true);
// }

// $backupFile = $backupFolder . $db . 'database_export_' . date('Y-m-d_H-i-s') . '.sql'; // Save in the database folder
// $tables = $conn->query("SHOW TABLES");

// if (!$tables) {
//     $_SESSION['status'] = 'failed';
//     $_SESSION['failed'] = 'Error fetching table information';
//     echo "<script> window.location = '/'; </script>";
//     exit();
// }

// $sqlDump = "";

// // Generate SQL dump
// while ($row = $tables->fetch_row()) {
//     $tableName = $row[0];
//     $createTable = $conn->query("SHOW CREATE TABLE `$tableName`")->fetch_row()[1];
//     $sqlDump .= "$createTable;\n\n";

//     $result = $conn->query("SELECT * FROM `$tableName`");
//     while ($data = $result->fetch_assoc()) {
//         $values = array_map(function ($value) use ($conn) {
//             return "'" . $conn->real_escape_string($value) . "'";
//         }, array_values($data));

//         $columns = implode("`, `", array_keys($data));
//         $valuesString = implode(", ", $values);
//         $sqlDump .= "INSERT INTO `$tableName` (`$columns`) VALUES ($valuesString);\n";
//     }
//     $sqlDump .= "\n\n";
// }

// // Save the SQL dump to the backup folder
// if (file_put_contents($backupFile, $sqlDump) !== false) {
//     $_SESSION['status'] = 'success';
//     $_SESSION['success'] = "Database exported successfully to: $backupFile";
// } else {
//     $_SESSION['status'] = 'failed';
//     $_SESSION['failed'] = 'Failed to write the database export file';
// }
// echo "<script> window.location = '/'; </script>";
// exit();
?>