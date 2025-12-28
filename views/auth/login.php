<?php
$pageTitle = 'Login - Blood Bank';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="auth-wrapper">
    <div class="glass-panel auth-card">
        <div class="text-center mb-4">
            <div class="logo">🩸 BloodBank</div>
            <h2 class="text-gradient">Welcome Back</h2>
            <p style="color: var(--text-muted)">Sign in to your account</p>
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

        <!-- Login Form -->
        <form action="/bloodbank/auth/login" method="POST">
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
                    placeholder="Enter your password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Sign In
            </button>

            <div class="text-center mt-3">
                <p style="color: var(--text-muted); font-size: 0.9rem;">
                    Don't have an account?
                    <a href="/bloodbank/register" class="text-link">Register here</a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
