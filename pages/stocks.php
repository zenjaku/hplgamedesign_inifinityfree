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

<div class="overlay d-none" id="overlay"></div>
<div id="inventory" class="bg-body-tertiary w-75 d-none rounded">
    <div class="container p-3">
        <table class="table table-bordered">
            <thead class="table-dark">
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

<script src="../js/stocks.js"></script>