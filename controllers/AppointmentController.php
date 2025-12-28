<?php
require_once __DIR__ . '/../config/database.php';

class AppointmentController {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* =========================
       INDEX – DONOR / ADMIN
    ========================= */
    public function index() {

        if (!isset($_SESSION['user_id'])) {
            header('Location: /bloodbank/login');
            exit;
        }

        // -------------------------
        // DONOR VIEW
        // -------------------------
        if ($_SESSION['user_role'] === 'donor') {
            require __DIR__ . '/../views/appointments/create.php';
            return;
        }

        // -------------------------
        // ADMIN / STAFF VIEW
        // -------------------------
        $stmt = $this->pdo->query("
            SELECT 
                a.id,
                a.appointment_date,
                a.status,
                u.fullname,
                u.email
            FROM appointments a
            JOIN users u ON u.id = a.user_id
            ORDER BY a.appointment_date DESC
        ");

        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/appointments/index.php';
    }

    /* =========================
       STORE APPOINTMENT (DONOR)
    ========================= */
    public function store() {

        if (!isset($_SESSION['user_id'])) {
            header('Location: /bloodbank/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /bloodbank/dashboard');
            exit;
        }

        try {
            $date = $_POST['appointment_date'] ?? '';

            if (!$date || strtotime($date) <= time()) {
                throw new Exception('Please select a valid future date');
            }

            // ❗ Prevent duplicate active appointment
            $check = $this->pdo->prepare("
                SELECT id FROM appointments
                WHERE user_id = ? AND status = 'scheduled'
            ");
            $check->execute([$_SESSION['user_id']]);

            if ($check->fetch()) {
                throw new Exception('You already have a scheduled appointment');
            }

            $stmt = $this->pdo->prepare(
                "INSERT INTO appointments (user_id, appointment_date)
                 VALUES (?, ?)"
            );

            $stmt->execute([
                $_SESSION['user_id'],
                $date
            ]);

            $_SESSION['success'] = 'Appointment scheduled successfully';

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /bloodbank/dashboard');
        exit;
    }

    /* =========================
       ADMIN / STAFF UPDATE STATUS
    ========================= */
    public function updateStatus() {

        if (
            !isset($_SESSION['user_id']) ||
            !in_array($_SESSION['user_role'], ['admin','staff'])
        ) {
            header('Location: /bloodbank/login');
            exit;
        }

        $id     = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if (!$id || !in_array($status, ['scheduled','completed','cancelled'])) {
            $_SESSION['error'] = 'Invalid action';
            header('Location: /bloodbank/appointments');
            exit;
        }

        $stmt = $this->pdo->prepare(
            "UPDATE appointments SET status = ? WHERE id = ?"
        );
        $stmt->execute([$status, $id]);

        $_SESSION['success'] = 'Appointment updated successfully';
        header('Location: /bloodbank/appointments');
        exit;
    }
}
