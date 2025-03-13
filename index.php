<?php
/**
 * Main Application Entry Point
 * 
 * This file serves as the main entry point for the Inventory System application.
 * It handles session management, includes necessary dependencies, and provides
 * the base HTML structure with navigation and layout components.
 */

// Include database connection file
include_once __DIR__ . '/server/connections.php';

// Start session management
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/p_1.png" type="image/x-icon">
    <!-- Stylesheets -->
    <link href="css/output/min3.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/e81967d7b9.js" crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="js/output/bootstrap.bundle.min.js"></script>
    <!-- Custom Script -->
    <script src="js/script.js"></script>
    <title>Inventory System</title>
</head>

<body>
    <!-- Global Spinner -->
    <div class="spinner-overlay d-flex flex-column gap-3" id="global-spinner">
        <div class="spinner"></div>
        <span class="text-white fst-bolder fs-4">Loading</span>
    </div>

    <!-- Session Status Toast Notification -->
    <?php if (isset($_SESSION['status'])): ?>
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <?php
            $bgColor = $_SESSION['status'] === 'success' ? 'warning' : 'danger';
            $textColor = $bgColor === 'danger' ? 'text-white' : '';
            ?>
            <div class="toast show bg-<?= $bgColor ?> <?= $textColor ?>" role="alert" id="alertMessage">
                <div class="toast-body justify-content-between d-flex">
                    <?= $_SESSION[$_SESSION['status']] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
        <script>
            setTimeout(() => document.getElementById('alertMessage').remove(), 3000);
        </script>
        <?php unset($_SESSION['status']); ?>
    <?php endif; ?>

    <!-- Main Layout -->
    <div class="d-flex">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar-toggle">
            <div class="sidebar-logo">
                <a href="/"><img src="assets/p_1.png" class="img-fluid hpl_logo"></a>
            </div>
            <!-- Sidebar Navigation -->
            <ul class="sidebar-nav p-0">
                <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
                    <li class="sidebar-header">Tools & Components</li>
                    <li class="sidebar-item">
                        <a href="/inventory" class="sidebar-link d-flex align-items-center gap-3" title="Inventory">
                            <i class="fa-solid fa-chart-simple"></i>
                            <span>Inventory</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/build" class="sidebar-link d-flex align-items-center gap-3" title="Build PC">
                            <i class="fa-solid fa-computer"></i>
                            <span>Build PC</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/allocate" class="sidebar-link d-flex align-items-center gap-3" title="Allocate PC">
                            <i class="fa-solid fa-truck"></i>
                            <span>Allocate PC</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/parts" class="sidebar-link d-flex align-items-center gap-3" title="Parts & Components">
                            <i class="fa-solid fa-hard-drive"></i>
                            <span>Parts & Components</span>
                        </a>
                    </li>
                    <li class="sidebar-header">Pages</li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed has-dropdown d-flex align-items-center gap-3"
                            data-bs-toggle="collapse" data-bs-target="#auth" aria-expanded="true" aria-controls="auth">
                            <i class="fa-solid fa-user-lock"></i>
                            <span>Auth</span>
                        </a>
                        <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <?php if ((int) $_SESSION['type'] == 1): ?>
                                <li class="sidebar-item">
                                    <a href="/users" class="sidebar-link px-5" title="Users">
                                        <span>Users</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li class="sidebar-item">
                                <a href="/employee" class="sidebar-link px-5" title="Employee Data">
                                    <span>Employee Data</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/register" class="sidebar-link px-5" title="Register">
                                    <span>Register Employee</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="admin/database.php" class="sidebar-link px-5"
                                    onclick="setTimeout(() => { window.location.href = '/inventory'; }, 300);"
                                    title="Backup Database" target="_blank">
                                    <span>Backup Database</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
            <!-- Sidebar Navigation Ends -->
            <div class="sidebar-footer d-flex flex-column gap-2 mb-5">
                <?php if (!isset($_SESSION['login']) || $_SESSION['login'] !== true): ?>
                    <li class="sidebar-item">
                        <a href="/employee-id" class="sidebar-link d-flex align-items-center gap-3" title="Employee Access">
                            <i class="fa-solid fa-chart-simple"></i>
                            <span>Employee Access</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="https://drive.google.com/file/d/1kU6K38qSz7ID4ZebblqJCpw5VBqOUk99/view?usp=drive_link"
                            class="sidebar-link d-flex align-items-center gap-3" target="_blank" title="Register">
                            <i class="fa-solid fa-file-pdf"></i>
                            <span>Documentation</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/admin-register" class="sidebar-link d-flex align-items-center gap-3" title="Register">
                            <i class="fa-solid fa-user"></i>
                            <span>Register</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/login" class="sidebar-link d-flex align-items-center gap-3" title="Login">
                            <i class="fa-solid fa-user-lock"></i>
                            <span>Login</span>
                        </a>
                    </li>
                <?php endif; ?>
            </div>
        </aside>
        <!-- Sidebar Ends -->

        <!-- Main Content -->
        <div class="main" id="main">
            <nav class="navbar navbar-expand d-flex justify-content-between p-1">
                <button class="toggler-btn px-3" type="button">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="d-flex gap-2 align-items-center">
                    <h1 class="text-uppercase my-1">HPL GAME DESIGN INVENTORY SYSTEM</h1>
                    <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
                        <a href="/logout" class="sidebar-link d-flex align-items-center gap-3" title="Logout">
                            <button class="btn btn-danger power-off"><i class="fa-solid fa-power-off"></i></button>
                        </a>
                    <?php endif; ?>
                </div>
            </nav>
            <div class="py-2 px-5">
                <!-- Include Routing Logic -->
                <?php include 'routes/route.php'; ?>
            </div>
        </div>
    </div>
</body>

</html>