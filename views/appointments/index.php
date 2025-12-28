<?php
// Page title
$pageTitle = 'Appointments - Blood Bank';

// Header (session + CSS)
require_once __DIR__ . '/../layout/header.php';

// Safety (avoid undefined variable error)
$appointments = $appointments ?? [];
?>

<div class="dashboard-container">

    <!-- Sidebar -->
    <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">

        <h2 class="text-gradient mb-4">Donation Appointments</h2>

        <!-- Error Message -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Success Message -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Appointments Table -->
        <div class="glass-panel" style="padding:0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Donor Name</th>
                            <th>Email</th>
                            <th>Appointment Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($appointments)): ?>
                            <?php foreach ($appointments as $app): ?>
                                <tr>
                                    <td><?= htmlspecialchars($app['fullname']) ?></td>
                                    <td><?= htmlspecialchars($app['email']) ?></td>
                                    <td>
                                        <?= date('d M Y H:i', strtotime($app['appointment_date'])) ?>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?= $app['status'] ?>">
                                            <?= ucfirst($app['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No appointments found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>

    </div>
</div>

<?php
// Footer
require_once __DIR__ . '/../layout/footer.php';
?>
