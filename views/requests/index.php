<?php
$pageTitle = 'Manage Requests - Blood Bank';
require_once __DIR__ . '/../layout/header.php';

// Safety fallback
$requests = $requests ?? [];
?>

<div class="dashboard-container">

    <!-- Sidebar -->
    <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">

        <h2 class="text-gradient mb-4">Blood Requests</h2>

        <!-- Success Message -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="glass-panel" style="padding:0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Patient / Hospital</th>
                            <th>Group (Units)</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($requests)): ?>
                            <?php foreach ($requests as $req): ?>
                                <tr>
                                    <td>
                                        <?= date('M d, Y', strtotime($req['request_date'])) ?>
                                    </td>

                                    <td>
                                        <div><?= htmlspecialchars($req['patient_name']) ?></div>
                                        <small style="color:var(--text-muted);">
                                            <?= htmlspecialchars($req['hospital_name']) ?>
                                        </small>
                                    </td>

                                    <td>
                                        <strong style="color:var(--primary-color);">
                                            <?= htmlspecialchars($req['blood_group']) ?>
                                        </strong>
                                        <span style="color:var(--text-muted);font-size:.9rem;">
                                            (<?= (int)$req['units'] ?> Units)
                                        </span>
                                    </td>

                                    <td><?= htmlspecialchars($req['contact_phone']) ?></td>

                                    <td>
                                        <span class="status-badge status-<?= htmlspecialchars($req['status']) ?>">
                                            <?= ucfirst($req['status']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php if ($req['status'] === 'pending'): ?>

                                            <form action="/bloodbank/requests/update-status"
                                                  method="POST"
                                                  style="display:inline;">
                                                <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                                <input type="hidden" name="status" value="approved">
                                                <button
                                                    type="submit"
                                                    class="btn"
                                                    style="padding:.25rem .5rem;width:auto;background:rgba(34,197,94,.2);color:#86efac;">
                                                    Approve
                                                </button>
                                            </form>

                                            <form action="/bloodbank/requests/update-status"
                                                  method="POST"
                                                  style="display:inline;">
                                                <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                                <input type="hidden" name="status" value="rejected">
                                                <button
                                                    type="submit"
                                                    class="btn"
                                                    style="padding:.25rem .5rem;width:auto;background:rgba(239,68,68,.2);color:#fca5a5;">
                                                    Reject
                                                </button>
                                            </form>

                                        <?php elseif ($req['status'] === 'approved'): ?>

                                            <form action="/bloodbank/requests/update-status"
                                                  method="POST"
                                                  style="display:inline;">
                                                <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                                <input type="hidden" name="status" value="completed">
                                                <button
                                                    type="submit"
                                                    class="btn"
                                                    style="padding:.25rem .5rem;width:auto;background:rgba(56,189,248,.2);color:#7dd3fc;">
                                                    Mark Complete
                                                </button>
                                            </form>

                                        <?php else: ?>
                                            <span style="color:var(--text-muted);">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No blood requests found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="/bloodbank/assets/js/script.js"></script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
