<?php
include_once __DIR__ . '/server/connections.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/p_1.png" type="image/x-icon">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/output/min3.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/e81967d7b9.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <!-- <script src="js/bootstrap/bootstrap.bundle.min.js"></script> -->
    <script src="js/output/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <title>Inventory System</title>
</head>

<body>

    <div class="spinner-overlay d-flex flex-column gap-3" id="global-spinner">
        <div class="spinner"></div>
        <span class="text-white fst-bolder fs-4">
            Loading
        </span>
    </div>
    <?php if (isset($_SESSION['status'])): ?>
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <?php
            $bgColor = $_SESSION['status'] === 'success' ? 'warning' : 'danger';
            $textColor = $bgColor === 'danger' ? 'text-white' : ''; // Add text-white only if bg-danger
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

    <div class="d-flex">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar-toggle">
            <div class="sidebar-logo">
                <a href="/"><img src="assets/p_1.png" class="img-fluid hpl_logo"></a>
            </div>
            <!-- Sidebar Navigation -->
            <ul class="sidebar-nav p-0">
                <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
                    <li class="sidebar-header">
                        Tools & Components
                    </li>
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
                    <li class="sidebar-header">
                        Pages
                    </li>
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
                                    title="Backup Database">
                                    <span>Backup Database</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <!-- <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="lni lni-popup"></i>
                        <span>Notification</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="lni lni-cog"></i>
                        <span>Setting</span>
                    </a>
                </li> -->
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
            <!-- <div class="sidebar-footer">
                <a href="#" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Setting</span>
                </a>
            </div> -->
        </aside>
        <!-- Sidebar Ends -->
        <!-- Main Component -->
        <div class="main" id="main">
            <nav class="navbar navbar-expand d-flex justify-content-between p-1">
                <button class="toggler-btn px-3" type="button">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="d-flex gap-2 align-items-center">
                    <h1 class="text-uppercase my-1">HPL GAME DESIGN INVENTORY SYSTEM</h1>
                    <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
                        <a href="/logout" class=" sidebar-link d-flex align-items-center gap-3" title="Logout">
                            <button class="btn btn-danger power-off"><i class="fa-solid fa-power-off"></i></button>
                        </a>
                    <?php endif; ?>
                </div>
            </nav>
            <div class="py-3 px-5">

                <?php
                include 'routes/route.php';

                ?>
            </div>
        </div>
    </div>
</body>

</html>

<div class="container my-5">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3>Add Assets</h3>
            <a href="/inventory">
                <button type="button" class="btn btn-danger">
                    Close
                </button>
            </a>
        </div>
        <form action="/add-assets" method="post" id="addAssets">
            <div class="card-body">
                <div class="row d-flex flex-column gap-3">
                    <div class="col d-flex gap-3 justify-content-start align-items-center">
                        <div class="input-group">
                            <label class="input-group-text" for="assets">Assets</label>
                            <select class="form-select" name="assets[]" id="assets">
                                <option selected>-- Choose Assets --</option>
                                <option value="PROCESSOR">PROCESSOR</option>
                                <option value="MOTHERBOARD">MOTHERBOARD</option>
                                <option value="GPU">GPU</option>
                                <option value="HDD">HDD</option>
                                <option value="SSD">SSD</option>
                                <option value="RAM">RAM</option>
                                <option value="CABLE">CABLE</option>
                                <option value="HEADSET">HEADSET</option>
                                <option value="WEBCAM">WEBCAM</option>
                                <option value="MONITOR">MONITOR</option>
                                <option value="KEYBOARD">KEYBOARD</option>
                                <option value="MOUSE">MOUSE</option>
                                <option value="PEN DISPLAY">PEN DISPLAY</option>
                                <option value="PEN TABLET">PEN TABLET</option>
                            </select>
                        </div>
                        <div class="form-floating">
                            <input type="text" name="brand[]" class="form-control group" required>
                            <label>Brand</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" name="model[]" class="form-control group" required>
                            <label>Model</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" name="sn[]" class="form-control group" required>
                            <label>Serial Number</label>
                        </div>
                        <button type="button" class="btn btn-warning">
                            <i class="fa-solid fa-circle-plus"></i>
                        </button>
                        <button type="button" class="btn btn-danger cancel">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end align-items-center gap-3">
                    <button type="submit" name="addAssets" class="btn btn-dark">Submit</button>
                    <button type="reset" class="btn btn-danger" onclick="window.location =''">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        function addInput() {
            const container = document.querySelector(".row");

            if (!container) return;

            // Function to check if all fields in a row are filled
            function checkInputs(row) {
                let inputs = row.querySelectorAll("input");
                let addButton = row.querySelector(".btn-warning");
                let allFilled = Array.from(inputs).every(input => input.value.trim() !== "");

                addButton.disabled = !allFilled; // Enable button if all fields are filled
            }

            // Function to add new input row
            function addInputRow() {
                let originalGroups = document.querySelectorAll(".col.d-flex");

                // Disable buttons on all previous rows
                originalGroups.forEach(group => {
                    group.querySelector(".btn-warning").disabled = true;
                    group.querySelector(".btn-danger").disabled = true;
                });

                // Clone the last group and clear inputs
                let newGroup = originalGroups[originalGroups.length - 1].cloneNode(true);
                newGroup.querySelectorAll("input").forEach(input => input.value = "");

                // Enable the new row's buttons
                let addButton = newGroup.querySelector(".btn-warning");
                let deleteButton = newGroup.querySelector(".btn-danger");

                addButton.disabled = true; // Initially disabled until fields are filled
                deleteButton.disabled = false;
                deleteButton.style.display = "inline-block";

                // Attach event listeners to the new row's inputs
                newGroup.querySelectorAll("input").forEach(input => {
                    input.addEventListener("input", function () {
                        checkInputs(newGroup);
                    });
                });

                // Attach event listeners for buttons
                addButton.addEventListener("click", addInputRow);
                deleteButton.addEventListener("click", function () {
                    newGroup.remove();
                    enableLastRowButtons(); // Re-enable buttons on the last remaining row
                });

                // Append new input group
                container.appendChild(newGroup);
            }

            // Function to enable buttons on the last row after deletion
            function enableLastRowButtons() {
                let lastRow = document.querySelectorAll(".col.d-flex");
                if (lastRow.length > 0) {
                    let lastAddButton = lastRow[lastRow.length - 1].querySelector(".btn-warning");
                    let lastDeleteButton = lastRow[lastRow.length - 1].querySelector(".btn-danger");
                    lastAddButton.disabled = false;
                    lastDeleteButton.disabled = false;
                }
            }

            // Attach event listener to the first row's inputs
            let firstRow = document.querySelector(".col.d-flex");
            firstRow.querySelectorAll("input").forEach(input => {
                input.addEventListener("input", function () {
                    checkInputs(firstRow);
                });
            });

            // Attach event listener to the first "Add" button
            document.querySelector(".btn-warning").addEventListener("click", addInputRow);
        }

        addInput();
    });
</script>
<?php
require "server/drop-downs/available-pc.php";
$assets_id = $_GET['assets_id'] ?? '';

$_SESSION['assetsID'] = $assets_id;

$fetchName = $conn->query("SELECT assets FROM assets WHERE assets_id = '$assets_id'");
$result = $fetchName->fetch_assoc();

if($result) {
    $assetName = $result["assets"];
}
?>
<div class="d-flex justify-content-center align-items-center py-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center gap-5">
            <h3> Where do you want to add <?=$assetName?>?</h3>
            <a href="/parts">
                <button class="btn btn-danger" id="closeModal">X</button>
            </a>
        </div>
        <form action="/add-asset" method="post" id="addForm">
            <div class="card-body">
                <select name="cname_id" id="assets_id" class="form-select computer-select" required>
                    <option value="">Computer Name</option>
                    <?php foreach ($availableAssets as $row): ?>
                        <option value="<?= htmlspecialchars($row['cname_id']) ?>">
                            <?= htmlspecialchars($row['cname']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="card-footer d-flex justify-content-end align-items-center gap-3">
                <button class="btn btn-dark" type="submit" name="addAsset">Submit</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="d-flex flex-column gap-4 py-4" id="inventoryDashboard">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <td>ASSETS ID</td>
                    <td>SPECIFIED</td>
                    <td>BRAND</td>
                    <td>MODEL</td>
                    <td>S/N</td>
                </tr>
            </thead>
            <tbody id="showdata">
            </tbody>
        </table>
    </div>
</div>
<script>
    // script for allocating pc
    $(document).ready(function () {

        $('#assets_id').on("change", function () {
            var assetID = $(this).val().trim();
            var formID = $(this).closest('form').attr('id');

            if (assetID === "") {
                $("#showdata").html("");
                return;
            }

            $.ajax({
                method: 'POST',
                url: 'server/jquery/employee_allocation.php',
                data: {
                    assetID: assetID,
                    formType: formID
                },
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        var html = '';

                        if (data.message) {
                            html = "<tr><td colspan='6'>No assets found</td></tr>";
                        } else {
                            data.forEach(function (item) {
                                html += "<tr>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='assets_id' value='" + item.assets_id + "'><label>Assets ID</label></div></td>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='c_assets' value='" + item.assets + "'><label>Assets</label></div></td>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='brand' value='" + item.brand + "'><label>Brand</label></div></td>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='model' value='" + item.model + "'><label>Model</label></div></td>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='sn' value='" + item.sn + "'><label>Serial Number</label></div></td>";
                                html += "</tr>";
                            });
                        }
                        $("#showdata").html(html);
                    } catch (e) {
                        console.error("Error parsing JSON response", e);
                        $("#showdata").html("<tr><td colspan='6'>An error occurred while fetching data.</td></tr>");
                    }
                },
                error: function () {
                    $("#showdata").html("<tr><td colspan='6'>An error occurred while fetching data.</td></tr>");
                }
            });
        });
    });
</script>
<?php
require_once "server/drop-downs/allocate.php";
require_once "server/drop-downs/transfer.php";
require_once "server/drop-downs/return.php";

$assets = [];
while ($row = mysqli_fetch_assoc($assetsResult)) {
    $assets[] = $row;
}

