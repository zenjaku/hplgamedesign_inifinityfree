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