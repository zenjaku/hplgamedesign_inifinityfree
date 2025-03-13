<?php
if (isset($_POST['allocate'])) {
    // Validate and sanitize inputs
    $employee_id = mysqli_real_escape_string($conn, $_POST['employee_id']);
    $cname_id = htmlspecialchars(trim($_POST['cname_id']));
    // $cname = htmlspecialchars(trim($_POST['cname']));
    $microtime = microtime(true); // Get current microtime as a float
    $milliseconds = sprintf('%03d', ($microtime - floor($microtime)) * 1000); // Extract milliseconds
    $created_at = date('Y-m-d H:i:s', (int) $microtime) . '.' . $milliseconds;
    $updated_at = $created_at; // Use the same timestamp for consistency


    $status = 1;

    // Check if the allocation record exists
    $checkStatus = mysqli_query($conn, "SELECT * FROM allocation WHERE cname_id = '$cname_id'");
    if (!$checkStatus) {
        $_SESSION['status'] = 'failed';
        $_SESSION['failed'] = 'Database query failed. Please try again.';
        echo "<script> window.location = '/allocate'; </script>";
        exit();
    }

    $statusQ = mysqli_fetch_assoc($checkStatus);

    if ($statusQ) {
        $employeeDataId = $statusQ['employee_id'];
        $getEmployeeData = mysqli_query($conn, "SELECT * FROM employee WHERE employee_id = '$employeeDataId'");
        $employeeData = mysqli_fetch_assoc($getEmployeeData);

        if (!$employeeData) {
            $_SESSION['status'] = 'failed';
            $_SESSION['failed'] = 'Employee data not found.';
            echo "<script> window.location = '/allocate'; </script>";
            exit();
        }

        $employeeName = $employeeData['fname'] . ' ' . $employeeData['lname'];

        if ((int) $statusQ["status"] == 1) {
            $_SESSION['status'] = 'failed';
            $_SESSION['failed'] = 'Asset is still allocated to ' . $employeeName;
            echo "<script> window.location = '/allocate'; </script>";
            exit();
        }
    }

    // Insert new allocation record
    $query = "INSERT INTO allocation (employee_id, cname_id, status, created_at) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssis", $employee_id, $cname_id, $status, $created_at);
    $result = $stmt->execute();


    $getID = $conn->query("SELECT * FROM allocation WHERE employee_id = '$employee_id' AND status = 1");
    $result = $getID->fetch_assoc();

    $allocationID = $result["allocation_id"];

    if ($result) {
        $_SESSION['status'] = 'success';
        $_SESSION['success'] = 'Information saved successfully';

        $history = $conn->prepare("INSERT INTO computer_history (allocation_id, employee_id, cname_id, created_at) VALUES (?, ?, ?, ?)");
        $history->bind_param("isss", $allocationID, $employee_id, $cname_id, $created_at);
        if ($history->execute()) {

            $updateComputer = $conn->prepare("UPDATE computer SET `status` = ?, `updated_at` = ? WHERE cname_id = ?");
            $updateComputer->bind_param("iss", $status, $updated_at, $cname_id);
            if ($updateComputer->execute()) {
                // Redirect after successful insertion
                echo "<script> window.location = '/allocate'; </script>";
                exit();
            }


        }

    } else {
        $_SESSION['status'] = 'failed';
        $_SESSION['failed'] = 'Failed to save information. Please try again.';
        echo "<script> window.location = '/allocate'; </script>";
        exit();
    }
}
?>