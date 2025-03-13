<?php
include_once 'server/connections.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['adminRegister'])) {
        // Validate and sanitize inputs
        // $employee_id = mysqli_real_escape_string($conn, $_POST['employee_id']);
        $fname = htmlspecialchars(trim($_POST['fname']));
        $lname = htmlspecialchars(trim($_POST['lname']));
        $contact = htmlspecialchars(trim($_POST['contact']));
        $email = htmlspecialchars(trim($_POST['email']));
        $username = htmlspecialchars(trim($_POST['username']));
        $password = htmlspecialchars(trim($_POST['password']));
        $cpassword = htmlspecialchars(trim($_POST['cpassword']));

        $microtime = microtime(true); // Get current microtime as a float
        $milliseconds = sprintf('%03d', ($microtime - floor($microtime)) * 1000); // Extract milliseconds
        $created_at = date('Y-m-d H:i:s', (int) $microtime) . '.' . $milliseconds;

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user data into the database
        $query = "INSERT INTO users (fname, lname, contact, email, username, password, created_at) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssss", $fname, $lname, $contact, $email, $username, $hashedPassword, $created_at);
        $result = $stmt->execute();

        if ($result) {
            $_SESSION['status'] = 'success';
            $_SESSION['success'] = 'Information saved successfully';
            echo "<script> window.location = '/login'; </script>";
            exit();
        } else {
            $_SESSION['status'] = 'failed';
            $_SESSION['failed'] = 'Failed to save information. Please try again.';
            echo "<script> window.location = '/login'; </script>";
            exit();
        }
    }
}

?>