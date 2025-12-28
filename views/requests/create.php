<?php
$pageTitle = 'Request Blood - Blood Bank';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="dashboard-container">

    <!-- Sidebar -->
    <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">

        <h2 class="text-gradient mb-4">Request Blood</h2>

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

        <div class="glass-panel" style="max-width:600px;padding:2rem;">

            <!-- Request Form -->
            <form action="/bloodbank/requests/store" method="POST">

                <div class="form-group">
                    <label class="form-label">Patient Name</label>
                    <input
                        type="text"
                        name="patient_name"
                        class="form-control"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">Hospital Name</label>
                    <input
                        type="text"
                        name="hospital_name"
                        class="form-control"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">Blood Group Required</label>
                    <select
                        name="blood_group"
                        class="form-control"
                        required
                    >
                        <option value="">Select Group</option>
                        <option>A+</option><option>A-</option>
                        <option>B+</option><option>B-</option>
                        <option>AB+</option><option>AB-</option>
                        <option>O+</option><option>O-</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Units Required</label>
                    <input
                        type="number"
                        name="units"
                        class="form-control"
                        min="1"
                        value="1"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">Contact Phone</label>
                    <input
                        type="text"
                        name="contact_phone"
                        class="form-control"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    Submit Request
                </button>

            </form>
        </div>
    </div>
</div>

<!-- JS -->
<script src="/bloodbank/assets/js/script.js"></script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
