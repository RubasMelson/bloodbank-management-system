<?php
// Database connection passed locally

class DonorController {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* =========================
       ADMIN / STAFF – VIEW DONORS
    ========================= */
    public function index() {

        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') === 'donor') {
            header('Location: /bloodbank/dashboard');
            exit;
        }

        try {
            $result = $this->conn->query("
                SELECT 
                    d.id,
                    d.fullname,
                    d.email,
                    d.phone,
                    d.blood_group,
                    d.city,
                    d.last_donation,
                    d.status,
                    d.created_at,
                    u.fullname AS user_name
                FROM donors d
                JOIN users u ON u.id = d.user_id
                ORDER BY d.created_at DESC
            ");

            $donors = $result->fetch_all(MYSQLI_ASSOC);

            // Pending count (FOR SIDEBAR BADGE)
            $result2 = $this->conn->query(
                "SELECT COUNT(*) FROM donors WHERE status = 'pending'"
            );
            $row = $result2->fetch_row();
            $pendingDonorsCount = (int)$row[0];

        } catch (Exception $e) {
            $donors = [];
            $pendingDonorsCount = 0;
            $_SESSION['error'] = 'Failed to load donors';
        }

        require __DIR__ . '/../views/donors/index.php';
    }

    /* =========================
       DONOR APPLY TO DONATE
    ========================= */
    public function store() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /bloodbank/dashboard');
            exit;
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /bloodbank/login');
            exit;
        }

        try {
            $user_id       = $_SESSION['user_id'];
            $fullname      = trim($_POST['fullname'] ?? '');
            $email         = $_POST['email'] ?? null;
            $phone         = trim($_POST['phone'] ?? '');
            $blood_group   = trim($_POST['blood_group'] ?? '');
            $city          = trim($_POST['city'] ?? '');
            
            // New logic for Last Donation Date
            $is_first_time = $_POST['is_first_time'] ?? 'yes';
            $last_donation = null;

            if ($is_first_time === 'no') {
                $dateInput = $_POST['last_donation_date'] ?? '';
                if (empty($dateInput)) {
                    throw new Exception("Last Blood Donation Date is required.");
                }
                $last_donation = $dateInput;
            }

            // Validate Eligibility
            $this->validateEligibility($last_donation);

            if (!$fullname || !$phone || !$blood_group || !$city) {
                throw new Exception('All required fields must be filled');
            }

            // Prevent duplicate donor
            $check = $this->conn->prepare(
                "SELECT id FROM donors WHERE user_id = ?"
            );
            $check->bind_param("i", $user_id);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                throw new Exception('You already applied as a donor');
            }

            $stmt = $this->conn->prepare("
                INSERT INTO donors
                (user_id, fullname, email, phone, blood_group, city, last_donation, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
            ");

            $stmt->bind_param("issssss", 
                $user_id,
                $fullname,
                $email,
                $phone,
                $blood_group,
                $city,
                $last_donation
            );

            $stmt->execute();

            $_SESSION['success'] =
                'Donor application submitted. Waiting for admin approval.';
            header('Location: /bloodbank/dashboard');
            exit;

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /bloodbank/become-donor');
            exit;
        }
    }

    /* =========================
       ADMIN APPROVE / REJECT
    ========================= */
    public function update() {

        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') === 'donor') {
            header('Location: /bloodbank/login');
            exit;
        }

        $id     = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if (!$id || !in_array($status, ['approved','rejected'])) {
            $_SESSION['error'] = 'Invalid action';
            header('Location: /bloodbank/donors');
            exit;
        }

        try {
            $stmt = $this->conn->prepare(
                "UPDATE donors SET status = ? WHERE id = ?"
            );
            $stmt->bind_param("si", $status, $id);
            $stmt->execute();

            $_SESSION['success'] = 'Donor status updated successfully';

        } catch (Exception $e) {
            $_SESSION['error'] = 'Failed to update donor';
        }

        header('Location: /bloodbank/donors');
        exit;
    }

    /* =========================
       DELETE DONOR (ADMIN ONLY)
    ========================= */
    public function delete($id) {

        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /bloodbank/login');
            exit;
        }

        try {
            $stmt = $this->conn->prepare(
                "DELETE FROM donors WHERE id = ?"
            );
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $_SESSION['success'] = 'Donor deleted successfully';

        } catch (Exception $e) {
            $_SESSION['error'] = 'Failed to delete donor';
        }

        header('Location: /bloodbank/donors');
        exit;
    }

    private function validateEligibility($last_donation) {
        if (!$last_donation) return true;

        $lastDate = new DateTime($last_donation);
        $today    = new DateTime();
        $diff     = $today->diff($lastDate);

        if ($lastDate > $today) {
            throw new Exception('Last donation date cannot be in the future.');
        }

        if ($diff->days < 90) {
            throw new Exception('❌ You are not eligible to donate blood. A minimum gap of 3 months is required between donations.');
        }
        
        return true;
    }
}
