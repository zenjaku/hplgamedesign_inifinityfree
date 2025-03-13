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
                <p>27th Floor, IBM Plaza Building, E. Rodriguez Jr. Avenue, Eastwood, Quezon City, 1110
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
                                            <div class="d-flex flex-column justify-content-center align-items-center w-100">
                                                <img src="<?= $imageUrl ?>" alt="Employee Signature"
                                                    class="img-fluid mt-5 mx-auto"
                                                    style="width: 200px; aspect-ratio: 2/1; object-fit: cover;">
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
                                                <div class="d-flex flex-column justify-content-center align-items-center w-100">
                                                    <img src="<?= $adminURL ?>" alt="Admin Signature"
                                                        class="img-fluid mt-5 mx-auto"
                                                        style="width: 200px; aspect-ratio: 2/1; object-fit: cover;">
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