// $transfer = [];
// while ($row = mysqli_fetch_assoc($transferIDResult)) {
//     $transfer[] = $row;
// }
?>
<div class="container py-5">
    <div class="row">
        <!-- Allocate PC -->
        <div class="col-4">
            <form action="/allocation-assets" method="post" id="allocateForm">
                <div class="card">
                    <div class="card-header">
                        <h2>Allocation of PC</h2>
                    </div>
                    <div class="card-body my-5">
                        <div class="d-flex">
                            <div class="input-group mb-3">
                                <input type="text" name="employee_id" id="search_employee_id"
                                    class="form-control w-50 h-75" placeholder="Search Employee by ID or Name here">
                                <button type="submit" id="search_employee" class="btn btn-dark h-75">Search</button>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex flex-column justify-content-center align-content-center gap-3">
                            <select name="employee_id" id="allocate_employee_id" class="form-select" required>
                                <option value="">Employee Name</option>
                                <?php foreach ($employeeIDs as $row): ?>
                                    <option value="<?= htmlspecialchars($row['employee_id']) ?>">
                                        <?= htmlspecialchars($row['fname'] . ' ' . $row['lname']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <select name="cname_id" id="allocate_cname_id" class="form-select computer-select" required>
                                <option value="">Select a Computer</option>
                                <?php foreach ($assets as $row): ?>
                                    <option value="<?= htmlspecialchars($row['cname_id']) ?>">
                                        <?= htmlspecialchars($row['cname']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end gap-3">
                        <button type="submit" name="allocate" class="btn btn-dark">Submit</button>
                        <button type="reset" class="btn btn-danger resetForm">Cancel</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Transfer PC -->
        <div class="col-4">
            <form action="/transfer-assets" method="post" id="transferForm">
                <div class="card">
                    <div class="card-header">
                        <h2>Transfer PC</h2>
                    </div>
                    <div class="card-body my-5">
                        <div class="d-flex flex-column">
                            <div class="input-group mb-3" id="transfer">
                                <span class="input-group-text h-75" id="label">From</span>
                                <input type="text" name="employee_id" id="search_original_id" class="form-control w-50"
                                    placeholder="Search Employee by ID or Name here">
                                <button type="submit" id="search_original" class="btn btn-dark h-75">Search</button>
                            </div>
                            <div class="input-group mb-3" id="transfer">
                                <span class="input-group-text h-75" id="label">To</span>
                                <input type="text" name="employee_id" id="search_transfer" class="form-control w-50"
                                    placeholder="Search Employee by ID or Name here">
                                <button type="submit" id="transfer_employee" class="btn btn-dark h-75">Search</button>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex flex-column justify-content-center align-content-center gap-3">
                            <div class="input-group" id="from">
                                <label class="input-group-text" for="original_employee_id"> From </label>
                                <select name="employee_id" id="original_employee_id" class="form-select" required>
                                    <option value="">Employee Name</option>
                                    <?php foreach ($transferEmployeeID as $row): ?>
                                        <option value="<?= htmlspecialchars($row['employee_id']) ?>">
                                            <?= htmlspecialchars($row['fname'] . ' ' . $row['lname']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="input-group" id="to">
                                <label class="input-group-text" for="transfer_employee_id"> To </label>
                                <select name="transfer_employee_id" id="transfer_employee_id"
                                    class="form-select computer-select" required>
                                    <option value="">Employee Name</option>
                                    <?php foreach ($transfer as $row): ?>
                                        <option value="<?= htmlspecialchars($row['employee_id']) ?>">
                                            <?= htmlspecialchars($row['fname'] . ' ' . $row['lname']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end gap-3">
                        <button type="submit" name="transfer" class="btn btn-dark">Submit</button>
                        <button type="reset" class="btn btn-danger resetForm">Cancel</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Return PC -->
        <div class="col-4">
            <form action="/return-assets" method="post" id="returnForm">
                <div class="card">
                    <div class="card-header">
                        <h2>Returned PC</h2>
                    </div>
                    <div class="card-body my-5">
                        <div class="d-flex flex-column" id="search_return">
                            <div class="input-group mb-3">
                                <input type="text" name="employee_id" id="search_id"
                                    class="form-control w-50" placeholder="Search Employee by ID or Name here">
                                <button type="submit" id="return_id" class="btn btn-dark h-75">Search</button>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex flex-column justify-content-center align-content-center gap-3">
                            <div class="input-group" id="return">
                                <select name="employee_id" id="return_employee_id" class="form-select" required>
                                    <option value="">Employee Name</option>
                                    <?php foreach ($employeeID as $row): ?>
                                        <option value="<?= htmlspecialchars($row['employee_id']) ?>">
                                            <?= htmlspecialchars($row['fname'] . ' ' . $row['lname']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end gap-3">
                        <button type="submit" name="return" class="btn btn-dark">Submit</button>
                        <button type="reset" class="btn btn-danger resetForm">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="d-flex flex-column gap-4 py-4" id="inventoryDashboard">
            <table class="table text-center">
                <tbody id="showdata">
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const resetButtons = document.querySelectorAll(".resetForm"); // Selects all buttons with the class 'resetForm'
        // Loop through all reset buttons and add event listener
        resetButtons.forEach(button => {
            button.addEventListener("click", function (event) {
                event.preventDefault();
                window.location.reload();
            });
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#search_employee').click(function (event) {
            event.preventDefault(); // Prevent form submission
            var searchQuery = $('#search_employee_id').val().trim();

            if (searchQuery === "") {
                return;
            }

            $.ajax({
                method: 'POST',
                url: 'server/jquery/search_employee.php',
                data: { searchQuery: searchQuery },
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        var employeeDropdown = $('#allocate_employee_id');

                        employeeDropdown.empty(); // Clear existing options
                        // employeeDropdown.append('<option value="">Select Employee</option>');

                        if (data.length === 0) {
                            showToast("No employee data found");
                            return;
                        }

                        data.forEach(function (item) {
                            let newOption = $('<option>', {
                                value: item.employee_id,
                                text: item.fname + ' ' + item.lname
                            });
                            employeeDropdown.append(newOption);
                        });

                        $('#allocate_employee_id').trigger('change');
                        console.log($('#allocate_employee_id').html());


                        // console.log("Dropdown updated with employees:", employeeDropdown.html());
                    } catch (e) {
                        console.error("Error parsing JSON response", e);
                    }
                },
                error: function () {
                    showToast("An error occurred while fetching data.");
                }
            });
        });

        //transfer allocation **from**
        $('#search_original').click(function (event) {
            event.preventDefault(); // Prevent form submission
            var originalQuery = $('#search_original_id').val().trim();

            if (originalQuery === "") {
                return;
            }

            $.ajax({
                method: 'POST',
                url: 'server/jquery/search_employee.php',
                data: { originalQuery: originalQuery },
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        var employeeDropdown = $('#original_employee_id');

                        employeeDropdown.empty(); // Clear existing options
                        // employeeDropdown.append('<option value="">Select Employee</option>');

                        if (data.length === 0) {
                            showToast("No employee data found");
                            return;
                        }

                        data.forEach(function (item) {
                            let newOption = $('<option>', {
                                value: item.employee_id,
                                text: item.fname + ' ' + item.lname
                            });
                            employeeDropdown.append(newOption);
                        });

                        $('#original_employee_id').trigger('change');
                        console.log($('#original_employee_id').html());


                        // console.log("Dropdown updated with employees:", employeeDropdown.html());
                    } catch (e) {
                        console.error("Error parsing JSON response", e);
                    }
                },
                error: function () {
                    showToast("An error occurred while fetching data.");
                }
            });
        });

        //transfer allocation **to**
        $('#transfer_employee').click(function (event) {
            event.preventDefault(); // Prevent form submission
            var transferQuery = $('#search_transfer').val().trim();

            if (transferQuery === "") {
                return;
            }

            $.ajax({
                method: 'POST',
                url: 'server/jquery/search_employee.php',
                data: { transferQuery: transferQuery },
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        var employeeDropdown = $('#transfer_employee_id');

                        employeeDropdown.empty(); // Clear existing options
                        // employeeDropdown.append('<option value="">Select Employee</option>');

                        if (data.length === 0) {
                            showToast("No employee data found");
                            return;
                        }

                        data.forEach(function (item) {
                            let newOption = $('<option>', {
                                value: item.employee_id,
                                text: item.fname + ' ' + item.lname
                            });
                            employeeDropdown.append(newOption);
                        });

                        $('#transfer_employee_id').trigger('change');
                        console.log($('#transfer_employee_id').html());


                        // console.log("Dropdown updated with employees:", employeeDropdown.html());
                    } catch (e) {
                        console.error("Error parsing JSON response", e);
                    }
                },
                error: function () {
                    showToast("An error occurred while fetching data.");
                }
            });
        });

        // return allocation
        $('#return_id').click(function (event) {
            event.preventDefault(); // Prevent form submission
            var originalQuery = $('#search_id').val().trim();

            if (originalQuery === "") {
                return;
            }

            $.ajax({
                method: 'POST',
                url: 'server/jquery/search_employee.php',
                data: { originalQuery: originalQuery },
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        var employeeDropdown = $('#return_employee_id');

                        employeeDropdown.empty(); // Clear existing options
                        // employeeDropdown.append('<option value="">Select Employee</option>');

                        if (data.length === 0) {
                            showToast("No employee data found");
                            return;
                        }

                        data.forEach(function (item) {
                            let newOption = $('<option>', {
                                value: item.employee_id,
                                text: item.fname + ' ' + item.lname
                            });
                            employeeDropdown.append(newOption);
                        });

                        $('#return_employee_id').trigger('change');
                        console.log($('#return_employee_id').html());


                        // console.log("Dropdown updated with employees:", employeeDropdown.html());
                    } catch (e) {
                        console.error("Error parsing JSON response", e);
                    }
                },
                error: function () {
                    showToast("An error occurred while fetching data.");
                }
            });
        });


        function showToast(message) {
            var toast = $('<div class="toast align-items-center text-white bg-danger border-0 position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">' +
                '<div class="d-flex">' +
                '<div class="toast-body">' + message + '</div>' +
                '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
                '</div>' +
                '</div>');

            $('body').append(toast);
            var toastElement = new bootstrap.Toast(toast[0]);
            toastElement.show();

            setTimeout(function () {
                toast.remove();
            }, 3000);
        }
    });

    // script for allocating pc
    $(document).ready(function () {

        $('#allocate_cname_id').on("change", function () {
            var assetID = $(this).val().trim();
            var formID = $(this).closest('form').attr('id');

            if (assetID === "") {
                $("#showdata").html("");
                return;
            }

            $.ajax({
                method: 'POST',
                url: 'server/jquery/employee_allocation.php',
                data: {
                    assetID: assetID,
                    formType: formID
                },
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        var html = '';

                        if (data.message) {
                            html = "<tr><td colspan='6'>No assets found</td></tr>";
                        } else {
                            data.forEach(function (item) {
                                html += "<tr>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='assets_id' value='" + item.assets_id + "'><label>Assets ID</label></div></td>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='c_assets' value='" + item.assets + "'><label>Assets</label></div></td>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='brand' value='" + item.brand + "'><label>Brand</label></div></td>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='model' value='" + item.model + "'><label>Model</label></div></td>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='sn' value='" + item.sn + "'><label>Serial Number</label></div></td>";
                                html += "</tr>";
                            });
                        }
                        $("#showdata").html(html);
                    } catch (e) {
                        console.error("Error parsing JSON response", e);
                        $("#showdata").html("<tr><td colspan='6'>An error occurred while fetching data.</td></tr>");
                    }
                },
                error: function () {
                    $("#showdata").html("<tr><td colspan='6'>An error occurred while fetching data.</td></tr>");
                }
            });
        });
    });

    // script for transferring pc
    $(document).ready(function () {
        $('#original_employee_id').on("change", function () {
            var transferID = $(this).val().trim();
            var formID = $(this).closest('form').attr('id');

            if (transferID === "") {
                $("#showdata").html("");
                return;
            }

            $.ajax({
                method: 'POST',
                url: 'server/jquery/transfer_allocation.php',
                data: {
                    transferID: transferID,
                    formType: formID
                },
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        var html = '';

                        if (data.message) {
                            html = "<tr><td colspan='6'>No assets found</td></tr>";
                        } else {
                            data.forEach(function (item) {
                                html += "<tr>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='assets_id' value='" + item.assets_id + "'><label>Assets ID</label></div></td>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='c_assets' value='" + item.assets + "'><label>Assets</label></div></td>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='brand' value='" + item.brand + "'><label>Brand</label></div></td>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='model' value='" + item.model + "'><label>Model</label></div></td>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='sn' value='" + item.sn + "'><label>Serial Number</label></div></td>";
                                html += "</tr>";
                            });
                        }
                        $("#showdata").html(html);
                    } catch (e) {
                        console.error("Error parsing JSON response", e);
                        $("#showdata").html("<tr><td colspan='6'>An error occurred while fetching data.</td></tr>");
                    }
                },
                error: function () {
                    $("#showdata").html("<tr><td colspan='6'>An error occurred while fetching data.</td></tr>");
                }
            });
        });
    });

    // script for returning pc
    $(document).ready(function () {
        $('#return_employee_id').on("change", function () {
            var returnID = $(this).val().trim();
            var formID = $(this).closest('form').attr('id');

            if (returnID === "") {
                $("#showdata").html("");
                return;
            }

            $.ajax({
                method: 'POST',
                url: 'server/jquery/return_allocations.php',
                data: {
                    returnID: returnID,
                    formType: formID
                },
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        var html = '';

                        if (data.message) {
                            html = "<tr><td colspan='6'>No assets found</td></tr>";
                        } else {
                            data.forEach(function (item) {
                                html += "<tr>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='assets_id' value='" + item.assets_id + "'><label>Assets ID</label></div></td>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='c_assets' value='" + item.assets + "'><label>Assets</label></div></td>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='brand' value='" + item.brand + "'><label>Brand</label></div></td>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='model' value='" + item.model + "'><label>Model</label></div></td>";
                                html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='sn' value='" + item.sn + "'><label>Serial Number</label></div></td>";
                                html += "</tr>";
                            });
                        }
                        $("#showdata").html(html);
                    } catch (e) {
                        console.error("Error parsing JSON response", e);
                        $("#showdata").html("<tr><td colspan='6'>An error occurred while fetching data.</td></tr>");
                    }
                },
                error: function () {
                    $("#showdata").html("<tr><td colspan='6'>An error occurred while fetching data.</td></tr>");
                }
            });
        });
    });
</script>
<?php
require('server/drop-downs/parts.php');
?>
<div class="row d-flex flex-column justify-content-center align-items-center">
    <div class="col-12 py-5">
        <div class="card">
            <div class="card-header">
                <h3>Build PC</h3>
            </div>
            <form action="/build-pc" method="post" id="buildForm">
                <div class="card-body">
                    <div class="form-floating mb-3">
                        <input type="text" name="cname" id="cname" class="form-control" placeholder="Computer Name"
                            required>
                        <label for="cname">Computer Name</label>
                        <table class="table table-bordered text-center table-responsive">
                            <thead class="table-dark">
                                <tr>
                                    <td>ASSETS ID</td>
                                    <td>SPECIFIED</td>
                                    <td>BRAND</td>
                                    <td>MODEL</td>
                                    <td>S/N</td>
                                    <td>REMOVE</td>
                                </tr>
                            </thead>
                            <tbody id="showassets">

                                <script>
                                    $(document).ready(function () {
                                        var currentPage = 1;
                                        var addedAssets = []; // Track added asset IDs
                                        var singleAddParts = ['PROCESSOR', 'MOTHERBOARD', 'GPU', 'POWER SUPPLY']; // Parts that can only be added once

                                        // Function to fetch data based on search term
                                        function fetchData(page) {
                                            currentPage = page;
                                            var getAssets = $('#getAssets').val().trim();

                                            $.ajax({
                                                method: 'POST',
                                                url: 'server/jquery/fetch_parts.php',
                                                data: {
                                                    name: getAssets,
                                                    page: page,
                                                    exclude: addedAssets // Send the excluded assets
                                                },
                                                success: function (response) {
                                                    console.log("Response from fetch_parts.php:", response); // Log the response for debugging
                                                    try {
                                                        var data = JSON.parse(response);
                                                        var html = '';
                                                        if (data.data.length > 0) {
                                                            data.data.forEach(function (item) {
                                                                html += `<tr>
                                        <td>${item.assets_id}</td>
                                        <td>${item.assets}</td>
                                        <td>${item.brand}</td>
                                        <td>${item.model}</td>
                                        <td>${item.sn}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning mb-3 add-part" data-assets-id="${item.assets_id}" data-assets="${item.assets}">
                                                <i class="fa-solid fa-circle-plus"></i>
                                            </button>
                                        </td>
                                    </tr>`;
                                                            });
                                                        } else {
                                                            html = '<tr><td colspan="6">No parts found</td></tr>';
                                                        }
                                                        $('#showdata').html(html);

                                                        // Pagination logic
                                                        var paginationHtml = '';
                                                        for (var i = 1; i <= data.totalPages; i++) {
                                                            var activeClass = (i === currentPage) ? 'active' : '';
                                                            paginationHtml += `<li class='page-item ${activeClass}'><a class='page-link' href='#' data-page='${i}'>${i}</a></li>`;
                                                        }
                                                        $('#pagination').html(paginationHtml); // Inject pagination links

                                                        // Attach click event handlers to pagination links
                                                        $('#pagination .page-link').on('click', function (e) {
                                                            e.preventDefault(); // Prevent default link behavior
                                                            var page = $(this).data('page'); // Get the page number
                                                            fetchData(page); // Fetch data for the selected page
                                                        });
                                                    } catch (e) {
                                                        console.error("Error parsing response:", e);
                                                    }
                                                },
                                                error: function (xhr, status, error) {
                                                    console.error("AJAX Error:", error);
                                                }
                                            });
                                        }

                                        // Fetch data on page load
                                        fetchData(1);

                                        // Add Part to Build
                                        $(document).on('click', '.add-part', function () {
                                            var row = $(this).closest('tr');
                                            var assets_id = $(this).data('assets-id');
                                            var assets = $(this).data('assets').trim().toUpperCase(); // Trim and convert to uppercase
                                            var brand = row.find('td:eq(2)').text();
                                            var model = row.find('td:eq(3)').text();
                                            var sn = row.find('td:eq(4)').text();

                                            if (addedAssets.includes(assets_id)) {
                                                showToast('This part is already added.', 'error');
                                                return;
                                            }

                                            // Check if the part is in singleAddParts and already added
                                            if (singleAddParts.includes(assets)) {
                                                var alreadyAdded = false;
                                                $('#showassets input[name="assets[]"]').each(function () {
                                                    var existingAsset = $(this).val().trim().toUpperCase(); // Trim and convert to uppercase
                                                    if (existingAsset === assets) {
                                                        alreadyAdded = true;
                                                        return false; // Break the loop
                                                    }
                                                });

                                                if (alreadyAdded) {
                                                    showToast(`Only one ${assets} can be added.`, 'error');
                                                    return;
                                                }
                                            }

                                            // Add the part to the list
                                            addedAssets.push(assets_id);
                                            $('#showassets').append(`<tr>
                                    <td><input type="text" class="border-0" readonly name="assets_id[]" value="${assets_id}"></td>
                                    <td><input type="text" class="border-0" readonly name="assets[]" value="${assets}"></td>
                                    <td><input type="text" class="border-0" readonly name="brand[]" value="${brand}"></td>
                                    <td><input type="text" class="border-0" readonly name="model[]" value="${model}"></td>
                                    <td><input type="text" class="border-0" readonly name="sn[]" value="${sn}"></td>
                                    <td><button type="button" class="btn btn-danger mb-3 remove-part"><i class="fa-solid fa-trash-can"></i></button></td>
                                </tr>`);

                                            showToast(`${assets} added successfully.`, 'success');
                                        });

                                        // Remove Part from Build
                                        $(document).on('click', '.remove-part', function () {
                                            var row = $(this).closest('tr');
                                            var assets_id = row.find('input[name="assets_id[]"]').val();
                                            var assets = row.find('input[name="assets[]"]').val();

                                            // Remove from the list
                                            addedAssets = addedAssets.filter(id => id !== assets_id);
                                            row.remove();

                                            showToast(`${assets} removed successfully.`, 'success');
                                        });

                                        // Search handler for real-time search
                                        $('#getAssets').on('input', function () {
                                            fetchData(1); // Reload parts on every search term change
                                        });

                                        // Function to show toast notifications
                                        function showToast(message, status) {
                                            const bgColor = status === 'success' ? 'warning' : 'danger';
                                            const textColor = bgColor === 'danger' ? 'text-white' : '';
                                            const toast = document.createElement('div');
                                            toast.className = `toast show bg-${bgColor} ${textColor}`;
                                            toast.setAttribute('role', 'alert');
                                            toast.innerHTML = `
                            <div class="toast-body justify-content-between d-flex">
                                ${message}
                                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                            </div>
                        `;
                                            let toastContainer = document.querySelector('.toast-container');
                                            if (!toastContainer) {
                                                toastContainer = document.createElement('div');
                                                toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                                                document.body.appendChild(toastContainer);
                                            }
                                            toastContainer.appendChild(toast);
                                            setTimeout(() => toast.remove(), 3000);
                                        }
                                    });
                                </script>



                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-dark" id="submitBtn" name="build-pc">Save</button>
                    <button type="reset" class="btn btn-danger" onclick="window.location = ''">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col">
        <div class="">
            <table class="table table-bordered text-center table-responsive">
                <div id="searchAssets" class="form-outline">
                    <input type="text" class="form-control parts-search" name="assets_id" id="getAssets"
                        placeholder="Search by Serial Number">
                </div>
                <thead class="table-dark">
                    <tr>
                        <td>ASSETS ID</td>
                        <td>SPECIFIED</td>
                        <td>BRAND</td>
                        <td>MODEL</td>
                        <td>S/N</td>
                        <td>ADD</td>
                    </tr>
                </thead>
                <tbody id="showdata">
                    <tr>
                        <td>ASSETS ID</td>
                        <td>SPECIFIED</td>
                        <td>BRAND</td>
                        <td>MODEL</td>
                        <td>S/N</td>
                        <td>
                            <button type="button" class="btn btn-warning mb-3 add-part">
                                <i class="fa-solid fa-circle-plus"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <div class="text-white">
                                <nav>
                                    <ul class="bg-dark rounded p-2 d-flex justify-content-center align-items-center gap-3 border border-2 border-white"
                                        id="pagination">
                                        <!-- Pagination links will be inserted dynamically by JavaScript -->
                                    </ul>
                                </nav>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
</div>

