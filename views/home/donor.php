<?php
$pageTitle = 'Become a Donor';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="auth-wrapper">
    <div class="glass-panel auth-card" style="max-width:500px;">
        <h2 class="text-gradient mb-4 text-center">Become a Donor</h2>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="text-center py-5">
                <div class="mb-4" style="font-size:3rem;">🔒</div>
                <h3>Login Required</h3>
                <p class="text-muted mb-4">You need to login to register as a donor.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="/bloodbank/login" class="btn btn-primary">Login</a>
                    <a href="/bloodbank/register" class="btn btn-outline-primary">Register</a>
                </div>
            </div>
        <?php else: ?>
            
            <!-- Error Message -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger mb-4">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Success Message -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success mb-4">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <form action="/bloodbank/become-donor/store" method="POST" id="donorForm">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Optional">
                </div>

                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Blood Group</label>
                    <select name="blood_group" class="form-control" required>
                        <option value="">Select</option>
                        <option>A+</option><option>A-</option>
                        <option>B+</option><option>B-</option>
                        <option>AB+</option><option>AB-</option>
                        <option>O+</option><option>O-</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" required>
                </div>

                <!-- Donation History Section -->
                <div class="form-group mt-4">
                    <label class="form-label d-block">Have you donated blood before?</label>
                    <div class="d-flex gap-4 mt-2">
                        <label class="d-flex align-items-middle gap-2" style="cursor:pointer;">
                            <input type="radio" name="is_first_time" value="yes" checked onclick="toggleDateInput(true)">
                            <span>No, this is my first time</span>
                        </label>
                        <label class="d-flex align-items-middle gap-2" style="cursor:pointer;">
                            <input type="radio" name="is_first_time" value="no" onclick="toggleDateInput(false)">
                            <span>Yes, I have donated before</span>
                        </label>
                    </div>
                </div>

                <div class="form-group" id="lastDonationGroup" style="display:none;">
                    <label class="form-label">Last Blood Donation Date <span class="text-danger">*</span></label>
                    <input 
                        type="date" 
                        name="last_donation_date" 
                        id="lastDonationDate" 
                        class="form-control"
                        onchange="validateEligibility()"
                    >
                    <div id="eligibilityError" class="alert alert-danger mt-2" style="display:none;">
                        ❌ You are not eligible to donate blood. A minimum gap of 3 months is required between donations.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-4" id="submitBtn">
                    Register as Donor
                </button>
            </form>

            <script>
            function toggleDateInput(isFirstTime) {
                const group = document.getElementById('lastDonationGroup');
                const input = document.getElementById('lastDonationDate');
                const error = document.getElementById('eligibilityError');
                const btn = document.getElementById('submitBtn');

                if (isFirstTime) {
                    group.style.display = 'none';
                    input.required = false;
                    input.value = '';
                    error.style.display = 'none';
                    btn.disabled = false;
                } else {
                    group.style.display = 'block';
                    input.required = true;
                }
            }

            function validateEligibility() {
                const input = document.getElementById('lastDonationDate');
                const error = document.getElementById('eligibilityError');
                const btn = document.getElementById('submitBtn');
                
                if (!input.value) return;

                const lastDate = new Date(input.value);
                const today = new Date();
                
                // Calculate difference in milliseconds
                const diffTime = Math.abs(today - lastDate);
                // Convert to days
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 

                // Check if date is in future
                if (lastDate > today) {
                    alert("Date cannot be in the future");
                    input.value = '';
                    return;
                }

                if (diffDays < 90) {
                    error.style.display = 'block';
                    btn.disabled = true;
                } else {
                    error.style.display = 'none';
                    btn.disabled = false;
                }
            }
            </script>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
