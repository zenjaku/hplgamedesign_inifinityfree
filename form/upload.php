<?php
session_start(); // REQUIRED to use $_SESSION variables
include '../server/connections.php'; // Ensure database connection is included

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["tsv"])) {
    $fileTmpPath = $_FILES["tsv"]["tmp_name"];

    if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
        $header = fgetcsv($handle, 0, "\t"); // Read header row with tab delimiter

        // Ensure the header row is valid
        if ($header === FALSE || count(array_filter($header)) == 0) {
            $_SESSION['status'] = 'failed';
            $_SESSION['failed'] = 'Invalid or empty TSV file.';
            echo "<script> window.location = '/employee'; </script>";
            exit();
        }

        // Read and insert each row
        while (($row = fgetcsv($handle, 0, "\t")) !== FALSE) { // Use tab delimiter for rows as well
            // Remove empty columns and trim spaces
            $row = array_map('trim', $row);
            $row = array_filter($row); // Remove empty values

            // Skip completely empty rows
            if (empty($row))
                continue;

            // Ensure row matches header count
            if (count($row) !== count($header))
                continue;

            // Combine header with row data
            $data = array_combine($header, $row);

            $employee_id = $data['ID Number'] ?? null;
            $created_at = date('Y-m-d H:i:s.u'); // Ensure microsecond precision

            // Check if employee_id already exists
            $checkStmt = $conn->prepare("SELECT COUNT(*) FROM employee WHERE employee_id = ?");
            $checkStmt->bind_param("s", $employee_id);
            $checkStmt->execute();
            $checkStmt->bind_result($exists);
            $checkStmt->fetch();
            $checkStmt->close();

            if ($exists) {
                // Skip if employee_id exists
                continue;
            }

            $data['Work Setup'] = isset($data['Work Setup']) ? strtoupper($data['Work Setup']) : null;

            // Insert new employee
            $stmt = $conn->prepare("INSERT INTO employee (employee_id, fname, lname, email, dept, status, created_at) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $employee_id,
                $data['First Name'] ?? null,
                $data['Last Name'] ?? null,
                $data['Email Address'] ?? null,
                $data['Department'] ?? null,
                $data['Work Setup'] ?? null,
                $created_at
            ]);
        }

        fclose($handle);
        $_SESSION['status'] = 'success';
        $_SESSION['success'] = 'TSV uploaded and data saved successfully!';
        echo "<script> window.location = '/employee'; </script>";
        exit();
    } else {
        $_SESSION['status'] = 'failed';
        $_SESSION['failed'] = 'Error opening file.';
        echo "<script> window.location = '/employee'; </script>";
        exit();
    }
} else {
    $_SESSION['status'] = 'failed';
    $_SESSION['failed'] = 'No file uploaded.';
    echo "<script> window.location = '/employee'; </script>";
    exit();
}
?>
