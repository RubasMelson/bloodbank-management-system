<?php
$pageTitle = 'My Profile - Blood Bank';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="dashboard-container">
<?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

<div class="main-content">
    <h2 class="text-gradient mb-4">My Profile</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="glass-panel" style="max-width:450px;">
        <form action="bloodbank/profile/update" method="POST">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="fullname" class="form-control"
                       value="<?= htmlspecialchars($_SESSION['user_name']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Role</label>
                <input type="text" class="form-control"
                       value="<?= ucfirst($_SESSION['user_role']) ?>" disabled>
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control"
                       placeholder="Leave empty to keep same">
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
