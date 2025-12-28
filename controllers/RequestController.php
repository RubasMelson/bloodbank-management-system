<?php

class RequestController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        // Ensure session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* ===========================
       LIST REQUESTS (ADMIN/STAFF)
    ============================ */
    public function index()
    {
        // Access control
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') === 'donor') {
            header('Location: /bloodbank/dashboard');
            exit;
        }

        try {
            // All requests
            $stmt = $this->pdo->query(
                "SELECT * FROM requests ORDER BY request_date DESC"
            );
            $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // ✅ Pending count (FOR SIDEBAR BADGE)
            $stmt2 = $this->pdo->query(
                "SELECT COUNT(*) FROM requests WHERE status = 'pending'"
            );
            $pendingCount = (int)$stmt2->fetchColumn();

        } catch (PDOException $e) {
            $requests = [];
            $pendingCount = 0;
            $_SESSION['error'] = 'Failed to load requests';
        }

        require __DIR__ . '/../views/requests/index.php';
    }

    /* ===========================
       CREATE REQUEST FORM
    ============================ */
    public function create()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /bloodbank/login');
            exit;
        }

        require __DIR__ . '/../views/requests/create.php';
    }

    /* ===========================
       STORE REQUEST
    ============================ */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /bloodbank/dashboard');
            exit;
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /bloodbank/login');
            exit;
        }

        try {
            $patient_name  = trim($_POST['patient_name'] ?? '');
            $hospital_name = trim($_POST['hospital_name'] ?? '');
            $blood_group   = trim($_POST['blood_group'] ?? '');
            $units         = (int)($_POST['units'] ?? 0);
            $contact_phone = trim($_POST['contact_phone'] ?? '');

            if ($patient_name === '' || $hospital_name === '' || $blood_group === '' || $units <= 0) {
                throw new Exception('All fields are required');
            }

            $stmt = $this->pdo->prepare(
                "INSERT INTO requests
                 (patient_name, hospital_name, blood_group, units, contact_phone, status)
                 VALUES (?, ?, ?, ?, ?, 'pending')"
            );

            $stmt->execute([
                $patient_name,
                $hospital_name,
                $blood_group,
                $units,
                $contact_phone
            ]);

            $_SESSION['success'] = 'Blood request submitted successfully!';
            header('Location: /bloodbank/dashboard');
            exit;

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /bloodbank/request-blood');
            exit;
        }
    }

    /* ===========================
       UPDATE STATUS + INVENTORY
    ============================ */
    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /bloodbank/requests');
            exit;
        }

        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') === 'donor') {
            header('Location: /bloodbank/login');
            exit;
        }

        $id     = (int)($_POST['id'] ?? 0);
        $status = trim($_POST['status'] ?? '');

        if ($id <= 0 || $status === '') {
            $_SESSION['error'] = 'Invalid request';
            header('Location: /bloodbank/requests');
            exit;
        }

        try {
            $this->pdo->beginTransaction();

            // Fetch request
            $stmt = $this->pdo->prepare(
                "SELECT blood_group, units FROM requests WHERE id = ?"
            );
            $stmt->execute([$id]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$request) {
                throw new Exception('Request not found');
            }

            // Deduct inventory ONLY when COMPLETED
            if ($status === 'completed') {

                $stmt = $this->pdo->prepare(
                    "SELECT units FROM blood_inventory WHERE blood_group = ?"
                );
                $stmt->execute([$request['blood_group']]);
                $available = (int)$stmt->fetchColumn();

                if ($available < (int)$request['units']) {
                    throw new Exception('Not enough blood stock available');
                }

                $stmt = $this->pdo->prepare(
                    "UPDATE blood_inventory
                     SET units = units - ?
                     WHERE blood_group = ?"
                );
                $stmt->execute([
                    $request['units'],
                    $request['blood_group']
                ]);
            }

            // Update request status
            $stmt = $this->pdo->prepare(
                "UPDATE requests SET status = ? WHERE id = ?"
            );
            $stmt->execute([$status, $id]);

            $this->pdo->commit();

            $_SESSION['success'] = 'Request marked as ' . ucfirst($status);

        } catch (Exception $e) {
            $this->pdo->rollBack();
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /bloodbank/requests');
        exit;
    }
}
