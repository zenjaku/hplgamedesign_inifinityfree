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