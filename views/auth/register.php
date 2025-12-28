<?php
$pageTitle = 'Register - Blood Bank';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="auth-wrapper">
    <div class="glass-panel auth-card" style="max-width: 500px;">
        <div class="text-center mb-4">
            <div class="logo">🩸 BloodBank</div>
            <h2 class="text-gradient">Join the Cause</h2>
            <p style="color: var(--text-muted)">Create a new account</p>
        </div>

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

        <!-- Register Form -->
        <form action="/bloodbank/auth/register" method="POST">

            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input
                    type="text"
                    name="fullname"
                    class="form-control"
                    placeholder="John Doe"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Enter your email"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Create a password"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">I want to register as:</label>
                <select name="role" class="form-control" required>
                    <option value="donor">Donor (I want to donate)</option>
                    <option value="staff">Staff (Hospital / Bank Staff)</option>
                    <!-- Admin should be created manually in DB -->
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Create Account
            </button>

            <div class="text-center mt-3">
                <p style="color: var(--text-muted); font-size: 0.9rem;">
                    Already have an account?
                    <a href="/bloodbank/login" class="text-link">Sign in</a>
                </p>
            </div>

        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
