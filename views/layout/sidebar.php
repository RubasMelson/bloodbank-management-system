<?php
$userRole     = $_SESSION['user_role'] ?? 'donor';
$path         = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pendingCount = $pendingCount ?? 0; // SAFE DEFAULT
?>

<div class="sidebar glass-panel">
    <div class="logo mb-4">🩸 BloodBank</div>

    <nav>
        <a href="/bloodbank/dashboard"
           class="nav-link <?= strpos($path, '/dashboard') !== false ? 'active' : '' ?>">
            <span class="nav-icon">📊</span> Dashboard
        </a>

        <?php if ($userRole === 'admin' || $userRole === 'staff'): ?>

            <a href="/bloodbank/donors"
               class="nav-link <?= strpos($path, '/donors') !== false ? 'active' : '' ?>"
               style="display:flex;align-items:center;">
                <span class="nav-icon">🧑‍🦱</span> Donors

                <?php if (($pendingDonorsCount ?? 0) > 0): ?>
                    <span class="pending-badge" style="background:#f59e0b;color:#fff;">
                        <?= $pendingDonorsCount ?>
                    </span>
                <?php endif; ?>
            </a>

            <a href="/bloodbank/inventory"
               class="nav-link <?= strpos($path, '/inventory') !== false ? 'active' : '' ?>">
                <span class="nav-icon">🩸</span> Inventory
            </a>

            <a href="/bloodbank/requests"
               class="nav-link <?= strpos($path, '/requests') !== false ? 'active' : '' ?>"
               style="display:flex;align-items:center;">
                <span class="nav-icon">🚑</span> Requests

                <?php if ($pendingCount > 0): ?>
                    <span class="pending-badge">
                        <?= $pendingCount ?>
                    </span>
                <?php endif; ?>
            </a>

        <?php else: ?>

            <a href="/bloodbank/become-donor"
               class="nav-link <?= strpos($path, '/become-donor') !== false ? 'active' : '' ?>">
                <span class="nav-icon">🩸</span> Become a Donor
            </a>

            <a href="/bloodbank/request-blood"
               class="nav-link <?= strpos($path, '/request-blood') !== false ? 'active' : '' ?>">
                <span class="nav-icon">🚑</span> Request Blood
            </a>

        <?php endif; ?>

        <a href="/bloodbank/profile"
           class="nav-link <?= strpos($path, '/profile') !== false ? 'active' : '' ?>">
            <span class="nav-icon">👤</span> Profile
        </a>

        <a href="/bloodbank/logout"
           class="nav-link"
           style="color:#fca5a5;">
            <span class="nav-icon">🚪</span> Logout
        </a>
    </nav>
</div>
