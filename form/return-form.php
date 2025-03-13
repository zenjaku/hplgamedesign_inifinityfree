<?php
// session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['return'])) {

    // Validate input
    if (empty($_POST['employee_id'])) {
        die("Employee ID cannot be empty.");
    }

    $employee_id = $_POST['employee_id'];
    $created_at = date('Y-m-d H:i:s.u'); // Ensure microsecond precision
    $updated_at = $created_at;

    // Fetch allocation data
    $getData = $conn->prepare("SELECT allocation.cname_id, computer.cname 
                               FROM allocation 
                               LEFT JOIN computer ON allocation.cname_id = computer.cname_id 
                               WHERE allocation.employee_id = ? AND allocation.status = 1");
    $getData->bind_param("s", $employee_id);
    $getData->execute();
    $result = $getData->get_result();
    $data = $result->fetch_assoc();

    if (!$data) {
        die("No allocation found for Employee ID: " . htmlspecialchars($employee_id));
    }

    $cname_id = $data["cname_id"];
    $cname = $data["cname"];

    // Insert into returned table
    $insertData = $conn->prepare("INSERT INTO returned (cname_id, employee_id, created_at) VALUES (?, ?, ?)");
    $insertData->bind_param("sss", $cname_id, $employee_id, $created_at);
    if (!$insertData->execute()) {
        die("Insert Failed: " . $conn->error);
    }

    // Fetch employee name
    $getName = $conn->prepare("SELECT fname, lname FROM employee WHERE employee_id = ?");
    $getName->bind_param("s", $employee_id);
    $getName->execute();
    $nameResult = $getName->get_result();
    $showName = $nameResult->fetch_assoc();
    $returnName = ($showName) ? trim($showName["fname"] . " " . $showName["lname"]) : "Unknown";

    $_SESSION['status'] = 'success';
    $_SESSION['success'] = "$cname is successfully returned by $returnName";

    // Update allocation status
    $allocationStatus = 0;
    $updateAllocation = $conn->prepare("UPDATE allocation SET `status` = ?, `updated_at` = ? WHERE employee_id = ? AND status = 1");
    $updateAllocation->bind_param("iss", $allocationStatus, $updated_at, $employee_id);
    if (!$updateAllocation->execute()) {
        die("Update Allocation Failed: " . $conn->error);
    }

    // Check if employee exists in transferred table
    $fetchTransfer = $conn->prepare("SELECT t_employee_id FROM transferred WHERE t_employee_id = ?");
    $fetchTransfer->bind_param("s", $employee_id);
    $fetchTransfer->execute();
    $transferResult = $fetchTransfer->get_result();
    $fetchID = $transferResult->fetch_assoc();

    if ($fetchID) {
        // Update transferred status
        $transferStatus = 0;
        $updateTransfer = $conn->prepare("UPDATE transferred SET `status` = ?, `updated_at` = ? WHERE t_employee_id = ? AND status = 1");
        $updateTransfer->bind_param("iss", $transferStatus, $updated_at, $employee_id);
        if (!$updateTransfer->execute()) {
            die("Update Transfer Failed: " . $conn->error);
        }
    }

    // Fetch return_id from returned table
    $fetchReturnData = $conn->prepare("SELECT return_id, cname_id FROM returned WHERE employee_id = ? ORDER BY return_id DESC LIMIT 1");
    $fetchReturnData->bind_param("s", $employee_id);
    $fetchReturnData->execute();
    $returnResult = $fetchReturnData->get_result();
    $returnData = $returnResult->fetch_assoc();

    if (!$returnData) {
        die("Failed to fetch return_id.");
    }

    $returnID = $returnData["return_id"];
    $cnameID = $returnData["cname_id"];

    // Insert into computer_history
    $insertToHistory = $conn->prepare("INSERT INTO computer_history (employee_id, return_id, cname_id, created_at) VALUES (?, ?, ?, ?)");
    $insertToHistory->bind_param("siss", $employee_id, $returnID, $cnameID, $created_at);
    if (!$insertToHistory->execute()) {
        die("Insert to history failed: " . $conn->error);
    }

    // Update computer status
    $updateComputerStatus = 0;
    $updateComputer = $conn->prepare("UPDATE computer SET `status` = ?, `updated_at` = ? WHERE cname_id = ?");
    $updateComputer->bind_param("iss", $updateComputerStatus, $updated_at, $cname_id);
    if (!$updateComputer->execute()) {
        die("Update Computer Failed: " . $conn->error);
    }

    // Redirect after successful transaction
    echo "<script> window.location = '/allocate'; </script>";
    exit();
}
?>