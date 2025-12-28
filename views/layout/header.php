<?php
require_once __DIR__ . '/../../config/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Blood Bank System' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

<!-- TOP HEADER BAR -->
<header style="
    height:60px;
    background:rgba(15,23,42,0.95);
    border-bottom:1px solid rgba(255,255,255,0.1);
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 1.5rem;
    position:sticky;
    top:0;
    z-index:200;
">
    <div style="display:flex;align-items:center;gap:12px;">
        <span style="font-size:1.6rem;">🩸</span>
        <span style="font-weight:700;font-size:1.1rem;color:#ef4444;">
            BloodBank System
        </span>
    </div>

    <div style="display:flex;align-items:center;gap:1rem;">
        <?php if (!empty($_SESSION['user_name']) && !empty($_SESSION['user_role']) && $_SESSION['user_role'] !== 'guest'): ?>
            <span style="color:#94a3b8;font-size:.9rem;">
                <?= htmlspecialchars($_SESSION['user_name']) ?>
            </span>
            <span class="status-badge status-approved">
                <?= ucfirst($_SESSION['user_role']) ?>
            </span>
        <?php endif; ?>
    </div>
</header>
