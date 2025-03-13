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