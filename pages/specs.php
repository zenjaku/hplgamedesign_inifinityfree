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
        }
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