<script>
    // Function to show toast notifications
    function showToast(message, status) {
        // Define colors based on status
        const bgColor = status === 'success' ? 'warning' : 'danger';
        const textColor = bgColor === 'danger' ? 'text-white' : '';

        // Create the toast element
        const toast = document.createElement('div');
        toast.className = `toast show bg-${bgColor} ${textColor}`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
        <div class="toast-body justify-content-between d-flex">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
    `;

        // Create the toast container if it doesn't exist
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }

        // Append the toast to the container
        toastContainer.appendChild(toast);

        // Automatically remove the toast after 3 seconds
        setTimeout(() => toast.remove(), 3000);
    }
    $(document).ready(function () {
        var isCnameValid = false; // Flag to track if cname is valid
        const submitBtn = document.getElementById('submitBtn');

        submitBtn.disabled = true; // Correct way to disable the button initially

        // Function to check if cname exists
        function checkCnameExists(cname) {
            $.ajax({
                method: 'POST',
                url: 'server/jquery/check_cname.php', // Ensure this file handles the check
                data: { cname: cname },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.exists) {
                        showToast('Computer name already exists. Please choose a different name.', 'error');
                        isCnameValid = false;
                    } else {
                        isCnameValid = true;
                    }
                    submitBtn.disabled = !isCnameValid; // Enable or disable the button based on validation
                },
                error: function () {
                    showToast('Error checking computer name. Please try again.', 'error');
                    isCnameValid = false;
                    submitBtn.disabled = true;
                }
            });
        }

        // Event listener for cname input field
        $('#cname').on('input', function () {
            var cname = $(this).val().trim();
            if (cname.length > 0) {
                checkCnameExists(cname);
            } else {
                isCnameValid = false;
                submitBtn.disabled = true; // Ensure the button is disabled if input is empty
            }
        });
    });


</script>

<div class="container-fluid table-responsive d-flex flex-column" id="inventoryDashboard">

    <h2>Employee Data</h2>
    <div class="input-group my-2" id="searchAssets">
        <input type="text" id="getAssets" placeholder="SEARCH EMPLOYEE HERE"
            class="form-control border border-black input-search" />
        <label for="getAssets" class="input-group-text bg-dark text-white border border-black">Search</label>
    </div>
    <div class="admin-btn d-flex justify-content-between align-items-center">
        <form action="/tsv" method="post" id="csvForm" enctype="multipart/form-data" class="my-3">
            <div class="input-group">
                <label for="tsv" class="input-group-text bg-dark border border-black text-white">Import Employee Data</label>
                <label for="tsv" class="input-group-text border border-black" id="file-name-label">Upload TSV file</label>
                <input type="file" name="tsv" id="tsv" class="form-control" accept=".tsv" style="display:none;"
                    onchange="updateFileName()">
                <button type="submit" name="csvBtn" class="btn btn-dark">Submit</button>
            </div>
        </form>
        <div class="d-flex gap-3 mb-3">
            <label>Filter: </label>
            <div id="statusFilter" class="d-flex gap-2">
                <input type="radio" id="all" name="status" value="" checked>
                <label for="all" class="me-2">All</label>

                <input type="radio" id="allocated" name="status" value="1">
                <label for="allocated" class="me-2">Allocated</label>

                <input type="radio" id="not" name="status" value="0">
                <label for="not" class="me-2">No Allocated</label>
            </div>
        </div>
    </div>



    <table class="table text-center table-bordered">
        <thead class="table-dark">
            <tr>
                <th scope="col">Employee ID</th>
                <th scope="col">Name</th>
                <th scope="col">Email Address</th>
                <th scope="col">Department</th>
                <th scope="col">Documents</th>
            </tr>
        </thead>
        <tbody id="showdata">
        </tbody>
    </table>
    <div class="text-white">
        <nav>
            <ul class="bg-dark rounded p-2 d-flex justify-content-center align-items-center gap-3 border border-2 border-white"
                id="pagination">
                <!-- Pagination links will be inserted dynamically by JavaScript -->
            </ul>
        </nav>
    </div>

</div>

<script>
    function updateFileName() {
        const fileInput = document.getElementById('tsv');
        const label = document.getElementById('file-name-label');
        if (fileInput.files.length > 0) {
            label.textContent = fileInput.files[0].name;  // Update label with file name
        } else {
            label.textContent = 'Upload TSV file';  // Default text if no file is selected
        }
    }
    $(document).ready(function () {
        var currentPage = 1; // Track the current page globally

        function fetchData(page) {
            currentPage = page; // Update the global currentPage variable
            var getAssets = $('#getAssets').val().trim(); // Get search query
            var statusFilter = $('input[name="status"]:checked').val(); // Get selected radio button value

            $.ajax({
                method: 'POST',
                url: 'server/jquery/fetch_employee.php',
                data: {
                    name: getAssets,  // Include search query
                    status: statusFilter, // Include status filter
                    page: page        // Include current page for pagination
                },
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        if (data.data.length > 0) {
                            var html = '';

                            data.data.forEach(function (item) {
                                // If the employee is allocated, wrap the button in <a>, otherwise just show a disabled button
                                var buttonHtml = item.allocated
                                    ? `<a href="/admin-view?employee_id=${item.employee_id}">
                                            <button class="btn btn-danger">View</button>
                                        </a>`
                                    : `<button class="btn btn-secondary" disabled>View</button>`;

                                html += "<tr>";
                                html += "<td>" + item.employee_id + "</td>";
                                html += "<td>" + item.fname + ' ' + item.lname + "</td>";
                                html += "<td>" + item.email + "</td>";
                                html += "<td>" + item.dept + "</td>";
                                html += `<td>${buttonHtml}</td>`;
                                html += "</tr>";
                            });



                            $('#showdata').html(html); // Inject data into the table

                            // Create pagination links dynamically
                            var paginationHtml = '';

                            // Add the "First" and "Previous" links
                            paginationHtml += "<li class='page-item " + (currentPage === 1 ? 'disabled' : '') + "'>";
                            paginationHtml += "<a class='page-link' href='#' data-page='1' title='First'><span aria-hidden='true'>&laquo;&laquo;</span></a></li>";

                            paginationHtml += "<li class='page-item " + (currentPage === 1 ? 'disabled' : '') + "'>";
                            paginationHtml += "<a class='page-link' href='#' data-page='" + (currentPage - 1) + "' title='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";

                            // Define the range of pages to display dynamically
                            var pageLimit = 4; // Number of pages to display before and after the current page
                            var startPage = Math.max(1, currentPage - Math.floor(pageLimit / 2));
                            var endPage = Math.min(data.totalPages, currentPage + Math.floor(pageLimit / 2));

                            // Adjust to ensure we show at least a few pages before and after
                            if (endPage - startPage < pageLimit) {
                                if (startPage === 1) {
                                    endPage = Math.min(data.totalPages, startPage + pageLimit - 1);
                                } else if (endPage === data.totalPages) {
                                    startPage = Math.max(1, endPage - pageLimit + 1);
                                }
                            }

                            // Add page number links with ellipsis for skipped pages
                            for (var i = 1; i <= data.totalPages; i++) {
                                if (i === 1 || i === data.totalPages || (i >= startPage && i <= endPage)) {
                                    var activeClass = (i === currentPage) ? 'active' : '';
                                    paginationHtml += "<li class='page-item " + activeClass + "'><a class='page-link' href='#' data-page='" + i + "'>" + i + "</a></li>";
                                } else if (i === startPage - 1 || i === endPage + 1) {
                                    paginationHtml += "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                                }
                            }

                            // Add the "Next" and "Last" links
                            paginationHtml += "<li class='page-item " + (currentPage === data.totalPages ? 'disabled' : '') + "'>";
                            paginationHtml += "<a class='page-link' href='#' data-page='" + (currentPage + 1) + "' title='Next'><span aria-hidden='true'>&raquo;</span></a></li>";

                            paginationHtml += "<li class='page-item " + (currentPage === data.totalPages ? 'disabled' : '') + "'>";
                            paginationHtml += "<a class='page-link' href='#' data-page='" + data.totalPages + "' title='Last'><span aria-hidden='true'>&raquo;&raquo;</span></a></li>";

                            paginationHtml += '</ul></nav></div>';

                            $('#pagination').html(paginationHtml); // Inject pagination links

                        } else {
                            $('#showdata').html("<tr><td colspan='6'>No employee found</td></tr>");
                        }
                    } catch (e) {
                        console.error("Error parsing JSON response", e);
                        $('#showdata').html("<tr><td colspan='6'>An error occurred while fetching data.</td></tr>");
                    }
                },
                error: function () {
                    $('#showdata').html("<tr><td colspan='6'>An error occurred while fetching data.</td></tr>");
                }
            });
        }



        // Initial data fetch when the page loads
        fetchData(1);

        // Handle pagination link clicks
        $(document).on('click', '#pagination .page-link', function (e) {
            e.preventDefault();
            var page = $(this).data('page');
            fetchData(page); // Fetch data for the clicked page
        });

        $(document).on('change', 'input[name="status"]', function () {
            fetchData(1);
        });

        // Search function when typing in the search input
        $('#getAssets').on('keyup', function () {
            fetchData(1); // Reload data from the first page when the search input changes
        });
    });
</script>

<div class="container" id="checkID">

    <div class="card">

        <div class="card-header">

            <h5>Enter your Employee ID</h5>

        </div>

        <div class="p-5 card-body">

            <div class="input-group">

                <input type="number" name="employee_id" class="form-control" placeholder="Employee ID" id="id">

                <button type="submit" class="btn btn-dark" id="submitBtn" disabled>Submit</button>

            </div>

        </div>

    </div>

</div>





<script>

    function validateContact(input) {

        if (input.value.length > 11) {

            input.value = input.value.slice(0, 11);

        }

    }

    // Function to show toast notifications

    function showToast(message, status) {

        // Define colors based on status

        const bgColor = status === 'success' ? 'warning' : 'danger';

        const textColor = bgColor === 'danger' ? 'text-white' : '';



        // Create the toast element

        const toast = document.createElement('div');

        toast.className = `toast show bg-${bgColor} ${textColor}`;

        toast.setAttribute('role', 'alert');

        toast.innerHTML = `

        <div class="toast-body justify-content-between d-flex">

            ${message}

            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>

        </div>

    `;



        // Create the toast container if it doesn't exist

        let toastContainer = document.querySelector('.toast-container');

        if (!toastContainer) {

            toastContainer = document.createElement('div');

            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';

            document.body.appendChild(toastContainer);

        }



        // Append the toast to the container

        toastContainer.appendChild(toast);



        // Automatically remove the toast after 3 seconds

        setTimeout(() => toast.remove(), 3000);

    }

    $(document).ready(function () {

        const submitBtn = document.getElementById('submitBtn');



        submitBtn.disabled = true; // Ensure button starts as disabled



        function checkEmployeeExists(employee_id) {

            $.ajax({

                method: 'POST',

                url: 'server/jquery/check_employee_id.php',

                data: { employee_id: employee_id }, // Ensure key matches PHP script

                success: function (response) {

                    var data = JSON.parse(response);

                    if (data.exists) {

                        showToast('Employee ID is valid.', 'success');

                        submitBtn.disabled = false; // Enable button when valid

                    } else {

                        showToast('Employee ID not found.', 'error');

                        submitBtn.disabled = true;

                    }

                },

                error: function () {

                    showToast('Error checking Employee ID. Please try again.', 'error');

                    submitBtn.disabled = true;

                }

            });

        }



        $('#id').on('input', function () {

            var employee_id = $(this).val().trim();

            if (employee_id.length > 0) {

                checkEmployeeExists(employee_id);

            } else {

                submitBtn.disabled = true;

            }

        });

    });







</script>

<?php
// Ensure you have established your database connection in $conn

$resultNotFound = '';

if (isset($_GET['cname_id']) && !empty($_GET['cname_id'])) {
    $cname_id = $_GET['cname_id'];

    // Pagination variables
    $limit = 10;
    $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
    $offset = ($page - 1) * $limit;

    // COUNT QUERY:
    // We join the three tables and count distinct history records by concatenating the three IDs.

    $countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM computer_history WHERE cname_id = ?");
    $countStmt->bind_param("s", $cname_id);
    $countStmt->execute();
    $countResult = $countStmt->get_result()->fetch_assoc();
    $totalRecords = $countResult['total'];
    $totalPages = ceil($totalRecords / $limit);

    // Ensure that the page number is within the valid range.
    $page = max(1, min($page, $totalPages));

    // MAIN QUERY:
    // Instead of using a CASE with subqueries (which may return multiple rows),
    // we join the computer table using COALESCE on the three possible cname_id fields.
    // This way we select the computer name directly.
    $stmt = $conn->prepare("SELECT DISTINCT ch.allocation_id, ch.transfer_id, ch.return_id, c.cname
                                    FROM computer_history ch
                                    LEFT JOIN allocation a ON ch.allocation_id = a.allocation_id
                                    LEFT JOIN transferred t ON ch.transfer_id = t.transfer_id
                                    LEFT JOIN returned r ON ch.return_id = r.return_id
                                    JOIN computer c ON c.cname_id = COALESCE(a.cname_id, t.cname_id, r.cname_id)
                                    WHERE 
                                        (ch.allocation_id IS NOT NULL AND a.cname_id = ?)
                                        OR (ch.transfer_id IS NOT NULL AND t.cname_id = ?)
                                        OR (ch.return_id IS NOT NULL AND r.cname_id = ?)
                                    ORDER BY
                                        GREATEST(
                                            IFNULL(a.created_at, '0000-00-00 00:00:00'),
                                            IFNULL(t.created_at, '0000-00-00 00:00:00'),
                                            IFNULL(r.created_at, '0000-00-00 00:00:00')
                                        ) DESC
                                    LIMIT ?, ?
                                ");

    // Bind parameters: three for cname_id and two integers for offset and limit.
    $stmt->bind_param("sssii", $cname_id, $cname_id, $cname_id, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $resultNotFound = 'Data Not Found';
    }
}
?>
<div class="container py-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>History</h3>
            <a href="/inventory">
                <button class="btn btn-danger" id="closeModal">X</button>
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Employee</th>
                        <th scope="col">Status</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultNotFound): ?>
                        <tr>
                            <td colspan="4" class="text-center"><?= htmlspecialchars($resultNotFound) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <?php
                            $status = '<span class="badge bg-success">' . "Allocated" . '</span>';
                            $employeeId = null;
                            $time = null;
                            $badge = '';

                            // Check record type and fetch data accordingly.
                            if ($row['allocation_id']) {
                                $stmtAlloc = $conn->prepare("SELECT employee_id, created_at FROM allocation WHERE allocation_id = ?");
                                $stmtAlloc->bind_param("s", $row['allocation_id']);
                                $stmtAlloc->execute();
                                $allocData = $stmtAlloc->get_result()->fetch_assoc();
                                if ($allocData) {
                                    $employeeId = $allocData['employee_id'];
                                    $time = $allocData['created_at'];
                                }
                            } elseif ($row['transfer_id']) {
                                $stmtTrans = $conn->prepare("SELECT t.t_employee_id, t.employee_id, t.created_at, e.fname, e.lname FROM transferred t LEFT JOIN employee e ON e.employee_id = t.employee_id WHERE transfer_id = ?");
                                $stmtTrans->bind_param("s", $row['transfer_id']);
                                $stmtTrans->execute();
                                $transData = $stmtTrans->get_result()->fetch_assoc();
                                if ($transData) {
                                    $employeeName = $transData['fname'] . ' ' . $transData['lname'];
                                    $employeeId = $transData['t_employee_id'];
                                    $time = $transData['created_at'];
                                    $status = '<span class="badge bg-danger ">' . "Transferred from " . htmlspecialchars($employeeName) . '</span>';
                                    // Original employee information
                                    // $badge = '<span class="badge bg-danger ms-2">from ' . htmlspecialchars($transData['employee_id']) . '</span>';
                                }
                            } elseif ($row['return_id']) {
                                $stmtReturn = $conn->prepare("SELECT employee_id, created_at FROM returned WHERE return_id = ?");
                                $stmtReturn->bind_param("s", $row['return_id']);
                                $stmtReturn->execute();
                                $returnData = $stmtReturn->get_result()->fetch_assoc();
                                if ($returnData) {
                                    $employeeId = $returnData['employee_id'];
                                    $time = $returnData['created_at'];
                                    $status = '<span class="badge bg-dark">' . "Returned" . '</span>';
                                }
                            }

                            // Fetch employee details from employee table.
                            $employeeName = "Unknown";
                            $employeeID = "";
                            if ($employeeId) {
                                $stmtEmp = $conn->prepare("SELECT fname, lname, employee_id FROM employee WHERE employee_id = ?");
                                $stmtEmp->bind_param("s", $employeeId);
                                $stmtEmp->execute();
                                $empData = $stmtEmp->get_result()->fetch_assoc();
                                if ($empData) {
                                    $employeeName = $empData['fname'] . ' ' . $empData['lname'];
                                    $employeeID = $empData['employee_id'];
                                }
                            }
                            ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($employeeName) ?>
                                    <?php if ($employeeID): ?>
                                        <span class="badge bg-warning text-dark ms-2"><?= htmlspecialchars($employeeID) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= $status ?>
                                    <?= $badge ?>
                                </td>
                                <td><?= htmlspecialchars($time) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination Navigation -->

            <div class="text-white">
                <?php if ($resultNotFound): ?>
                    <!-- No data found, don't show pagination -->
                <?php else: ?>
                    <nav>
                        <ul class="bg-dark rounded p-2 d-flex justify-content-center align-items-center gap-3 border border-2 border-white"
                            id="pagination">
                            <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?cname_id=<?= htmlspecialchars($cname_id) ?>&page=1"
                                    title="First">
                                    <span aria-hidden="true">&laquo;&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?cname_id=<?= htmlspecialchars($cname_id) ?>&page=<?= $page - 1 ?>"
                                    title="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="?cname_id=<?= htmlspecialchars($cname_id) ?>&page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($page == $totalPages) ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?cname_id=<?= htmlspecialchars($cname_id) ?>&page=<?= $page + 1 ?>" title="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            <li class="page-item <?= ($page == $totalPages) ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?cname_id=<?= htmlspecialchars($cname_id) ?>&page=<?= $totalPages ?>"
                                    title="Last">
                                    <span aria-hidden="true">&raquo;&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$notFound = '';
$resultNotFound = '';
$rows = [];
$assetsDetails = [];

// Check if employee_id is in the cookie
$employee_id_from_cookie = isset($_COOKIE['employee_id']) ? $_COOKIE['employee_id'] : '';

// Check if employee_id is set in the URL
if (isset($_GET['employee_id'])) {
    $employee_id = trim($_GET['employee_id']); // Trim to remove spaces

    // Check if the employee_id from the URL matches the one in the cookie
    if ($employee_id !== $employee_id_from_cookie) {
        // Redirect to a different page if they don't match
        echo "<script> window.location = '/employee-id'; </script>";
        exit();
    }

    // Validate input
    if (empty($employee_id)) {
        echo "<script> window.location = '/employee-id'; </script>";
        exit();
    }

    // Fetch computer history and cname using cname_id
    $stmt = $conn->prepare("SELECT a.*, c.cname, c.assets_id 
                            FROM allocation a
                            LEFT JOIN computer c ON a.cname_id = c.cname_id
                            WHERE a.employee_id = ? AND a.status = 1 ORDER BY created_at DESC");
    $stmt->bind_param("s", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $computerAssets = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
        $computerAssets[] = $row['assets_id']; // Collect serialized assets_id
    }

    if (empty($rows)) {
        $resultNotFound = 'No Data Found';
    }

    // Fetch employee details
    $fetchEmployeeStmt = $conn->prepare("SELECT * FROM employee WHERE employee_id = ?");
    $fetchEmployeeStmt->bind_param("s", $employee_id);
    $fetchEmployeeStmt->execute();
    $show = $fetchEmployeeStmt->get_result()->fetch_assoc() ?? null;

    if (!$show) {
        $notFound = 'Employee Not Found';
    }

    // Process assets_id: unserialize and get asset details
    foreach ($computerAssets as $index => $serializedAssets) {
        if (!empty($serializedAssets)) {
            $assetIds = unserialize($serializedAssets); // Unserialize to get individual asset IDs
            if (is_array($assetIds) && count($assetIds) > 0) {
                // Convert array to a string of placeholders for SQL query
                $placeholders = implode(',', array_fill(0, count($assetIds), '?'));

                // Prepare the query to fetch asset details
                $query = "SELECT * FROM assets WHERE assets_id IN ($placeholders)";
                $stmt = $conn->prepare($query);

                if ($stmt) {
                    $stmt->bind_param(str_repeat('s', count($assetIds)), ...$assetIds);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($asset = $result->fetch_assoc()) {
                        $assetsDetails[] = $asset;
                    }
                }
            }
        }
    }
}

?>
<style>
    /* Hide everything except the terms-conditions section when printing */
    @media print {
        body * {
            visibility: hidden;
        }

        .not {
            visibility: hidden !important;
            display: none;
        }

        .card-footer {
            visibility: hidden !important;
            display: none;
        }

        #terms-conditions,
        #terms-conditions * {
            visibility: visible;
        }

        #terms-conditions {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 20px;
            /* Adjust padding for print */
            box-shadow: none;
            font-size: 12px;
            /* Remove shadows for print */
        }

        /* Optional: Adjust table borders for better print visibility */
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000 !important;
        }

        /* Hide buttons and other unnecessary elements */
        #printBtn,
        #cancelPrint {
            display: none !important;
        }
    }
</style>
<div class="container py-5">
    <div class="card" id="terms-conditions">
        <div class="card-header p-3">
            <div class="d-flex flex-column justify-content-center align-items-center text-center">
                <h2 class="text-uppercase fw-bold">HPL Gamedesign Corporation</h2>
                <p>82 Road 3 Project 6 Quezon City, Metro Manila, 1100
                    <br />
                    admin@hplgamedesign.com ᐧ 09202773422 ᐧ (02) 8 808 6920
                </p>
            </div>
        </div>
        <div class="card-body p-3">
            <h2 class="text-center mb-5">EMPLOYEE EQUIPMENT AGREEMENT</h2>
            <section>
                <p class="text-justify text-wrap" style="line-height: 2em;">
                    I, <span class="fw-bold fs-5 fst-italic" style="text-decoration: underline;">
                        &emsp;<?= isset($show) ? htmlspecialchars($show['fname'] . ' ' . $show['lname']) : '' ?>&emsp;
                    </span>, hereby acknowledge and agree to the following terms and conditions regarding the equipment
                    supplied to me by HPL Gamedesign Corporation, referred to as the Company:
                    <br><br>
                    Equipment Care and Responsibility: I agree to take proper care of all equipment supplied to me by
                    the
                    Company. This includes, but is not limited to, laptops, cell phones, monitors, software licenses, or
                    any other company-provided equipment deemed necessary by Company management for the performance of
                    my job duties. Proper care entails safeguarding the equipment from damage and ensuring its
                    maintenance in good working condition.
                    <br><br>
                    Equipment Return Policy: Upon termination of my employment, whether by resignation or termination, I
                    understand and agree to return all Company-supplied equipment within the specified time-frames:
                    <br><br>
                    ● All employees, including those working remotely or on temporary work-from-home arrangements,
                    are
                    required to promptly return all issued equipment when instructed by the Company within 72hrs.
                    <br><br>
                    ● Following resignation, all issued equipment must be returned within 24 hours.
                    <br><br>
                    Condition of Returned Equipment: I acknowledge that all equipment must be returned in proper
                    working
                    order. Any damage to or malfunction of the equipment beyond normal wear and tear may result in
                    financial responsibility on my part.
                    <br><br>
                    Business Use Only: I understand and agree that any equipment provided by the Company is to be
                    used
                    solely for business purposes and shall not be used for personal activities or non-work-related
                    endeavors.
                    <br><br>
                    Consequences of Non-Compliance: Failure to return any equipment supplied by the Company after
                    the
                    termination of my employment may be considered theft and may result in criminal prosecution by the
                    Company. Additionally, I acknowledge that failure to comply with the terms of this agreement may
                    lead to disciplinary action, including potential legal consequences.
                    <br><br>
                    Termination Conditions: The terms of this agreement apply regardless of the circumstances of
                    termination, including resignation, termination for cause, or termination without cause.
                </p>
            </section>

            <table class="table table-bordered my-5">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Computer Name</th>
                        <th scope="col">Assets</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Model</th>
                        <th scope="col">S/N</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultNotFound): ?>
                        <tr class="text-center">
                            <td colspan="6"><?= $resultNotFound ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($assetsDetails as $asset): ?>
                            <tr>
                                <td><?= htmlspecialchars($rows[0]['cname'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($asset['assets']) ?></td>
                                <td><?= htmlspecialchars($asset['brand']) ?></td>
                                <td><?= htmlspecialchars($asset['model']) ?></td>
                                <td><?= htmlspecialchars($asset['sn']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <section class="text-center">
                <p>By signing below, I acknowledge that I have reviewed each point of this agreement and agree to all
                    the conditions above. </p>
            </section>
            <div>
                <table class="table table-bordered">
                    <thead>
                        <tr class="fw-bold">
                            <td>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>Date Released:</div>
                                    <?php
                                    if (isset($rows[0]['created_at'])) {
                                        // Convert the created_at to a DateTime object and format it
                                        $date = new DateTime($rows[0]['created_at']);
                                        echo htmlspecialchars($date->format('F d, Y'));
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </div>
                            </td>
                            <td class="d-flex align-items-center gap-5 justify-content-end" id="custody-radio">
                                <?php if (isset($show['status']) && $show['status'] == 'NEW HIRE'): ?>
                                    <input type="radio" name="e_status" id="e-new-hire" checked disabled>
                                    <label for="e-new-hire">New Hire</label>
                                    <input type="radio" name="e_status" id="e-wfh" disabled>
                                    <label for="e-wfh">WFH</label>
                                    <input type="radio" name="e_status" id="e-temp" disabled>
                                    <label for="e-temp">TEMP WFH</label>
                                <?php elseif (isset($show['status']) && $show['status'] == 'WFH'): ?>
                                    <input type="radio" name="e_status" id="e-new-hire" disabled>
                                    <label for="e-new-hire">New Hire</label>
                                    <input type="radio" name="e_status" id="e-wfh" checked disabled>
                                    <label for="e-wfh">WFH</label>
                                    <input type="radio" name="e_status" id="e-temp" disabled>
                                    <label for="e-temp">TEMP WFH</label>
                                <?php else: ?>
                                    <input type="radio" name="e_status" id="e-new-hire" disabled>
                                    <label for="e-new-hire">New Hire</label>
                                    <input type="radio" name="e_status" id="e-wfh" disabled>
                                    <label for="e-wfh">WFH</label>
                                    <input type="radio" name="e_status" id="e-temp" checked disabled>
                                    <label for="e-temp">TEMP WFH</label>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="fw-bold">
                            <td colspan="2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>Department:</div>
                                    <div class="px-2">
                                        <?= htmlspecialchars($show['dept'] ? $show['dept'] : 'UNDEFINED') ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">
                                <form action="/esignature?employee_id=<?= $employee_id ?>" method="post"
                                    id="e-signature" enctype="multipart/form-data">
                                    <div class="input-group">
                                        <?php
                                        // Fetch the image URL from the database
                                        $fetchImage = $conn->query("SELECT signature FROM employee WHERE employee_id = '$employee_id'");
                                        if ($result = $fetchImage->fetch_assoc()) {
                                            $imageUrl = $result['signature'];
                                        } else {
                                            $imageUrl = null; // Default to null if no image is found
                                        }
                                        ?>

                                        <!-- Show file input and upload button ONLY if no image exists -->
                                        <?php if (empty($imageUrl)): ?>
                                            <input type="file" class="form-control" name="e-signature" id="e-signature"
                                                aria-describedby="e-signature" aria-label="Upload"
                                                accept=".jpg, .jpeg, .png" required>
                                            <button class="btn btn-dark h-50" type="submit" id="e-signature"
                                                name="signature">Upload</button>
                                        <?php endif; ?>

                                        <!-- Display the uploaded image if it exists -->
                                        <?php if (!empty($imageUrl)): ?>
                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                <!-- Check if the image path is from the server directory or Cloudinary -->
                                                <?php if (strpos($imageUrl, '/server/signature/') === 0): ?>
                                                    <!-- If it's a local path, display from the server directory -->
                                                    <img src="<?= $imageUrl ?>" alt="Employee Signature"
                                                        class="img-fluid w-25 mt-5">
                                                <?php else: ?>
                                                    <!-- If it's a Cloudinary URL, display from Cloudinary -->
                                                    <img src="<?= $imageUrl ?>" alt="Employee Signature"
                                                        class="img-fluid w-25 mt-5">
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </form>
                                <h3 class="fst-italic fs-5 mt-3 fw-bold">
                                    <u><?= isset($show) ? htmlspecialchars($show['fname'] . ' ' . $show['lname']) : 'N/A' ?></u>
                                </h3>
                            </td>
                            <td class="text-center">
                                <?php
                                $fetchAdmin = $conn->query("SELECT * FROM users WHERE type = 1");
                                if ($result = $fetchAdmin->fetch_assoc()) {
                                    $username = $result['username'];
                                    if (!empty($result['signature'])) {
                                        $adminURL = $result['signature'];
                                    } else {
                                        $adminURL = null;
                                    }
                                    ?>
                                    <form action="/esignature?username=<?= $username ?>" method="post" id="a-signature"
                                        enctype="multipart/form-data">
                                        <div class="input-group">
                                            <!-- Show file input and upload button ONLY if no image exists -->
                                            <?php if (empty($adminURL)): ?>
                                                <input type="file" class="form-control" name="a-signature" id="a-signature"
                                                    aria-describedby="a-signature" aria-label="Upload"
                                                    accept=".jpg, .jpeg, .png" required>
                                                <button class="btn btn-dark h-50" type="submit" id="a-signature"
                                                    name="admin-signature">Upload</button>
                                            <?php endif; ?>

                                            <!-- Display the uploaded image if it exists -->
                                            <?php if (!empty($adminURL)): ?>
                                                <div class="d-flex flex-column justify-content-center align-items-center">
                                                    <!-- Check if the image path is from the server directory or Cloudinary -->
                                                    <?php if (strpos($adminURL, '/server/signature/') === 0): ?>
                                                        <!-- If it's a local path, display from the server directory -->
                                                        <img src="<?= $adminURL ?>" alt="Admin Signature"
                                                            class="img-fluid w-25 mt-5">
                                                    <?php else: ?>
                                                        <!-- If it's a Cloudinary URL, display from Cloudinary -->
                                                        <img src="<?= $adminURL ?>" alt="Admin Signature"
                                                            class="img-fluid w-25 mt-5">
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                    </form>
                                    <h3 class="fst-italic fs-5 mt-3 fw-bold">
                                        <u><?= htmlspecialchars($result['fname'] . ' ' . $result['lname']) ?></u>
                                    </h3>
                                    <?php
                                } ?>
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td>
                                EMPLOYEE NAME & SIGNATURE
                            </td>
                            <td>
                                IT PERSONNEL - NAME & SIGNATURE
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer p-3 d-flex justify-content-center align-items-center gap-2">
            <p class="not">To ensure the document prints correctly, please enable <strong>Headers and Footers</strong>
                and <strong>Background Graphics</strong> in the print window under <strong>More Settings</strong>.</p>

            <button type="button" class="btn btn-dark w-25" id="printBtn">Print</button>
            <button type="button" class="btn btn-danger w-25" id="cancelPrint">Cancel</button>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const printBtn = document.getElementById('printBtn');
        const cancelBtn = document.getElementById('cancelPrint');

        printBtn.onclick = () => {
            // Force a reflow to ensure styles are applied
            document.body.offsetHeight;

            // Trigger the print dialog
            window.print();
        };

        cancelBtn.onclick = () => {
            window.location = "/employee";
        };
    });
</script>
<div class="container-fluid table-responsive d-flex flex-column gap-4" id="inventoryDashboard">
    <h2>Inventory Dashboard</h2>
    <div class="input-group" id="searchAssets">
        <label for="getAssets" class="input-group-text bg-dark text-white border border-black">Search</label>
        <input type="text" id="getAssets" placeholder="SEARCH BY USING COMPUTER ID OR COMPUTER NAME"
            class="form-control border border-black input-search" />
    </div>
    <div class="admin-btn d-flex justify-content-between align-items-center">
        <div class="d-flex gap-3 mb-2">
            <label>Filter by Available PC: </label>
            <div id="pcFilter" class="d-flex gap-2 flex-wrap">
                <!-- jquery -->
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="/stocks">
                <button class="btn btn-dark w-auto">Parts Dashboard</button>
            </a>
            <a href="/add">
                <button type="button" class="btn btn-danger w-auto" id="addAssets">Add Assets</button>
            </a>
        </div>
    </div>
    <table class="table text-center table-bordered">
        <thead class="table-dark">
            <tr>
                <th scope="col">Computer ID</th>
                <th scope="col">Computer Name</th>
                <th scope="col">Specifications</th>
                <th scope="col">History</th>
            </tr>
        </thead>
        <tbody id="showdata">
        </tbody>
    </table>
    <div class="text-white">
        <nav>
            <ul class="bg-dark rounded p-2 d-flex justify-content-center align-items-center gap-3 border border-2 border-white"
                id="pagination">
                <!-- Pagination links will be inserted dynamically by JavaScript -->
            </ul>
        </nav>
    </div>
    <script>
        $(document).ready(function () {
            var currentPage = 1; // Track the current page globally

            // Function to fetch data with pagination
            function fetchData(page) {
                currentPage = page;
                var getAssets = $('#getAssets').val().trim();
                var pcFilter = $('input[name="pcFilter"]:checked').val() || '';

                $.ajax({
                    method: 'POST',
                    url: 'server/jquery/inventory.php',
                    data: {
                        name: getAssets,
                        pcFilter: pcFilter,
                        page: page
                    },
                    success: function (response) {
                        try {
                            var data = JSON.parse(response);

                            // Render pc filter radio buttons
                            var pcFilterHtml = `
                            <input type="radio" id="status_all" name="pcFilter" value="" ${pcFilter === '' ? 'checked' : ''}>
                            <label for="status_all" class="me-2">All</label>
                            <input type="radio" id="status_available" name="pcFilter" value="Available" ${pcFilter === 'Available' ? 'checked' : ''}>
                            <label for="status_available" class="me-2">Available</label>
                            <input type="radio" id="status_not" name="pcFilter" value="Not Available" ${pcFilter === 'Not Available' ? 'checked' : ''}>
                            <label for="status_not" class="me-2">Not Available</label>
                        `;
                            $('#pcFilter').html(pcFilterHtml);

                            if (data.message) {
                                $("#showdata").html("<tr><td colspan='5'>No assets found</td></tr>");
                                $('#pagination').html('');
                            } else {
                                var html = '';
                                data.data.forEach(function (item) {
                                    html += "<tr>";
                                    html += "<td>" + item.cname_id + "</td>";
                                    html += "<td>" + item.cname + "</td>";
                                    html += "<td><a href='/specs?cname_id=" + item.cname_id + "'><button type='button' class='btn btn-dark'>View Specifications</button></a></td>";
                                    html += "<td><a href='/history?cname_id=" + item.cname_id + "'><button type='button' class='btn btn-warning'>View History</button></a></td>";
                                    html += "</tr>";
                                });

                                $("#showdata").html(html);

                                // Pagination logic
                                var paginationHtml = '';

                                // Add the "First" and "Previous" links
                                paginationHtml += "<li class='page-item " + (currentPage === 1 ? 'disabled' : '') + "'>";
                                paginationHtml += "<a class='page-link' href='#' data-page='1' title='First'><span aria-hidden='true'>&laquo;&laquo;</span></a></li>";

                                paginationHtml += "<li class='page-item " + (currentPage === 1 ? 'disabled' : '') + "'>";
                                paginationHtml += "<a class='page-link' href='#' data-page='" + (currentPage - 1) + "' title='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";

                                // Define the range of pages to display dynamically
                                var pageLimit = 4; // Number of pages to display before and after the current page
                                var startPage = Math.max(1, currentPage - Math.floor(pageLimit / 2));
                                var endPage = Math.min(data.totalPages, currentPage + Math.floor(pageLimit / 2));

                                // Adjust to ensure we show at least a few pages before and after
                                if (endPage - startPage < pageLimit) {
                                    if (startPage === 1) {
                                        endPage = Math.min(data.totalPages, startPage + pageLimit - 1);
                                    } else if (endPage === data.totalPages) {
                                        startPage = Math.max(1, endPage - pageLimit + 1);
                                    }
                                }

                                // Add page number links with ellipsis for skipped pages
                                for (var i = 1; i <= data.totalPages; i++) {
                                    if (i === 1 || i === data.totalPages || (i >= startPage && i <= endPage)) {
                                        var activeClass = (i === currentPage) ? 'active' : '';
                                        paginationHtml += "<li class='page-item " + activeClass + "'><a class='page-link' href='#' data-page='" + i + "'>" + i + "</a></li>";
                                    } else if (i === startPage - 1 || i === endPage + 1) {
                                        paginationHtml += "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                                    }
                                }

                                // Add the "Next" and "Last" links
                                paginationHtml += "<li class='page-item " + (currentPage === data.totalPages ? 'disabled' : '') + "'>";
                                paginationHtml += "<a class='page-link' href='#' data-page='" + (currentPage + 1) + "' title='Next'><span aria-hidden='true'>&raquo;</span></a></li>";

                                paginationHtml += "<li class='page-item " + (currentPage === data.totalPages ? 'disabled' : '') + "'>";
                                paginationHtml += "<a class='page-link' href='#' data-page='" + data.totalPages + "' title='Last'><span aria-hidden='true'>&raquo;&raquo;</span></a></li>";

                                paginationHtml += '</ul></nav></div>';

                                $('#pagination').html(paginationHtml); // Inject pagination links
                            }
                        } catch (e) {
                            console.error("Error parsing JSON response", e);
                            $("#showdata").html("<tr><td colspan='5'>An error occurred while fetching data.</td></tr>");
                            $('#pagination').html(''); // Clear pagination if no data
                        }
                    },
                    error: function () {
                        $("#showdata").html("<tr><td colspan='5'>An error occurred while fetching data.</td></tr>");
                        $('#pagination').html(''); // Clear pagination if no data
                    }
                });
            }

            // Initial data fetch when the page loads
            fetchData(1);

            // Handle pagination link clicks
            $(document).on('click', '#pagination .page-link', function (e) {
                e.preventDefault();
                var page = $(this).data('page');
                fetchData(page); // Fetch data for the clicked page
            });

            // Search function when typing in the search input
            $('#getAssets').on('keyup', function () {
                fetchData(1); // Reload data from the first page when the search input changes
            });

            // Handle pc filter change
            $(document).on('change', 'input[name="pcFilter"]', function () {
                fetchData(1); // Reload data from the first page when the filter changes
            });
        });
    </script>
</div>

<div>
    <h2 class="my-2 mb-4">Parts and Components</h2>
    <div class="input-group my-2" id="searchAssets">
        <input type="text" id="getAssets" placeholder="SEARCH ASSETS HERE"
            class="form-control border border-black input-search" />
        <label for="getAssets" class="input-group-text bg-dark text-white border border-black">Search</label>
    </div>
    <div class="d-flex justify-content-between">
        <!-- Asset Filter -->
        <div class="d-flex gap-3 mb-3">
            <label>Filter by Asset: </label>
            <div id="assetFilter" class="d-flex gap-2 flex-wrap">
                <input type="radio" id="assets" name="assetFilter" value="">
                <label for="assets">All</label>
            </div>
        </div>
        <!-- Status Filter -->
        <div class="d-flex gap-3 mb-3">
            <label>Filter by Status: </label>
            <div id="statusFilter" class="d-flex gap-2 flex-wrap">
                <!-- <input type="radio" id="assetsStatus" name="statusFilter" value="">
                    <label for="assetsStatus">All</label> -->
            </div>
        </div>
    </div>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ASSOCIATED PC</th>
                <th>SPECIFIED</th>
                <th>BRAND</th>
                <th>MODEL</th>
                <th>S/N</th>
                <th>ADD TO</th>
                <th>DEFECTIVE ?</th>
            </tr>
        </thead>
        <tbody id="showdata">
            <!-- <tr>
                        <td>ASSETS ID</td>
                        <td>SPECIFIED</td>
                        <td>BRAND</td>
                        <td>MODEL</td>
                        <td>S/N</td>
                        <td>ACTION</td>
                    </tr> -->
        </tbody>
    </table>
    <div class="text-white">
        <nav>
            <ul class="bg-dark rounded p-2 d-flex justify-content-center align-items-center gap-3 border border-2 border-white"
                id="pagination">
                <!-- Pagination links will be inserted dynamically by JavaScript -->
            </ul>
        </nav>
    </div>
</div>
<script>
    $(document).ready(function () {
        var currentPage = 1; // Track the current page globally

        // Function to fetch data based on the search query
        function fetchData(page, searchQuery = '', assetFilter = '', statusFilter = '') {
            currentPage = page; // Update the global currentPage variable
            $.ajax({
                method: 'POST',
                url: 'server/jquery/parts.php',
                data: {
                    name: searchQuery,  // Send search query (empty if no search)
                    assetFilter: assetFilter, // Send asset filter (empty if no filter)
                    statusFilter: statusFilter, // Send asset filter (empty if no filter)
                    page: page          // Include current page for pagination
                },
                success: function (response) {
                    try {
                        var data = JSON.parse(response);

                        // Render asset filter radio buttons
                        var assetFilterHtml = `
                        <input type="radio" id="assets" name="assetFilter" value="" ${assetFilter === '' ? 'checked' : ''}>
                        <label for="assets" class="me-2">All</label>
                        `;

                        if (data.assetNames && data.assetNames.length > 0) {
                            data.assetNames.forEach(function (assetName, index) {
                                let assetId = `asset_${index}`;
                                assetFilterHtml += `
                                <input type="radio" id="${assetId}" name="assetFilter" value="${assetName}" ${assetFilter === assetName ? 'checked' : ''}>
                                <label for="${assetId}" class="me-2">${assetName}</label>
                                `;
                            });
                        }

                        $('#assetFilter').html(assetFilterHtml);

                        var statusFilterHtml = `
                            <input type="radio" id="status_all" name="statusFilter" value="" ${statusFilter === '' ? 'checked' : ''}>
                            <label for="status_all" class="me-2">All</label>
                            <input type="radio" id="status_installed" name="statusFilter" value="Installed" ${statusFilter === 'Installed' ? 'checked' : ''}>
                            <label for="status_installed" class="me-2">Installed</label>
                            <input type="radio" id="status_available" name="statusFilter" value="Available" ${statusFilter === 'Available' ? 'checked' : ''}>
                            <label for="status_available" class="me-2">Available</label>
                            <input type="radio" id="status_defective" name="statusFilter" value="Defective" ${statusFilter === 'Defective' ? 'checked' : ''}>
                            <label for="status_defective" class="me-2">Defective</label>
                        `;
                        $('#statusFilter').html(statusFilterHtml);


                        if (data.data.length > 0) {
                            var html = '';

                            data.data.forEach(function (item) {
                                html += "<tr>";
                                html += "<td>" + item.cname + "</td>";
                                html += "<td>" + item.assets + "</td>";
                                html += "<td>" + item.brand + "</td>";
                                html += "<td>" + item.model + "</td>";
                                html += "<td>" + item.sn + "</td>";

                                // Check if asset is installed, then change the button state and text
                                let installDisabled = (item.defective === 'Defective' || item.status === 'Installed') ? 'disabled' : '';
                                let buttonClass = (item.defective === 'Defective' || item.status === 'Installed') ? 'btn btn-secondary' : 'btn btn-dark';
                                let buttonText = item.defective === 'Defective' ? 'DEFECTIVE' : (item.status === 'Installed' ? 'Installed' : 'Install');


                                html += `<td>
                                            <a href="javascript:void(0);" style="${installDisabled ? 'pointer-events: none; opacity: 0.5;' : ''}">
                                                <button class="${buttonClass}" ${installDisabled}>
                                                    ${buttonText}
                                                </button>
                                            </a>
                                        </td>`;



                                if (item.defective === 'Defective') {
                                    html += `<td>
                                    <a href="javascript:void(0);" style="pointer-events: none; opacity: 0.5;">
                                        <button class="btn btn-secondary" disabled>
                                            DEFECTIVE
                                        </button>
                                    </a>
                                </td>`;
                                } else if (item.defective === 'Defective ?') {
                                    html += `<td>
                                    <a href="/defective?assets_id=${item.assets_id}">
                                        <button class="btn btn-danger">
                                            DEFECTIVE ?
                                        </button>
                                    </a>
                                </td>`;
                                }

                                html += "</tr>";
                            });

                            $('#showdata').html(html); // Inject data into the table

                            // Create pagination links dynamically
                            var paginationHtml = '';

                            // Add the "First" and "Previous" links
                            paginationHtml += "<li class='page-item " + (currentPage === 1 ? 'disabled' : '') + "'>";
                            paginationHtml += "<a class='page-link' href='#' data-page='1' title='First'><span aria-hidden='true'>&laquo;&laquo;</span></a></li>";

                            paginationHtml += "<li class='page-item " + (currentPage === 1 ? 'disabled' : '') + "'>";
                            paginationHtml += "<a class='page-link' href='#' data-page='" + (currentPage - 1) + "' title='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";

                            // Define the range of pages to display dynamically
                            var pageLimit = 4; // Number of pages to display before and after the current page
                            var startPage = Math.max(1, currentPage - Math.floor(pageLimit / 2));
                            var endPage = Math.min(data.totalPages, currentPage + Math.floor(pageLimit / 2));

                            // Adjust to ensure we show at least a few pages before and after
                            if (endPage - startPage < pageLimit) {
                                if (startPage === 1) {
                                    endPage = Math.min(data.totalPages, startPage + pageLimit - 1);
                                } else if (endPage === data.totalPages) {
                                    startPage = Math.max(1, endPage - pageLimit + 1);
                                }
                            }

                            // Add page number links with ellipsis for skipped pages
                            for (var i = 1; i <= data.totalPages; i++) {
                                if (i === 1 || i === data.totalPages || (i >= startPage && i <= endPage)) {
                                    var activeClass = (i === currentPage) ? 'active' : '';
                                    paginationHtml += "<li class='page-item " + activeClass + "'><a class='page-link' href='#' data-page='" + i + "'>" + i + "</a></li>";
                                } else if (i === startPage - 1 || i === endPage + 1) {
                                    paginationHtml += "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                                }
                            }

                            // Add the "Next" and "Last" links
                            paginationHtml += "<li class='page-item " + (currentPage === data.totalPages ? 'disabled' : '') + "'>";
                            paginationHtml += "<a class='page-link' href='#' data-page='" + (currentPage + 1) + "' title='Next'><span aria-hidden='true'>&raquo;</span></a></li>";

                            paginationHtml += "<li class='page-item " + (currentPage === data.totalPages ? 'disabled' : '') + "'>";
                            paginationHtml += "<a class='page-link' href='#' data-page='" + data.totalPages + "' title='Last'><span aria-hidden='true'>&raquo;&raquo;</span></a></li>";

                            paginationHtml += '</ul></nav></div>';

                            $('#pagination').html(paginationHtml); // Inject pagination links

                        } else {
                            $('#showdata').html("<tr><td class='text-center' colspan='7'>No parts found</td></tr>");
                            $('#pagination').html(''); // Clear pagination if no data
                        }
                    } catch (e) {
                        console.error("Error parsing JSON response", e);
                        $('#showdata').html("<tr><td colspan='7'>An error occurred while fetching data.</td></tr>");
                        $('#pagination').html(''); // Clear pagination if no data
                    }
                },
                error: function () {
                    $('#showdata').html("<tr><td colspan='7'>An error occurred while fetching data.</td></tr>");
                    $('#pagination').html(''); // Clear pagination if no data
                }
            });
        }

        // Initial data fetch when the page loads (with empty search query)
        fetchData(1, '');

        // Handle pagination link clicks
        $(document).on('click', '#pagination .page-link', function (e) {
            e.preventDefault();
            var page = $(this).data('page');
            var getAssets = $('#getAssets').val().trim();
            var assetFilter = $('input[name="assetFilter"]:checked').val();
            var statusFilter = $('input[name="statusFilter"]:checked').val();
            fetchData(page, getAssets, assetFilter, statusFilter);
        });

        // Search function when typing in the search input
        $('#getAssets').on('keyup', function () {
            var searchQuery = $(this).val().trim();
            var assetFilter = $('input[name="assetFilter"]:checked').val();
            var statusFilter = $('input[name="statusFilter"]:checked').val();
            fetchData(1, searchQuery, assetFilter, statusFilter);
        });

        // Handle asset filter change
        $(document).on('change', 'input[name="assetFilter"]', function () {
            var searchQuery = $('#getAssets').val().trim();
            var assetFilter = $(this).val();
            var statusFilter = $('input[name="statusFilter"]:checked').val();
            fetchData(1, searchQuery, assetFilter, statusFilter);
        });

        // Handle status filter change
        $(document).on('change', 'input[name="statusFilter"]', function () {
            var searchQuery = $('#getAssets').val().trim();
            var assetFilter = $('input[name="assetFilter"]:checked').val();
            var statusFilter = $(this).val();
            fetchData(1, searchQuery, assetFilter, statusFilter);
        });
    });
</script>

<div class="container d-flex flex-column align-items-center" id="register">
    <div class="shadow-lg bg-white p-4 rounded d-flex flex-column align-items-center">
        <h1 class="mb-5">REGISTER</h1>
        <form action="/register-employee" method="post" autocomplete="off" id="registerForm">
            <div class="row d-flex">
                <div class="col-2 form-floating">
                    <input type="text" id="employee_id" name="employee_id" class="form-control"
                        placeholder="Employee ID" required>
                    <label for="employee_id" class="employee-id"><small>Employee ID</small></label>
                </div>
                <div class="col-5 form-floating">
                    <input type="text" id="fname" name="fname" class="form-control" placeholder="First Name" required>
                    <label for="fname">First Name</label>
                </div>
                <div class="col-5 form-floating">
                    <input type="text" id="lname" name="lname" class="form-control" placeholder="Last Name" required>
                    <label for="lname">Last Name</label>
                </div>
            </div>
            <div class="row">
                <div class="col-6 form-floating">
                    <input type="text" id="dept" name="dept" class="form-control" placeholder="Department" required>
                    <label for="dept">Department</label>
                </div>
                <div class="col-6 form-floating">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email Address" required>
                    <label for="email">Email Address</label>
                </div>
            </div>
            <!-- <div class="row d-flex my-3 gap-3">
                <div class="col-md-12 form-floating">
                    <input type="number" id="contact" name="contact" class="form-control w-100"
                        placeholder="Contact Number" required pattern="\d{11}" maxlength="11"
                        oninput="validateContact(this)">
                    <label for="contact">Contact Number</label>
                </div>
            </div> -->
            <!-- <div class="row d-flex my-3">
                <div class="col-md-4 col-sm-12 form-floating">
                    <input type="text" id="street" name="street" class="form-control w-100" placeholder="Street"
                        required>
                    <label for="street">Street</label>
                </div>
                <div class="col-md-4 col-sm-12 form-floating">
                    <input type="text" id="brgy" name="brgy" class="form-control w-100" placeholder="Barangay" required>
                    <label for="brgy">Barangay</label>
                </div>
                <div class="col-md-4 col-sm-12 form-floating">
                    <input type="number" id="zip" name="zip" class="form-control w-100" placeholder="Zip Code" required
                        maxlength="5" oninput="validateZip(this)">
                    <label for="zip">Zip Code</label>
                </div>
            </div>
            <div class="row d-flex my-3">
                <div class="col-md-4 col-sm-12 form-floating">
                    <select id="region" name="region" class="form-control" required>
                        <option readonly>Select Region</option>
                    </select>
                </div>
                <div class="col-md-4 col-sm-12 form-floating">
                    <select id="province" name="province" class="form-control" required>
                        <option readonly>Select Province</option>
                    </select>
                </div>
                <div class="col-md-4 col-sm-12 form-floating">
                    <select id="city" name="city" class="form-control" required>
                        <option readonly>Select Municipality/City</option>
                    </select>
                </div>
            </div> -->


            <div class="row d-flex my-3">
                <div class="col-md-12 form-floating">
                    <select type="text" id="status" name="status" class="form-control" required>
                        <option class="bg-secondary-subtle" readonly>Work Status</option>
                        <option value="NEW HIRE">
                            NEW HIRE </option>
                        <option value="WFH">
                            WFH </option>
                        <option value="TEMP WFH">
                            TEMP WFH </option>
                    </select>
                </div>
            </div>
            <div class="form-buttons">
                <button type="submit" class="btn btn-dark" id="registerBtn" name="register">Submit</button>
                <button type="reset" class="btn btn-danger" onclick="parent.location.href=''">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function validateZip(input) {
        if (input.value.length > 5) {
            input.value = input.value.slice(0, 5);
        }
    }
    function validateContact(input) {
        if (input.value.length > 11) {
            input.value = input.value.slice(0, 11);
        }
    }

    function validateAge(input) {
        if (input.value < 0) {
            input.value = '';
        }
    }

    // document.addEventListener("DOMContentLoaded", () => {
    //     fetch("https://psgc.gitlab.io/api/regions/")
    //         .then((response) => response.json())
    //         .then((data) => {
    //             const regionSelect = document.getElementById("region");
    //             const provinceSelect = document.getElementById("province");
    //             const citySelect = document.getElementById("city");

    //             // Maps to store name-to-code mappings
    //             const regionMap = new Map(); // { regionName: regionCode }
    //             const provinceMap = new Map(); // { provinceName: provinceCode }
    //             const cityMap = new Map(); // { cityName: cityCode }

    //             // Populate regions dropdown
    //             data.forEach((region) => {
    //                 regionMap.set(region.name, region.code); // Store name-to-code mapping
    //                 const option = document.createElement("option");
    //                 option.value = region.name; // Save the name
    //                 option.textContent = region.name; // Display the name
    //                 regionSelect.appendChild(option);
    //             });

    //             // Region change event
    //             regionSelect.addEventListener("change", function () {
    //                 const regionName = this.value;
    //                 const regionCode = regionMap.get(regionName); // Get code from name

    //                 provinceSelect.innerHTML = '<option value="" selected>Select Province</option>';
    //                 citySelect.innerHTML = '<option value="" selected>Select Municipality/City</option>'; // Reset city select

    //                 if (regionCode === "130000000") {
    //                     // If NCR is selected, remove required from province
    //                     provinceSelect.removeAttribute("required");
    //                     provinceSelect.disabled = true; // Optionally disable the field
    //                 } else {
    //                     // If any other region is selected, add required back
    //                     provinceSelect.setAttribute("required", "required");
    //                     provinceSelect.disabled = false;
    //                 }

    //                 if (regionCode) {
    //                     Promise.all([
    //                         fetch(`https://psgc.gitlab.io/api/regions/${regionCode}/provinces/`).then(res => res.json()),
    //                         regionCode === "130000000"
    //                             ? fetch(`https://psgc.gitlab.io/api/regions/${regionCode}/cities/`).then(res => res.json())
    //                             : Promise.resolve([]),
    //                     ])
    //                         .then(([provinces, cities]) => {
    //                             provinceMap.clear(); // Clear previous province mappings
    //                             provinces.forEach((province) => {
    //                                 provinceMap.set(province.name, province.code); // Store name-to-code mapping
    //                                 const option = document.createElement("option");
    //                                 option.value = province.name; // Save the name
    //                                 option.textContent = province.name; // Display the name
    //                                 provinceSelect.appendChild(option);
    //                             });

    //                             if (regionCode === "130000000") { // If NCR, populate cities
    //                                 cityMap.clear(); // Clear previous city mappings
    //                                 cities.forEach((city) => {
    //                                     cityMap.set(city.name, city.code); // Store name-to-code mapping
    //                                     const option = document.createElement("option");
    //                                     option.value = city.name; // Save the name
    //                                     option.textContent = city.name; // Display the name
    //                                     citySelect.appendChild(option);
    //                                 });
    //                             }
    //                         })
    //                         .catch((error) => console.error("Error fetching data:", error));
    //                 }
    //             });

    //             // Province change event
    //             provinceSelect.addEventListener("change", function () {
    //                 const provinceName = this.value;
    //                 const provinceCode = provinceMap.get(provinceName); // Get code from name
    //                 citySelect.innerHTML = '<option value="" selected>Select Municipality/City</option>';

    //                 if (provinceCode) {
    //                     fetch(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities-municipalities/`)
    //                         .then(res => res.json())
    //                         .then(cities => {
    //                             cityMap.clear(); // Clear previous city mappings
    //                             cities.forEach((city) => {
    //                                 cityMap.set(city.name, city.code); // Store name-to-code mapping
    //                                 const option = document.createElement("option");
    //                                 option.value = city.name; // Save the name
    //                                 option.textContent = city.name; // Display the name
    //                                 citySelect.appendChild(option);
    //                             });
    //                         })
    //                         .catch(error => console.error("Error fetching cities:", error));
    //                 }
    //             });

    //             // // Form submission event
    //             // document.querySelector("form").addEventListener("submit", (event) => {
    //             //     event.preventDefault(); // Prevent default form submission

    //             //     // Get selected names
    //             //     const selectedRegionName = regionSelect.value;
    //             //     const selectedProvinceName = provinceSelect.value;
    //             //     const selectedCityName = citySelect.value;

    //             //     // Get corresponding codes from maps
    //             //     const selectedRegionCode = regionMap.get(selectedRegionName);
    //             //     const selectedProvinceCode = provinceMap.get(selectedProvinceName);
    //             //     const selectedCityCode = cityMap.get(selectedCityName);

    //             //     // Log or send data to backend
    //             //     console.log("Selected Names:", {
    //             //         region: selectedRegionName,
    //             //         province: selectedProvinceName,
    //             //         city: selectedCityName,
    //             //     });

    //             //     console.log("Corresponding Codes:", {
    //             //         region: selectedRegionCode,
    //             //         province: selectedProvinceCode,
    //             //         city: selectedCityCode,
    //             //     });

    //             //     // Example: Send data to backend
    //             //     // fetch("/your-backend-endpoint", {
    //             //     //     method: "POST",
    //             //     //     body: JSON.stringify({
    //             //     //         regionCode: selectedRegionCode,
    //             //     //         provinceCode: selectedProvinceCode,
    //             //     //         cityCode: selectedCityCode,
    //             //     //     }),
    //             //     // });
    //             // });
    //         })
    //         .catch((error) => console.error("Error fetching regions:", error));
    // });

    // $(document).ready(function () {
    //     $('#employee_id').on('blur', function () {
    //         var employeeID = $(this).val();
    //         $.ajax({
    //             url: 'server/jquery/check_id.php',
    //             type: 'POST',
    //             data: { employee_id: employeeID },
    //             success: function (response) {
    //                 if (response.exists) {
    //                     alert('Employee id already exists');
    //                 }
    //             }
    //         });
    //     });
    // });
    // Function to show toast notifications
    function showToast(message, status) {
        // Define colors based on status
        const bgColor = status === 'success' ? 'warning' : 'danger';
        const textColor = bgColor === 'danger' ? 'text-white' : '';

        // Create the toast element
        const toast = document.createElement('div');
        toast.className = `toast show bg-${bgColor} ${textColor}`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
        <div class="toast-body justify-content-between d-flex">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
    `;

        // Create the toast container if it doesn't exist
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }

        // Append the toast to the container
        toastContainer.appendChild(toast);

        // Automatically remove the toast after 3 seconds
        setTimeout(() => toast.remove(), 3000);
    }
    $(document).ready(function () {
        var isEmployeeID = false; // Flag to track if the Employee ID is valid
        const submitBtn = document.getElementById('registerBtn'); // Ensure your submit button has this ID

        // Disable the submit button initially
        submitBtn.disabled = true;

        // Function to check if the Employee ID exists
        function checkID(employee_id) {
            $.ajax({
                method: 'POST',
                url: 'server/jquery/check_id.php', // Ensure this endpoint handles the check correctly
                data: { employee_id: employee_id },
                success: function (response) {
                    // Parse the JSON response from the server
                    var data = JSON.parse(response);
                    if (data.exists) {
                        showToast('Employee ID already exists. Please choose a different ID.', 'error');
                        isEmployeeID = false;
                    } else {
                        isEmployeeID = true;
                    }
                    // Enable or disable the button based on the result
                    submitBtn.disabled = !isEmployeeID;
                },
                error: function () {
                    showToast('Error checking Employee ID. Please try again.', 'error');
                    isEmployeeID = false;
                    submitBtn.disabled = true;
                }
            });
        }

        // Listen for input changes on the Employee ID field
        $('#employee_id').on('input', function () {
            var employee_id = $(this).val().trim();
            if (employee_id.length > 0) {
                // Pass the correct variable to checkID
                checkID(employee_id);
            } else {
                isEmployeeID = false;
                submitBtn.disabled = true; // Ensure the button remains disabled if the input is empty
            }
        });
    });




</script>

<?php
// Fetch Computer Specifications
if (isset($_GET['cname_id']) && !empty($_GET['cname_id'])) {
    $cname_id = $_GET['cname_id'];

    // Fetch computer record
    $fetchComputer = $conn->prepare("SELECT cname, assets_id FROM computer WHERE cname_id = ?");
    $fetchComputer->bind_param("s", $cname_id);
    $fetchComputer->execute();
    $result = $fetchComputer->get_result();
    $computerData = $result->fetch_assoc();

    $assetsResult = false;

    if ($computerData) {
        $cname = $computerData['cname']; // Get computer name
        $assets_ids = unserialize($computerData['assets_id']); // Unserialize assets_id

        if (!empty($assets_ids)) {
            // Prepare placeholders for query
            $placeholders = implode(',', array_fill(0, count($assets_ids), '?'));

            // Fetch asset details
            $fetchAssets = $conn->prepare("
                SELECT assets_id, assets, sn, brand, model 
                FROM assets 
                WHERE assets_id IN ($placeholders)
            ");

            // Bind parameters dynamically
            $fetchAssets->bind_param(str_repeat('s', count($assets_ids)), ...$assets_ids);
            $fetchAssets->execute();
            $assetsResult = $fetchAssets->get_result();

            if (!$assetsResult) {
                die("Query failed: " . $conn->error);
            }
            // $rows = $assetsResult->fetch_all(MYSQLI_ASSOC);
        }
        // else {
        //     $rows = [];
        // }
    } else {
        $cname = 'N/A';
        $rows = [];
    }
}
?>
<div class="container py-2">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>SPECIFICATIONS FOR <?= htmlspecialchars($cname) ?></h3>
            <a href="/inventory">
                <button class="btn btn-danger" id="closeModal">X</button>
            </a>
        </div>
        <div class="card-body">
            <table class="table text-uppercase table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Assets ID</th>
                        <th scope="col">SPECIFIED</th>
                        <th scope="col">BRAND</th>
                        <th scope="col">MODEL</th>
                        <th scope="col">S/N</th>
                        <th scope="col" class="text-center">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ensure $assetsResult is valid before looping
                    if ($assetsResult && $assetsResult->num_rows > 0) {
                        // Loop through all the rows
                        while ($specs = $assetsResult->fetch_assoc()) {
                            ?>
                            <tr>
                                <td>
                                    <p class="my-2"><?= htmlspecialchars($specs['assets_id']) ?></p>
                                </td>
                                <td>
                                    <p class="my-2"><?= htmlspecialchars($specs['assets']) ?></p>
                                </td>
                                <td>
                                    <p class="my-2"><?= htmlspecialchars($specs['brand']) ?></p>
                                </td>
                                <td>
                                    <p class="my-2"><?= htmlspecialchars($specs['model']) ?></p>
                                </td>
                                <td>
                                    <p class="my-2"><?= htmlspecialchars($specs['sn']) ?></p>
                                </td>
                                <td class="d-flex align-items-center justify-content-center">
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal<?= htmlspecialchars($specs['assets_id']) ?>">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <!-- Delete Modal for this asset -->
                            <div class="modal fade" id="deleteModal<?= htmlspecialchars($specs['assets_id']) ?>"
                                data-bs-keyboard="true" tabindex="-1"
                                aria-labelledby="deleteModalLabel<?= htmlspecialchars($specs['assets_id']) ?>"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"
                                                id="deleteModalLabel<?= htmlspecialchars($specs['assets_id']) ?>">Confirm
                                                Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to remove <?= htmlspecialchars($specs['assets']) ?>? This
                                            action cannot be undone.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                                            <a href="/remove-parts?assets_id=<?= htmlspecialchars($specs['assets_id']) ?>">
                                                <button type="button" class="btn btn-danger">Delete</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row py-5 border border-black border-2" id="stocks">
    <div class="col-3">
        <h2 class="mb-4">Components</h2>
        <div id="list-example" class="list-group">
            <?php
            $fetchAssets = $conn->query("SELECT DISTINCT assets FROM assets");
            while ($fetchAsset = $fetchAssets->fetch_assoc()):
                ?>
                <a class="list-group-item list-group-item-action asset-link border border-black"
                    data-asset="<?= $fetchAsset['assets'] ?>" href="#">
                    <?= $fetchAsset['assets'] ?>
                </a>
            <?php endwhile; ?>
        </div>
    </div>
    <div class="col-9">
        <h2 class="mb-4 float-end">
            Dashboard
            <button class="btn btn-dark w-auto" id="inventoryBtn">Count</button>
        </h2>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Serial Number</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="asset-data">
                <!-- Data will be populated here via AJAX -->
            </tbody>
        </table>
        <!-- Pagination -->
        <div class="text-white">
            <nav>
                <ul class="bg-dark rounded p-2 d-flex justify-content-center align-items-center gap-3 border border-2 border-white"
                    id="pagination">
                    <!-- Pagination links will be inserted dynamically by JavaScript -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<div class="overlay hidden" id="overlay"></div>
<div id="inventory" class="bg-body-tertiary w-75 hidden rounded">
    <div class="container p-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">COMPONENTS</th>
                    <th scope="col">COUNT</th>
                    <th scope="col">AVAILABLE</th>
                    <th scope="col">DEFECTIVE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $limit = 10; // Display 10 records per page
                $page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? (int) $_GET['page'] : 1;
                $offset = ($page - 1) * $limit;

                // Query to get the total number of records (counting all assets)
                $totalResult = mysqli_query($conn, "SELECT COUNT(*) as total FROM (SELECT 1 FROM assets GROUP BY assets) as subquery");
                $totalRows = mysqli_fetch_assoc($totalResult)['total'] ?? 0;
                $totalPages = ($totalRows > 0) ? ceil($totalRows / $limit) : 1;

                $maxPagesToShow = 5; // Limit the number of visible pages
                $halfPages = floor($maxPagesToShow / 2);
                $startPage = max(1, $page - $halfPages);
                $endPage = min($totalPages, $startPage + $maxPagesToShow - 1);

                // Adjust if near the start or end
                if ($endPage - $startPage + 1 < $maxPagesToShow) {
                    $startPage = max(1, $endPage - $maxPagesToShow + 1);
                }

                // Adjust page if out of bounds
                if ($page > $totalPages) {
                    $page = $totalPages > 0 ? $totalPages : 1;
                }

                // Query to fetch and group assets by their name, and count the occurrences of each asset
                $assetQuery = "SELECT a.assets, a.assets_id, COUNT(a.assets_id) AS total_assets, SUM(CASE WHEN a.status = 1 THEN 1 ELSE 0 END) AS defective_count
                       FROM assets a
                       GROUP BY a.assets
                       LIMIT $limit OFFSET $offset
                    ";

                $result = mysqli_query($conn, $assetQuery);
                if (!$result) {
                    die("Error fetching assets: " . mysqli_error($conn));
                }

                // Fetch and unserialize assets_ids from computer table
                $computerQuery = "SELECT assets_id FROM computer WHERE cname_id IS NOT NULL";
                $computerResult = mysqli_query($conn, $computerQuery);
                if (!$computerResult) {
                    die("Error fetching computer data: " . mysqli_error($conn));
                }

                $allocatedAssets = [];
                while ($row = mysqli_fetch_assoc($computerResult)) {
                    // Unserialize the assets_id if it's stored serialized
                    $assetsIdArray = unserialize($row['assets_id']);
                    $allocatedAssets = array_merge($allocatedAssets, $assetsIdArray); // Merge into the allocated array
                }

                // Generate table rows for assets
                while ($row = mysqli_fetch_assoc($result)) {
                    $assetName = $row['assets'];
                    $totalAssetsCount = $row['total_assets']; // Total number of this asset type
                    $defectiveCount = $row['defective_count']; // Correct defective count
                
                    // Count the number of allocated assets for this asset type
                    $allocatedCount = 0;
                    foreach ($allocatedAssets as $allocatedAssetId) {
                        // Extract just the asset type (e.g., "CPU" from "CPU_987-654-321")
                        $allocatedAssetType = explode('_', $allocatedAssetId)[0];
                        $currentAssetType = explode('_', $assetName)[0];

                        if ($allocatedAssetType == $currentAssetType) {
                            $allocatedCount++;
                        }
                    }

                    // Calculate in_stock (total - allocated - defective)
                    $inStock = $totalAssetsCount - $allocatedCount - $defectiveCount;

                    // Output the row
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($assetName, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($totalAssetsCount, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($inStock, ENT_QUOTES, 'UTF-8') ?></td> <!-- Available parts -->
                        <td><?= htmlspecialchars($defectiveCount, ENT_QUOTES, 'UTF-8') ?></td> <!-- Defective parts -->
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <!-- page Links -->
        <div class="text-white">
            <nav>
                <ul class="bg-dark rounded p-2 d-flex justify-content-center align-items-center gap-3 border border-2 border-white"
                    id="page">
                    <!-- First and Previous -->
                    <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=1" title="First">&laquo;&laquo;</a>
                    </li>
                    <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>" title="Previous">&laquo;</a>
                    </li>

                    <!-- Ellipsis before first displayed page -->
                    <?php if ($startPage > 1): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>

                    <!-- Page Numbers -->
                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Ellipsis after last displayed page -->
                    <?php if ($endPage < $totalPages): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>

                    <!-- Next and Last -->
                    <li class="page-item <?= ($page == $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>" title="Next">&raquo;</a>
                    </li>
                    <li class="page-item <?= ($page == $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $totalPages ?>" title="Last">&raquo;&raquo;</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        let currentPage = 1;
        let currentAsset = '';

        // Function to fetch assets via AJAX
        function fetchAssets(asset, page = 1) {
            currentAsset = asset;
            currentPage = page;

            $.ajax({
                method: 'POST',
                url: 'server/jquery/fetch_assets_count.php',
                data: {
                    asset: asset,
                    page: page
                },
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        var html = '';

                        if (data.data.length > 0) {
                            data.data.forEach(function (item) {
                                html += "<tr>";
                                html += "<td>" + item.brand + "</td>";
                                html += "<td>" + item.model + "</td>";
                                html += "<td>" + item.sn + "</td>";

                                var statusText = "";

                                if (item.status === "Defective") {
                                    statusText = "<span class='badge bg-danger'>Defective</span>";
                                } else if (item.status === "Installed") {
                                    statusText = "Installed <a href='/specs?cname_id=" + item.installed_to + "'><span class='badge bg-success'>to " + item.installed_to + "</span></a>";
                                } else {
                                    statusText = "<span class='badge bg-primary'>Available</span>";
                                }

                                html += "<td>" + statusText + "</td>";
                                html += "</tr>";
                            });

                            $('#asset-data').html(html);
                            generatePagination(data.totalPages);
                        } else {
                            $('#asset-data').html("<tr><td colspan='4'>No assets found</td></tr>");
                        }
                    } catch (e) {
                        console.error("Error parsing JSON response", e);
                        $('#asset-data').html("<tr><td colspan='4'>An error occurred while fetching data.</td></tr>");
                    }
                },
                error: function () {
                    $('#asset-data').html("<tr><td colspan='4'>An error occurred while fetching data.</td></tr>");
                }
            });
        }


        // Function to generate pagination dynamically
        function generatePagination(totalPages) {
            var paginationHtml = '';

            // Add "First" and "Previous" links
            paginationHtml += "<li class='page-item " + (currentPage === 1 ? 'disabled' : '') + "'>";
            paginationHtml += "<a class='page-link' href='#' data-page='1' title='First'><span aria-hidden='true'>&laquo;&laquo;</span></a></li>";

            paginationHtml += "<li class='page-item " + (currentPage === 1 ? 'disabled' : '') + "'>";
            paginationHtml += "<a class='page-link' href='#' data-page='" + (currentPage - 1) + "' title='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";

            // Define the range of pages to display dynamically
            var pageLimit = 4; // Number of pages to display before and after the current page
            var startPage = Math.max(1, currentPage - Math.floor(pageLimit / 2));
            var endPage = Math.min(totalPages, currentPage + Math.floor(pageLimit / 2));

            // Adjust to ensure we show at least a few pages before and after
            if (endPage - startPage < pageLimit) {
                if (startPage === 1) {
                    endPage = Math.min(totalPages, startPage + pageLimit - 1);
                } else if (endPage === totalPages) {
                    startPage = Math.max(1, endPage - pageLimit + 1);
                }
            }

            // Add page number links with ellipsis for skipped pages
            for (var i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= startPage && i <= endPage)) {
                    var activeClass = (i === currentPage) ? 'active' : '';
                    paginationHtml += "<li class='page-item " + activeClass + "'><a class='page-link' href='#' data-page='" + i + "'>" + i + "</a></li>";
                } else if (i === startPage - 1 || i === endPage + 1) {
                    paginationHtml += "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                }
            }

            // Add "Next" and "Last" links
            paginationHtml += "<li class='page-item " + (currentPage === totalPages ? 'disabled' : '') + "'>";
            paginationHtml += "<a class='page-link' href='#' data-page='" + (currentPage + 1) + "' title='Next'><span aria-hidden='true'>&raquo;</span></a></li>";

            paginationHtml += "<li class='page-item " + (currentPage === totalPages ? 'disabled' : '') + "'>";
            paginationHtml += "<a class='page-link' href='#' data-page='" + totalPages + "' title='Last'><span aria-hidden='true'>&raquo;&raquo;</span></a></li>";

            // Inject the pagination HTML
            $('#pagination').html(paginationHtml);
        }

        // Handle asset link click
        $(document).on('click', '.asset-link', function (e) {
            e.preventDefault();

            // Remove active class from all links
            $('.asset-link').removeClass('active');

            // Add active class to clicked link
            $(this).addClass('active');

            // Fetch assets for selected component
            var asset = $(this).data('asset');
            fetchAssets(asset, 1);  // Fetch assets for page 1
        });

        // Handle pagination link click
        $(document).on('click', '#pagination .page-link', function (e) {
            e.preventDefault();
            var page = $(this).data('page');
            fetchAssets(currentAsset, page);  // Fetch assets for the selected page
        });

        // Automatically select and load the first asset on page load
        var firstAssetLink = $('.asset-link').first();
        if (firstAssetLink.length) {
            firstAssetLink.addClass('active');
            fetchAssets(firstAssetLink.data('asset'), 1);  // Load the first asset
        }
    });

</script>

<?php
// Ensure you have established your database connection in $conn

$resultNotFound = '';

if (isset($_GET['employee_id']) && !empty($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];

    // Pagination variables
    $limit = 10;
    $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
    $offset = ($page - 1) * $limit;

    // COUNT QUERY:
    // We join the three tables and count distinct history records by concatenating the three IDs.

    $countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM computer_history WHERE employee_id = ?");
    $countStmt->bind_param("s", $employee_id);
    $countStmt->execute();
    $countResult = $countStmt->get_result()->fetch_assoc();
    $totalRecords = $countResult['total'];
    $totalPages = ceil($totalRecords / $limit);

    // Ensure that the page number is within the valid range.
    $page = max(1, min($page, $totalPages));

    // MAIN QUERY:
    // Instead of using a CASE with subqueries (which may return multiple rows),
    // we join the computer table using COALESCE on the three possible cname_id fields.
    // This way we select the computer name directly.
    $stmt = $conn->prepare("SELECT DISTINCT ch.allocation_id, ch.transfer_id, ch.return_id, c.cname
                                    FROM computer_history ch
                                    LEFT JOIN allocation a ON ch.allocation_id = a.allocation_id
                                    LEFT JOIN transferred t ON ch.transfer_id = t.transfer_id
                                    LEFT JOIN returned r ON ch.return_id = r.return_id
                                    JOIN computer c ON c.cname_id = COALESCE(a.cname_id, t.cname_id, r.cname_id)
                                    WHERE 
                                        (ch.allocation_id IS NOT NULL AND a.cname_id = ?)
                                        OR (ch.transfer_id IS NOT NULL AND t.cname_id = ?)
                                        OR (ch.return_id IS NOT NULL AND r.cname_id = ?)
                                    ORDER BY
                                        GREATEST(
                                            IFNULL(a.created_at, '0000-00-00 00:00:00'),
                                            IFNULL(t.created_at, '0000-00-00 00:00:00'),
                                            IFNULL(r.created_at, '0000-00-00 00:00:00')
                                        ) DESC
                                    LIMIT ?, ?
                                ");

    // Bind parameters: three for cname_id and two integers for offset and limit.
    $stmt->bind_param("sssii", $cname_id, $cname_id, $cname_id, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $resultNotFound = 'Data Not Found';
    }
}
?>
<div class="container py-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>History</h3>
            <a href="/inventory">
                <button class="btn btn-danger" id="closeModal">X</button>
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Employee</th>
                        <th scope="col">Status</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultNotFound): ?>
                        <tr>
                            <td colspan="4" class="text-center"><?= htmlspecialchars($resultNotFound) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <?php
                            $status = '<span class="badge bg-success">' . "Allocated" . '</span>';
                            $employeeId = null;
                            $time = null;
                            $badge = '';

                            // Check record type and fetch data accordingly.
                            if ($row['allocation_id']) {
                                $stmtAlloc = $conn->prepare("SELECT employee_id, created_at FROM allocation WHERE allocation_id = ?");
                                $stmtAlloc->bind_param("s", $row['allocation_id']);
                                $stmtAlloc->execute();
                                $allocData = $stmtAlloc->get_result()->fetch_assoc();
                                if ($allocData) {
                                    $employeeId = $allocData['employee_id'];
                                    $time = $allocData['created_at'];
                                }
                            } elseif ($row['transfer_id']) {
                                $stmtTrans = $conn->prepare("SELECT t.t_employee_id, t.employee_id, t.created_at, e.fname, e.lname FROM transferred t LEFT JOIN employee e ON e.employee_id = t.employee_id WHERE transfer_id = ?");
                                $stmtTrans->bind_param("s", $row['transfer_id']);
                                $stmtTrans->execute();
                                $transData = $stmtTrans->get_result()->fetch_assoc();
                                if ($transData) {
                                    $employeeName = $transData['fname'] . ' ' . $transData['lname'];
                                    $employeeId = $transData['t_employee_id'];
                                    $time = $transData['created_at'];
                                    $status = '<span class="badge bg-danger ">' . "Transferred from " . htmlspecialchars($employeeName) . '</span>';
                                    // Original employee information
                                    // $badge = '<span class="badge bg-danger ms-2">from ' . htmlspecialchars($transData['employee_id']) . '</span>';
                                }
                            } elseif ($row['return_id']) {
                                $stmtReturn = $conn->prepare("SELECT employee_id, created_at FROM returned WHERE return_id = ?");
                                $stmtReturn->bind_param("s", $row['return_id']);
                                $stmtReturn->execute();
                                $returnData = $stmtReturn->get_result()->fetch_assoc();
                                if ($returnData) {
                                    $employeeId = $returnData['employee_id'];
                                    $time = $returnData['created_at'];
                                    $status = '<span class="badge bg-dark">' . "Returned" . '</span>';
                                }
                            }

                            // Fetch employee details from employee table.
                            $employeeName = "Unknown";
                            $employeeID = "";
                            if ($employeeId) {
                                $stmtEmp = $conn->prepare("SELECT fname, lname, employee_id FROM employee WHERE employee_id = ?");
                                $stmtEmp->bind_param("s", $employeeId);
                                $stmtEmp->execute();
                                $empData = $stmtEmp->get_result()->fetch_assoc();
                                if ($empData) {
                                    $employeeName = $empData['fname'] . ' ' . $empData['lname'];
                                    $employeeID = $empData['employee_id'];
                                }
                            }
                            ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($employeeName) ?>
                                    <?php if ($employeeID): ?>
                                        <span class="badge bg-warning text-dark ms-2"><?= htmlspecialchars($employeeID) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= $status ?>
                                    <?= $badge ?>
                                </td>
                                <td><?= htmlspecialchars($time) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination Navigation -->

            <div class="text-white">
                <?php if ($resultNotFound): ?>
                    <!-- No data found, don't show pagination -->
                <?php else: ?>
                    <nav>
                        <ul class="bg-dark rounded p-2 d-flex justify-content-center align-items-center gap-3 border border-2 border-white"
                            id="pagination">
                            <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?cname_id=<?= htmlspecialchars($cname_id) ?>&page=1"
                                    title="First">
                                    <span aria-hidden="true">&laquo;&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?cname_id=<?= htmlspecialchars($cname_id) ?>&page=<?= $page - 1 ?>"
                                    title="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="?cname_id=<?= htmlspecialchars($cname_id) ?>&page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($page == $totalPages) ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?cname_id=<?= htmlspecialchars($cname_id) ?>&page=<?= $page + 1 ?>" title="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            <li class="page-item <?= ($page == $totalPages) ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?cname_id=<?= htmlspecialchars($cname_id) ?>&page=<?= $totalPages ?>"
                                    title="Last">
                                    <span aria-hidden="true">&raquo;&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>



<div class="container d-flex flex-column align-items-center" id="register">
    <div class="shadow-lg bg-white p-4 rounded-lg d-flex flex-column align-items-center">
        <h1 class="mb-5">REGISTER</h1>
        <form action="/register-admin" method="post" autocomplete="off" id="registerForm">
            <div class="row d-flex my-3">
                <div class="col-6 form-floating">
                    <input type="text" id="fname" name="fname" placeholder="First Name" class="form-control" required>
                    <label for="fname">First Name</label>
                </div>
                <div class="col-6 form-floating">
                    <input type="text" id="lname" name="lname" placeholder="Last Name" class="form-control" required>
                    <label for="lname">Last Name</label>
                </div>
            </div>
            <div class="row d-flex flex-column my-3 gap-3">
                <div class="col form-floating">
                    <input type="number" id="contact" name="contact" placeholder="Contact Number"
                        class="form-control w-100" required pattern="\d{11}" maxlength="11"
                        oninput="validateContact(this)">
                    <label for="contact">Contact Number</label>
                </div>
                <div class="col form-floating">
                    <input type="email" id="email" name="email" placeholder="Email Address" class="form-control"
                        required>
                    <label for="email">Email Address</label>
                </div>
                <div class="col form-floating">
                    <input type="text" id="username" name="username" placeholder="Username" class="form-control"
                        required>
                    <label for="username">Username</label>
                </div>
                <div class="col form-floating">
                    <input type="password" id="password" name="password" placeholder="Password" class="form-control"
                        required>
                    <label for="password">Password</label>
                </div>
                <div class="col form-floating">
                    <input type="password" id="cpassword" name="cpassword" placeholder="Confirm Password"
                        class="form-control" required>
                    <label for="cpassword">Confirm Password</label>
                </div>
            </div>
            <div class="form-buttons">
                <button type="submit" class="btn btn-dark" name="adminRegister" id="registerBtn">Submit</button>
                <button type="button" class="btn btn-danger" onclick="parent.location.href=''">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Toast Notification -->
<div class="position-fixed top-0 end-0 px-3" style="z-index: 1050">
    <div id="toastAlert" class="toast align-items-center text-white bg-danger border-0" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">
                <!-- Message will be inserted dynamically -->
            </div>
            <!-- <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> -->
        </div>
    </div>
</div>


<script>
    function validateContact(input) {
        if (input.value.length > 11) {
            input.value = input.value.slice(0, 11);
        }
    }
    // Function to show toast notifications
    function showToast(message, status) {
        // Define colors based on status
        const bgColor = status === 'success' ? 'warning' : 'danger';
        const textColor = bgColor === 'danger' ? 'text-white' : '';

        // Create the toast element
        const toast = document.createElement('div');
        toast.className = `toast show bg-${bgColor} ${textColor}`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
        <div class="toast-body justify-content-between d-flex">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
    `;

        // Create the toast container if it doesn't exist
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }

        // Append the toast to the container
        toastContainer.appendChild(toast);

        // Automatically remove the toast after 3 seconds
        setTimeout(() => toast.remove(), 3000);
    }
    $(document).ready(function () {
        var isUsernameValid = false; // Flag to track if username is valid
        const registerBtn = document.getElementById('registerBtn');

        registerBtn.disabled = true; // Correct way to disable the button initially

        // Function to check if username exists
        function checkUsernameExists(username) {
            $.ajax({
                method: 'POST',
                url: 'server/jquery/check_username.php', // Ensure this file handles the check
                data: { username: username },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.exists) {
                        showToast('Username already exists. Please choose a different username.', 'error');
                        isUsernameValid = false;
                    } else {
                        isUsernameValid = true;
                    }
                    registerBtn.disabled = !isUsernameValid; // Enable or disable the button based on validation
                },
                error: function () {
                    showToast('Error checking username. Please try again.', 'error');
                    isUsernameValid = false;
                    registerBtn.disabled = true;
                }
            });
        }

        // Event listener for username input field
        $('#username').on('input', function () {
            var username = $(this).val().trim();
            if (username.length > 0) {
                checkUsernameExists(username);
            } else {
                isUsernameValid = false;
                registerBtn.disabled = true; // Ensure the button is disabled if input is empty
            }
        });
    });


