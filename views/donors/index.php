<?php
$pageTitle = 'Donors List';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="dashboard-container">
    <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

    <div class="main-content">
        <h2 class="text-gradient mb-4">Donors List</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="glass-panel" style="padding:0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Blood Group</th>
                            <th>City</th>
                            <th>Phone</th>
                            <th>Last Donation</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($donors)): ?>
                            <?php foreach ($donors as $donor): ?>
                                <tr>
                                    <td>
                                        <div style="font-weight:600;"><?= htmlspecialchars($donor['fullname']) ?></div>
                                        <div style="font-size:0.85em;color:var(--text-muted)"><?= htmlspecialchars($donor['email']) ?></div>
                                    </td>
                                    <td>
                                        <span class="blood-badge"><?= $donor['blood_group'] ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($donor['city']) ?></td>
                                    <td><?= htmlspecialchars($donor['phone']) ?></td>
                                    <td><?= $donor['last_donation'] ? $donor['last_donation'] : '-' ?></td>
                                    <td>
                                        <span class="status-badge status-<?= $donor['status'] ?>">
                                            <?= ucfirst($donor['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:5px;">
                                            <?php if ($donor['status'] === 'pending'): ?>
                                                <form action="/bloodbank/donors/update" method="POST" style="display:inline;">
                                                    <input type="hidden" name="id" value="<?= $donor['id'] ?>">
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" 
                                                            class="btn" 
                                                            style="padding:.25rem .5rem;width:auto;background:rgba(34,197,94,.2);color:#86efac;border:1px solid rgba(34,197,94,.3);">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form action="/bloodbank/donors/update" method="POST" style="display:inline;">
                                                    <input type="hidden" name="id" value="<?= $donor['id'] ?>">
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" 
                                                            class="btn" 
                                                            style="padding:.25rem .5rem;width:auto;background:rgba(239,68,68,.2);color:#fca5a5;border:1px solid rgba(239,68,68,.3);">
                                                        Reject
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span style="color:var(--text-muted);font-size:0.9rem;">
                                                    <?= ucfirst($donor['status']) ?>
                                                </span>
                                            <?php endif; ?>
                                            
                                            <a href="tel:<?= $donor['phone'] ?>" 
                                               class="btn" 
                                               style="padding:.25rem .5rem;width:auto;background:rgba(255,255,255,0.05);color:var(--text-main);">
                                                📞
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No donors found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
