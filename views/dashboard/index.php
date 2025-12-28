<?php
// ✅ START SESSION FIRST (MOST IMPORTANT)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Page title
$pageTitle = 'Dashboard - Blood Bank';

// Database
require_once __DIR__ . '/../../config/database.php';

// Header (HTML + CSS)
require_once __DIR__ . '/../layout/header.php';

// Session safety
$userName = $_SESSION['user_name'] ?? 'User';
$userRole = $_SESSION['user_role'] ?? 'donor';

/* =========================
   ADMIN / STAFF DASHBOARD DATA
========================= */
if ($userRole !== 'donor') {

    // Total Approved Donors
    $totalDonors = $pdo->query(
        "SELECT COUNT(*) FROM donors WHERE status = 'approved'"
    )->fetchColumn();

    // Total Blood Units
    $totalUnits = $pdo->query(
        "SELECT COALESCE(SUM(units),0) FROM blood_inventory"
    )->fetchColumn();

    // Pending Requests
    $pendingRequests = $pdo->query(
        "SELECT COUNT(*) FROM requests WHERE status = 'pending'"
    )->fetchColumn();

    // Pending Donors
    $pendingDonorsCount = $pdo->query(
        "SELECT COUNT(*) FROM donors WHERE status = 'pending'"
    )->fetchColumn();

    // Recent Requests
    $recentRequests = $pdo->query(
        "SELECT patient_name, blood_group, units, hospital_name, status
         FROM requests
         ORDER BY request_date DESC
         LIMIT 5"
    )->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="dashboard-container">

    <!-- Sidebar -->
    <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Header -->
        <header style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
            <div>
                <h2 class="text-gradient">Dashboard</h2>
                <p style="color:var(--text-muted)">
                    Welcome back, <?= htmlspecialchars($userName) ?>!
                </p>
            </div>

            <span class="status-badge status-approved">
                <?= ucfirst($userRole) ?>
            </span>
        </header>

        <!-- ================= ADMIN / STAFF ================= -->
        <?php if ($userRole !== 'donor'): ?>

        <!-- STATS -->
        <div class="stats-grid">

            <a href="/bloodbank/donors" class="glass-panel stat-card">
                <div>
                    <div class="stat-value"><?= $pendingDonorsCount ?></div>
                    <div class="stat-label">Pending Approvals</div>
                </div>
                <div class="stat-icon" style="color:#f59e0b;">⏳</div>
            </a>

            <a href="/bloodbank/donors" class="glass-panel stat-card">
                <div>
                    <div class="stat-value"><?= $totalDonors ?></div>
                    <div class="stat-label">Total Donors</div>
                </div>
                <div class="stat-icon">🧑‍🦱</div>
            </a>

            <a href="/bloodbank/inventory" class="glass-panel stat-card">
                <div>
                    <div class="stat-value"><?= $totalUnits ?></div>
                    <div class="stat-label">Blood Units</div>
                </div>
                <div class="stat-icon">🩸</div>
            </a>

            <a href="/bloodbank/requests" class="glass-panel stat-card">
                <div>
                    <div class="stat-value"><?= $pendingRequests ?></div>
                    <div class="stat-label">Pending Requests</div>
                </div>
                <div class="stat-icon">🚑</div>
            </a>

        </div>

        <!-- RECENT REQUESTS -->
        <h3 class="mb-3">Recent Requests</h3>
        <div class="glass-panel" style="padding:0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Group</th>
                            <th>Units</th>
                            <th>Hospital</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php if (!empty($recentRequests)): ?>
                        <?php foreach ($recentRequests as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['patient_name']) ?></td>
                            <td><strong><?= $r['blood_group'] ?></strong></td>
                            <td><?= $r['units'] ?></td>
                            <td><?= htmlspecialchars($r['hospital_name']) ?></td>
                            <td>
                                <span class="status-badge status-<?= $r['status'] ?>">
                                    <?= ucfirst($r['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No recent requests
                            </td>
                        </tr>
                    <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= DONOR ================= -->
        <?php else: ?>

        <div class="glass-panel" style="padding:2rem;text-align:center;">
            <h3>Thank you for being a hero 🩸</h3>
            <p style="color:var(--text-muted);margin-top:1rem;">
                You can schedule your next blood donation here.
            </p>

            <a href="/bloodbank/become-donor"
               class="btn btn-primary mt-3"
               style="max-width:220px;">
                Schedule Donation
            </a>
        </div>

        <?php endif; ?>

    </div>
</div>

<script src="/bloodbank/assets/js/script.js"></script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
