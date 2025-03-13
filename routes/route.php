<?php
/**
 * Router Configuration and Request Handler
 * 
 * This file manages URL routing and access control for the application.
 * It maps URLs to their corresponding PHP files and handles authentication
 * and authorization checks.
 */

// session_start(); // Start session to track logged-in users

// Route definitions with regex patterns mapped to file paths
$routes = [
    // Authentication & Admin routes
    '~^/login$~' => 'admin/login.php',
    '~^/admin-register$~' => 'admin/admin-register.php',
    '~^/users$~' => 'admin/users.php',
    '~^/admin-view$~' => 'admin/admin-view.php',

    // Main page routes
    '~^/$~' => 'pages/home.php',
    '~^/inventory$~' => 'pages/inventory.php',
    '~^/specs$~' => 'pages/specs.php',
    '~^/history$~' => 'pages/history.php',
    '~^/employee$~' => 'pages/employee.php',
    '~^/register$~' => 'pages/register.php',
    '~^/parts$~' => 'pages/parts.php',
    '~^/stocks$~' => 'pages/stocks.php',
    '~^/custody$~' => 'pages/inventory-custody.php',
    '~^/add$~' => 'pages/add_assets.php',
    '~^/allocate$~' => 'pages/allocate.php',
    '~^/build$~' => 'pages/build-computer.php',
    '~^/add-to$~' => 'pages/add-to.php',
    '~^/employee-id$~' => 'pages/get-employee-id.php',

    // Form handling routes
    '~^/register-admin$~' => 'form/admin-register-form.php',
    '~^/esignature$~' => 'form/signature-form.php',
    '~^/esignature-employee$~' => 'form/signature-employee-form.php',
    '~^/add-assets$~' => 'form/inventory-form.php',
    '~^/register-employee$~' => 'form/register-form.php',
    '~^/allocation-assets$~' => 'form/allocation-form.php',
    '~^/build-pc$~' => 'form/build-form.php',
    '~^/add-asset$~' => 'form/add-to-form.php',
    '~^/transfer-assets$~' => 'form/transfer-form.php',
    '~^/return-assets$~' => 'form/return-form.php',
    '~^/tsv$~' => 'form/upload.php',
    '~^/resigned$~' => 'form/resigned-form.php',

    // Server action routes
    '~^/logout$~' => 'server/logout.php',
    '~^/remove-parts$~' => 'server/remove-parts.php',
    '~^/defective$~' => 'server/defect-parts.php',
    '~^/approve$~' => 'server/approve.php',
    '~^/delete$~' => 'server/delete.php',
];

/**
 * Routes that require authentication to access
 */
$protected_routes = [
    '/',
    '/users',
    '/inventory',
    '/add',
    '/specs',
    '/history',
    '/allocate',
    '/employee',
    '/logout',
    '/register',
    '/parts',
    '/build',
    '/remove-parts',
    '/add-to',
    '/defective',
    '/approve',
    '/delete',
    '/add-assets',
    '/register-employee',
    '/allocation-assets',
    '/build-pc',
    '/add-asset',
    '/transfer-assets',
    '/return-assets',
    '/tsv',
    '/stocks',
    '/admin-view',
    '/resigned'
];

// Remove query string from request URI
$request = strtok($_SERVER['REQUEST_URI'], '?');
$matched = false;

foreach ($routes as $pattern => $file) {
    if (preg_match($pattern, $request)) {
        $matched = true;

        // Redirect authenticated users away from login page
        if ($request === '/login' && isset($_SESSION['login']) && $_SESSION['login'] === true) {
            echo "<script> window.location = '/'; </script>";
            exit();
        }

        // Authentication check for protected routes
        if (in_array($request, $protected_routes) && (!isset($_SESSION['login']) || $_SESSION['login'] !== true)) {
            $_SESSION['status'] = 'failed';
            $_SESSION['failed'] = 'Please login to continue.';
            echo "<script> window.location = '/login'; </script>";
            exit();
        }

        // Admin-only access check for users page
        if ($request === '/users' && (!isset($_SESSION['type']) || $_SESSION['type'] !== 1)) {
            $_SESSION['status'] = 'failed';
            $_SESSION['failed'] = 'You do not have permission to access this page.';
            echo "<script> window.location = '/'; </script>";
            exit();
        }

        require $file;
        break;
    }
}

// Handle 404 for unmatched routes
if (!$matched) {
    http_response_code(404);
    echo '404 Page not found';
}
?>