</script>


<?php
$notFound = '';
$resultNotFound = '';
$rows = [];
$assetsDetails = [];

if (isset($_GET['employee_id'])) {
    $employee_id = trim($_GET['employee_id']); // Trim to remove spaces

    // Validate input
    if (empty($employee_id)) {
        echo json_encode(['error' => 'Employee ID is required']);
        exit();
    }
    // Fetch computer history and cname using cname_id
    $stmt = $conn->prepare("SELECT a.*, c.cname, c.assets_id 
                            FROM allocation a
                            LEFT JOIN computer c ON a.cname_id = c.cname_id
                            WHERE a.employee_id = ? AND a.status = 1 ORDER BY created_at DESC");
    $stmt->bind_param("s", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $computerAssets = [];
    while ($row = $result->fetch_assoc()) {

        $rows[] = $row;
        $computerAssets[] = $row['assets_id']; // Collect serialized assets_id
    }

    if (empty($rows)) {
        $resultNotFound = 'No Data Found';
    }

    // Fetch employee details
    $fetchEmployeeStmt = $conn->prepare("SELECT * FROM employee WHERE employee_id = ?");
    $fetchEmployeeStmt->bind_param("s", $employee_id);
    $fetchEmployeeStmt->execute();
    $show = $fetchEmployeeStmt->get_result()->fetch_assoc() ?? null;


    if (!$show) {
        $notFound = 'Employee Not Found';
    }

    // Process assets_id: unserialize and get asset details
    foreach ($computerAssets as $index => $serializedAssets) {
        if (!empty($serializedAssets)) {
            $assetIds = unserialize($serializedAssets); // Unserialize to get individual asset IDs
            if (is_array($assetIds) && count($assetIds) > 0) {
                // Convert array to a string of placeholders for SQL query
                $placeholders = implode(',', array_fill(0, count($assetIds), '?'));

                // Prepare the query to fetch asset details
                $query = "SELECT * FROM assets WHERE assets_id IN ($placeholders)";
                $stmt = $conn->prepare($query);

                if ($stmt) {
                    $stmt->bind_param(str_repeat('s', count($assetIds)), ...$assetIds);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($asset = $result->fetch_assoc()) {
                        $assetsDetails[] = $asset;
                    }
                }
            }
        }
    }
}
?>
<style>
    /* Hide everything except the terms-conditions section when printing */
    @media print {
        body * {
            visibility: hidden;
        }

        .not {
            visibility: hidden !important;
            display: none;
        }

        .card-footer {
            visibility: hidden !important;
            display: none;
        }

        #terms-conditions,
        #terms-conditions * {
            visibility: visible;
        }

        #terms-conditions {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 20px;
            /* Adjust padding for print */
            box-shadow: none;
            font-size: 12px;
            /* Remove shadows for print */
        }

        /* Optional: Adjust table borders for better print visibility */
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000 !important;
        }

        /* Hide buttons and other unnecessary elements */
        #printBtn,
        #cancelPrint {
            display: none !important;
        }
    }
