<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['transfer'])) {

    if (empty($_POST['employee_id']) || empty($_POST['transfer_employee_id'])) {
        die("Employee IDs cannot be empty.");
    }

    // Fetch the data from transferred table
    $fetchTransferData = $conn->query("SELECT t_employee_id FROM transferred WHERE status = 1");
    if (!$fetchTransferData) {
        die("Query Error: " . $conn->error);
    }
    $transferIDs = array_column($fetchTransferData->fetch_all(MYSQLI_ASSOC), 't_employee_id');

    $originalEmployee = $_POST['employee_id'];
    $transferEmployeeID = $_POST['transfer_employee_id'];
    $microtime = microtime(true); // Get current microtime as a float
    $milliseconds = sprintf('%03d', ($microtime - floor($microtime)) * 1000); // Extract milliseconds
    $created_at = date('Y-m-d H:i:s', (int) $microtime) . '.' . $milliseconds;
    $updated_at = $created_at; // Use the same timestamp for consistency

    $getData = $conn->query("SELECT allocation.cname_id, computer.cname, employee.employee_id 
                                    FROM allocation 
                                    LEFT JOIN computer ON allocation.cname_id = computer.cname_id 
                                    LEFT JOIN employee ON allocation.employee_id = employee.employee_id 
                                    WHERE allocation.employee_id = '$originalEmployee'");

    if ($data = $getData->fetch_assoc()) {
        $cname_id = $data["cname_id"];
        $cname = $data["cname"];
        $status = 1;

        // Insert into transferred table
        $insertData = $conn->prepare("INSERT INTO transferred (employee_id, t_employee_id, cname_id, status, created_at) VALUES (?, ?, ?, ?, ?)");
        if (!$insertData) {
            die("Prepare Error: " . $conn->error);
        }
        $insertData->bind_param("sssis", $originalEmployee, $transferEmployeeID, $cname_id, $status, $created_at);

        if ($insertData->execute()) {
            // Fetch employee name
            $getName = $conn->query("SELECT t.t_employee_id, e.fname, e.lname FROM transferred t LEFT JOIN employee e ON e.employee_id = t.t_employee_id WHERE t.t_employee_id = '$transferEmployeeID'");
            if (!$getName) {
                die("Query Error: " . $conn->error);
            }
            if ($showName = $getName->fetch_assoc()) {
                $transferName = (!empty($showName["fname"]) && !empty($showName["lname"])) ? ($showName["fname"] . ' ' . $showName["lname"]) : 'Unknown';
            }
            $_SESSION['status'] = 'success';
            $_SESSION['success'] = $cname . ' is successfully transferred to ' . $transferName;

            // Insert into allocation table
            $allocationStatus = 1;
            $insertToAllocation = $conn->prepare("INSERT INTO allocation (employee_id, cname_id, status, created_at) VALUES (?, ?, ?, ?)");
            if (!$insertToAllocation) {
                die("Prepare Error: " . $conn->error);
            }
            $insertToAllocation->bind_param("ssis", $transferEmployeeID, $cname_id, $allocationStatus, $created_at);

            if ($insertToAllocation->execute()) {
                // Get transfer data for computer history
                $fetchTransfer = $conn->query("SELECT * FROM transferred WHERE t_employee_id = '$transferEmployeeID'");
                if (!$fetchTransfer) {
                    die("Query Error: " . $conn->error);
                }
                if ($newTransfer = $fetchTransfer->fetch_assoc()) {
                    $newTransferID = $newTransfer['transfer_id'];
                    $transferredCnameID = $newTransfer['cname_id'];

                    // Insert into computer history
                    $insertToHistory = $conn->prepare("INSERT INTO computer_history (transfer_id, employee_id, cname_id, created_at) VALUES (?, ?, ?, ?)");
                    if (!$insertToHistory) {
                        die("Prepare Error: " . $conn->error);
                    }
                    $insertToHistory->bind_param("isss", $newTransferID, $transferEmployeeID, $transferredCnameID, $created_at);
                    $insertToHistory->execute();
                }


                // Get allocation data for computer history
                $fetchNewID = $conn->query("SELECT * FROM allocation WHERE employee_id = '$transferEmployeeID' AND status = 1");
                if (!$fetchNewID) {
                    die("Query Error: " . $conn->error);
                }
                if ($getNewId = $fetchNewID->fetch_assoc()) {
                    $newID = $getNewId['allocation_id'];
                    $newCnameID = $getNewId['cname_id'];

                    // Insert into computer history
                    $insertToHistory = $conn->prepare("INSERT INTO computer_history (allocation_id, employee_id, cname_id, created_at) VALUES (?, ?, ?, ?)");
                    if (!$insertToHistory) {
                        die("Prepare Error: " . $conn->error);
                    }
                    $insertToHistory->bind_param("isss", $newID, $transferEmployeeID, $newCnameID, $created_at);
                    $insertToHistory->execute();
                }


                // Update the status of existing employee on allocation table
                $updateExistingStatus = 0;
                $updateAllocationStatus = $conn->prepare("UPDATE allocation SET `status` = ?, `updated_at` = ? WHERE employee_id = ?");
                if (!$updateAllocationStatus) {
                    die("Prepare Error: " . $conn->error);
                }
                $updateAllocationStatus->bind_param("iss", $updateExistingStatus, $updated_at, $originalEmployee);

                if ($updateAllocationStatus->execute()) {
                    $original = null;
                    foreach ($transferIDs as $id) {
                        $original = $id;
                        if ($original == $originalEmployee) {
                            // Update transferred employee status
                            $transferredStatus = 0;
                            $updateTransferredStatus = $conn->prepare("UPDATE transferred SET `status` = ?, `updated_at` = ? WHERE t_employee_id = ?");
                            if (!$updateTransferredStatus) {
                                die("Prepare Error: " . $conn->error);
                            }
                            $updateTransferredStatus->bind_param("iss", $transferredStatus, $updated_at, $original);

                            if ($updateTransferredStatus->execute()) {
                                echo "<script> window.location = '/allocate'; </script>";
                                exit();
                            } else {
                                $_SESSION['status'] = 'failed';
                                $_SESSION['failed'] = 'Cannot transfer ' . $cname . ' to ' . $transferName . '. Please try again later';
                                echo "<script> window.location = '/allocate'; </script>";
                                exit();
                            }
                        }
                    }
                }
            }

            echo "<script> window.location = '/allocate'; </script>";
            exit();
        } else {
            die("Insert Error: " . $insertData->error);
        }
    } else {
        die("Fetch Error: " . $conn->error);
    }
}

?>