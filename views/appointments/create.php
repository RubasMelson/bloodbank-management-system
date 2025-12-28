<?php
// Page title
$pageTitle = 'Schedule Donation - Blood Bank';

// Header (session + CSS loaded here)
require_once __DIR__ . '/../layout/header.php';
?>

<div class="dashboard-container">

    <!-- Sidebar -->
    <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">

        <h2 class="text-gradient mb-4">Schedule a Donation</h2>

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

        <!-- Card -->
        <div class="glass-panel" style="max-width: 500px; padding: 2rem;">

            <p class="mb-4">
                Thank you for checking eligibility. Please choose a suitable date and time for donation.
            </p>

            <!-- FORM -->
            <form action="/bloodbank/appointments/store" method="POST">

                <div class="form-group">
                    <label class="form-label">Preferred Date & Time</label>
                    <input
                        type="datetime-local"
                        name="appointment_date"
                        class="form-control"
                        required
                    >
                </div>

                <div class="alert"
                     style="background: rgba(56,189,248,0.1);
                            border: 1px solid rgba(56,189,248,0.2);
                            color: #7dd3fc;
                            font-size: 0.9rem;">
                    ℹ️ Note: Please ensure you have not donated blood in the last 3 months.
                </div>

                <button type="submit" class="btn btn-primary">
                    Confirm Appointment
                </button>

            </form>
        </div>

    </div>
</div>

<!-- JS -->
<script src="/bloodbank/assets/js/script.js"></script>

<?php
// Footer
require_once __DIR__ . '/../layout/footer.php';
?>
