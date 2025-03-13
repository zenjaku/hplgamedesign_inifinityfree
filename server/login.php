<?php
/**
 * Login Handler
 * Manages user authentication and session initialization
 */

// Ensure secure session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');

session_start();
require_once "connections.php";

try {
    // Initialize session variables if not set
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt'] = 0;
    }

    // Lockout check: Prevent login attempts if 5 failed attempts occurred in the last 15 minutes
    if ($_SESSION['login_attempts'] >= 5 && (time() - $_SESSION['last_attempt']) < 900) {
        throw new Exception('Too many failed login attempts. Please try again later.');
    }

    // Handle the login process
    if (!isset($_POST['login'])) {
        throw new Exception('Invalid request method.');
    }

    // CSRF protection
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        throw new Exception('Invalid request.');
    }

    // Validate input
    $inputUsername = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $inputPassword = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

    if (empty($inputUsername) || empty($inputPassword)) {
        throw new Exception('All fields are required.');
    }
    
    // Prevent SQL injection using prepared statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize a variable to track authentication success
    $authenticated = false;
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($inputPassword, $user['password'])) {
            // Check account status
            if ($user['status'] !== 1) {
                throw new Exception('Account not activated. Contact administrator.');
            }

            // Successful login: Reset login attempts
            $_SESSION['login_attempts'] = 0;

            // Initialize secure session
            session_regenerate_id(true);
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = htmlspecialchars($user['username']);
            $_SESSION['type'] = $user['type'];
            $_SESSION['last_activity'] = time();
            $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

            // Success response
            $_SESSION['status'] = 'success';
            $_SESSION['success'] = "Welcome, " . htmlspecialchars($user['username']);

            header('Location: /');
            exit();
        }
    }

    // If authentication fails, increase failed login attempt count
    $_SESSION['login_attempts']++;
    $_SESSION['last_attempt'] = time();

    // Calculate remaining attempts
    $attemptsLeft = 5 - $_SESSION['login_attempts'];

    if ($attemptsLeft > 0) {
        throw new Exception("Invalid credentials. You have $attemptsLeft attempt(s) left.");
    } else {
        throw new Exception("Too many failed login attempts. Please try again later.");
    }

} catch (Exception $e) {
    $_SESSION['status'] = 'failed';
    $_SESSION['failed'] = $e->getMessage();
    header('Location: /login');
    exit();
}
?>