</style>
<div class="container py-5">
    <div class="card" id="terms-conditions">
        <div class="card-header p-3">
            <div class="d-flex flex-column justify-content-center align-items-center text-center">
                <h2 class="text-uppercase fw-bold">HPL Gamedesign Corporation</h2>
                <p>82 Road 3 Project 6 Quezon City, Metro Manila, 1100
                    <br />
                    admin@hplgamedesign.com ᐧ 09202773422 ᐧ (02) 8 808 6920
                </p>
            </div>
        </div>
        <div class="card-body p-3">
            <h2 class="text-center mb-5">EMPLOYEE EQUIPMENT AGREEMENT</h2>
            <section>
                <p class="text-justify text-wrap" style="line-height: 2em;">
                    I, <span class="fw-bold fs-5 fst-italic" style="text-decoration: underline;">
                        &emsp;<?= isset($show) ? htmlspecialchars($show['fname'] . ' ' . $show['lname']) : '' ?>&emsp;
                    </span>, hereby acknowledge and agree to the following terms and conditions regarding the equipment
                    supplied to me by HPL Gamedesign Corporation, referred to as the Company:
                    <br><br>
                    Equipment Care and Responsibility: I agree to take proper care of all equipment supplied to me by
                    the
                    Company. This includes, but is not limited to, laptops, cell phones, monitors, software licenses, or
                    any other company-provided equipment deemed necessary by Company management for the performance of
                    my job duties. Proper care entails safeguarding the equipment from damage and ensuring its
                    maintenance in good working condition.
                    <br><br>
                    Equipment Return Policy: Upon termination of my employment, whether by resignation or termination, I
                    understand and agree to return all Company-supplied equipment within the specified time-frames:
                    <br><br>
                    ● All employees, including those working remotely or on temporary work-from-home arrangements,
                    are
                    required to promptly return all issued equipment when instructed by the Company within 72hrs.
                    <br><br>
                    ● Following resignation, all issued equipment must be returned within 24 hours.
                    <br><br>
                    Condition of Returned Equipment: I acknowledge that all equipment must be returned in proper
                    working
                    order. Any damage to or malfunction of the equipment beyond normal wear and tear may result in
                    financial responsibility on my part.
                    <br><br>
                    Business Use Only: I understand and agree that any equipment provided by the Company is to be
                    used
                    solely for business purposes and shall not be used for personal activities or non-work-related
                    endeavors.
                    <br><br>
                    Consequences of Non-Compliance: Failure to return any equipment supplied by the Company after
                    the
                    termination of my employment may be considered theft and may result in criminal prosecution by the
                    Company. Additionally, I acknowledge that failure to comply with the terms of this agreement may
                    lead to disciplinary action, including potential legal consequences.
                    <br><br>
                    Termination Conditions: The terms of this agreement apply regardless of the circumstances of
                    termination, including resignation, termination for cause, or termination without cause.
                </p>
            </section>

            <table class="table table-bordered my-5">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Computer Name</th>
                        <th scope="col">Assets</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Model</th>
                        <th scope="col">S/N</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultNotFound): ?>
                        <tr class="text-center">
                            <td colspan="6"><?= $resultNotFound ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($assetsDetails as $asset): ?>
                            <tr>
                                <td><?= htmlspecialchars($rows[0]['cname'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($asset['assets']) ?></td>
                                <td><?= htmlspecialchars($asset['brand']) ?></td>
                                <td><?= htmlspecialchars($asset['model']) ?></td>
                                <td><?= htmlspecialchars($asset['sn']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <section class="text-center">
                <p>By signing below, I acknowledge that I have reviewed each point of this agreement and agree to all
                    the conditions above. </p>
            </section>
            <div>
                <table class="table table-bordered">
                    <thead>
                        <tr class="fw-bold">
                            <td>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>Date Released:</div>
                                    <?php
                                    if (isset($rows[0]['created_at'])) {
                                        // Convert the created_at to a DateTime object and format it
                                        $date = new DateTime($rows[0]['created_at']);
                                        echo htmlspecialchars($date->format('F d, Y'));
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </div>
                            </td>
                            <td class="d-flex align-items-center gap-5 justify-content-end" id="custody-radio">
                                <?php if (isset($show['status']) && $show['status'] == 1): ?>
                                    <input type="radio" name="e_status" id="e-new-hire" checked disabled>
                                    <label for="e-new-hire">New Hire</label>
                                    <input type="radio" name="e_status" id="e-wfh" disabled>
                                    <label for="e-wfh">WFH</label>
                                    <input type="radio" name="e_status" id="e-temp" disabled>
                                    <label for="e-temp">TEMP WFH</label>
                                <?php elseif (isset($show['status']) && $show['status'] == 2): ?>
                                    <input type="radio" name="e_status" id="e-new-hire" disabled>
                                    <label for="e-new-hire">New Hire</label>
                                    <input type="radio" name="e_status" id="e-wfh" checked disabled>
                                    <label for="e-wfh">WFH</label>
                                    <input type="radio" name="e_status" id="e-temp" disabled>
                                    <label for="e-temp">TEMP WFH</label>
                                <?php else: ?>
                                    <input type="radio" name="e_status" id="e-new-hire" disabled>
                                    <label for="e-new-hire">New Hire</label>
                                    <input type="radio" name="e_status" id="e-wfh" disabled>
                                    <label for="e-wfh">WFH</label>
                                    <input type="radio" name="e_status" id="e-temp" checked disabled>
                                    <label for="e-temp">TEMP WFH</label>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="fw-bold">
                            <td colspan="2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>Department:</div>
                                    <?= htmlspecialchars($show['dept'] ? $show['dept'] : 'UNDEFINED') ?>
                                </div>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">
                                <form action="/esignature?employee_id=<?= $employee_id ?>" method="post"
                                    id="e-signature" enctype="multipart/form-data">
                                    <div class="input-group">
                                        <?php
                                        // Fetch the image URL from the database
                                        $fetchImage = $conn->query("SELECT signature FROM employee WHERE employee_id = '$employee_id'");
                                        if ($result = $fetchImage->fetch_assoc()) {
                                            $imageUrl = $result['signature'];
                                        } else {
                                            $imageUrl = null; // Default to null if no image is found
                                        }
                                        ?>

                                        <!-- Show file input and upload button ONLY if no image exists -->
                                        <?php if (empty($imageUrl)): ?>
                                            <input type="file" class="form-control" name="e-signature" id="e-signature"
                                                aria-describedby="e-signature" aria-label="Upload"
                                                accept=".jpg, .jpeg, .png" required>
                                            <button class="btn btn-dark h-50" type="submit" id="e-signature"
                                                name="signature">Upload</button>
                                        <?php endif; ?>

                                        <!-- Display the uploaded image if it exists -->
                                        <?php if (!empty($imageUrl)): ?>
                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                <!-- Check if the image path is from the server directory or Cloudinary -->
                                                <?php if (strpos($imageUrl, '/server/signature/') === 0): ?>
                                                    <!-- If it's a local path, display from the server directory -->
                                                    <img src="<?= $imageUrl ?>" alt="Employee Signature"
                                                        class="img-fluid w-25 mt-5">
                                                <?php else: ?>
                                                    <!-- If it's a Cloudinary URL, display from Cloudinary -->
                                                    <img src="<?= $imageUrl ?>" alt="Employee Signature"
                                                        class="img-fluid w-25 mt-5">
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </form>
                                <h3 class="fst-italic fs-5 mt-3 fw-bold">
                                    <u><?= isset($show) ? htmlspecialchars($show['fname'] . ' ' . $show['lname']) : 'N/A' ?></u>
                                </h3>
                            </td>
                            <td class="text-center">
                                <?php
                                $fetchAdmin = $conn->query("SELECT * FROM users WHERE type = 1");
                                if ($result = $fetchAdmin->fetch_assoc()) {
                                    $username = $result['username'];
                                    if (!empty($result['signature'])) {
                                        $adminURL = $result['signature'];
                                    } else {
                                        $adminURL = null;
                                    }
                                    ?>
                                    <form action="/esignature?username=<?= $username ?>" method="post" id="a-signature"
                                        enctype="multipart/form-data">
                                        <div class="input-group">
                                            <!-- Show file input and upload button ONLY if no image exists -->
                                            <?php if (empty($adminURL)): ?>
                                                <input type="file" class="form-control" name="a-signature" id="a-signature"
                                                    aria-describedby="a-signature" aria-label="Upload"
                                                    accept=".jpg, .jpeg, .png" required>
                                                <button class="btn btn-dark h-50" type="submit" id="a-signature"
                                                    name="admin-signature">Upload</button>
                                            <?php endif; ?>

                                            <!-- Display the uploaded image if it exists -->
                                            <?php if (!empty($adminURL)): ?>
                                                <div class="d-flex flex-column justify-content-center align-items-center">
                                                    <!-- Check if the image path is from the server directory or Cloudinary -->
                                                    <?php if (strpos($adminURL, '/server/signature/') === 0): ?>
                                                        <!-- If it's a local path, display from the server directory -->
                                                        <img src="<?= $adminURL ?>" alt="Admin Signature"
                                                            class="img-fluid w-25 mt-5">
                                                    <?php else: ?>
                                                        <!-- If it's a Cloudinary URL, display from Cloudinary -->
                                                        <img src="<?= $adminURL ?>" alt="Admin Signature"
                                                            class="img-fluid w-25 mt-5">
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                    </form>
                                    <h3 class="fst-italic fs-5 mt-3 fw-bold">
                                        <u><?= htmlspecialchars($result['fname'] . ' ' . $result['lname']) ?></u>
                                    </h3>
                                    <?php
                                } ?>
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td>
                                EMPLOYEE NAME & SIGNATURE
                            </td>
                            <td>
                                IT PERSONNEL - NAME & SIGNATURE
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer p-3 d-flex justify-content-center align-items-center gap-2">
            <p class="not">To ensure the document prints correctly, please enable <strong>Headers and Footers</strong>
                and <strong>Background Graphics</strong> in the print window under <strong>More Settings</strong>.</p>

            <button type="button" class="btn btn-dark w-25" id="printBtn">Print</button>
            <button type="button" class="btn btn-danger w-25" id="cancelPrint">Cancel</button>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const printBtn = document.getElementById('printBtn');
        const cancelBtn = document.getElementById('cancelPrint');

        printBtn.onclick = () => {
            // Force a reflow to ensure styles are applied
            document.body.offsetHeight;

            // Trigger the print dialog
            window.print();
        };

        cancelBtn.onclick = () => {
            window.location = "/employee";
        };
    });
</script>
<div class="container d-flex flex-column align-items-center" id="login">
    <div class="shadow-lg bg-white p-4 rounded-lg d-flex flex-column align-items-center">
        <h1 class="mb-5">LOGIN</h1>
        <form action="server/login.php" method="post" autocomplete="off" id="loginForm">
            <div class="row d-flex flex-column gap-4">
                <div class="col-md-6 col-sm-12 form-floating">
                    <input type="text" id="username" name="username" class="form-control" placeholder="Username"
                        required>
                    <label for="username">Username</label>
                </div>
                <div class="col-md-6 col-sm-12 form-floating">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password"
                        required>
                    <label for="password">Password</label>
                </div>
            </div>
            <div class="form-buttons">
                <button type="submit" class="btn btn-dark" name="login">Submit</button>
                <button type="reset" class="btn btn-danger">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div class="py-5">
    <table class="table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Name</th>
                <th>Email Address</th>
                <th>Contact Number</th>
                <th>Created Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $fetchData = $conn->query("SELECT * FROM users");
            while ($row = $fetchData->fetch_assoc()):
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']); ?></td>
                    <td><?= htmlspecialchars($row['fname']) . ' ' . htmlspecialchars($row['lname']) ?></td>
                    <td class="email" data-email="<?= htmlspecialchars($row['email']); ?>">
                        <?= htmlspecialchars($row['email']); ?>
                    </td>
                    <td class="contact" data-contact="<?= htmlspecialchars($row['contact']); ?>">
                        <?= htmlspecialchars($row['contact']); ?>
                    </td>

                    <td><?= htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <?php if ((int) $row['status'] === 1): ?>
                            <button type="button" class="btn btn-warning" disabled>
                                <i class="fa-solid fa-thumbs-up"></i>
                            </button>
                        <?php elseif ($row['status'] === null || (int) $row['status'] === 0): ?>
                            <a href="/approve?username=<?= urlencode($row['username']) ?>">
                                <button type="button" class="btn btn-warning">
                                    <i class="fa-solid fa-thumbs-up"></i>
                                </button>
                            </a>
                        <?php endif; ?>

                        <!-- Delete Button (Triggers Modal) -->
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteModal<?= htmlspecialchars($row['username']) ?>">
                            <i class="fa-solid fa-trash"></i>
                        </button>

                        <!-- Delete Modal for this user -->
                        <div class="modal fade" id="deleteModal<?= htmlspecialchars($row['username']) ?>"
                            data-bs-keyboard="true" tabindex="-1"
                            aria-labelledby="deleteModalLabel<?= htmlspecialchars($row['username']) ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"
                                            id="deleteModalLabel<?= htmlspecialchars($row['username']) ?>">
                                            Confirm Deletion</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to remove
                                        <strong><?= htmlspecialchars($row['username']) ?></strong>? This action
                                        cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                                        <a href="/delete?username=<?= htmlspecialchars($row['username']) ?>">
                                            <button type="button" class="btn btn-danger">
                                                Confirm
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
