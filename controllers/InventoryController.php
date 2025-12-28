<?php
require_once __DIR__ . '/../config/database.php';

class InventoryController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;

        // ✅ Ensure session exists
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {

        // ✅ Login + role check (staff/admin only)
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') === 'donor') {
            header('Location: /bloodbank/dashboard');
            exit;
        }

        try {
            $stmt = $this->pdo->query("SELECT * FROM blood_inventory ORDER BY blood_group");
            $inventory = $stmt->fetchAll();
        } catch (PDOException $e) {
            // Table might not exist, try to create it
            $this->initInventoryTable();
            $inventory = [];
            $_SESSION['error'] = "Initialized inventory system. Please refresh.";
        }

        // Calculate total units
        $totalUnits = 0;
        foreach ($inventory as $item) {
            $totalUnits += (int)$item['units'];
        }

        require __DIR__ . '/../views/inventory/index.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // ✅ Login + role check
            if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') === 'donor') {
                header('Location: /bloodbank/login');
                exit;
            }

            $blood_group = $_POST['blood_group'] ?? '';
            $operation   = $_POST['operation'] ?? '';
            $units       = (int)($_POST['units'] ?? 0);

            if ($units <= 0) {
                $_SESSION['error'] = 'Units must be greater than zero';
                header('Location: /bloodbank/inventory');
                exit;
            }

            try {
                $this->pdo->beginTransaction();

                // Ensure row exists
                $check = $this->pdo->prepare(
                    "SELECT units FROM blood_inventory WHERE blood_group = ?"
                );
                $check->execute([$blood_group]);
                $current = $check->fetchColumn();

                if ($current === false) {
                    $this->pdo->prepare(
                        "INSERT INTO blood_inventory (blood_group, units) VALUES (?, 0)"
                    )->execute([$blood_group]);
                    $current = 0;
                }

                if ($operation === 'add') {
                    $stmt = $this->pdo->prepare(
                        "UPDATE blood_inventory SET units = units + ? WHERE blood_group = ?"
                    );
                    $stmt->execute([$units, $blood_group]);
                    $_SESSION['success'] = "$units units added to $blood_group";

                } elseif ($operation === 'remove') {

                    if ($current >= $units) {
                        $stmt = $this->pdo->prepare(
                            "UPDATE blood_inventory SET units = units - ? WHERE blood_group = ?"
                        );
                        $stmt->execute([$units, $blood_group]);
                        $_SESSION['success'] = "$units units removed from $blood_group";
                    } else {
                        $_SESSION['error'] = "Insufficient stock for $blood_group";
                    }

                } else {
                    $_SESSION['error'] = 'Invalid operation';
                }

                $this->pdo->commit();

            } catch (Exception $e) {
                $this->pdo->rollBack();
                $_SESSION['error'] = 'Error updating inventory';
            }

            header('Location: /bloodbank/inventory');
            exit;
        }
    }
    private function initInventoryTable() {
        try {
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS blood_inventory (
                  id INT AUTO_INCREMENT PRIMARY KEY,
                  blood_group ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') UNIQUE NOT NULL,
                  units INT DEFAULT 0,
                  last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
            
            $groups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
            $stmt = $this->pdo->prepare("INSERT IGNORE INTO blood_inventory (blood_group, units) VALUES (?, 0)");
            foreach ($groups as $g) {
                $stmt->execute([$g]);
            }
        } catch (Exception $e) {
            // Siltent fail or log
        }
    }
}
