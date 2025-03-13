<?php
require_once "server/drop-downs/allocate.php";
require_once "server/drop-downs/transfer.php";
require_once "server/drop-downs/return.php";

$assets = [];
while ($row = mysqli_fetch_assoc($assetsResult)) {
    $assets[] = $row;
}
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
<script src="../js/allocate.js"></script>