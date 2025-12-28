<?php

require_once __DIR__ . '/../config/database.php';

class AuthController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;

        // ✅ Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {

                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['fullname'];
                $_SESSION['user_role'] = $user['role'];

                header('Location: /bloodbank/dashboard');
                exit;
            } else {
                $_SESSION['error'] = 'Invalid email or password';
                header('Location: /bloodbank/login');
                exit;
            }
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $fullname = trim($_POST['fullname'] ?? '');
            $email    = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $role     = trim($_POST['role'] ?? 'donor');

            // Check if email exists
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'Email already registered';
                header('Location: /bloodbank/register');
                exit;
            }

            $allowed_roles = ['donor', 'staff'];
            if (!in_array($role, $allowed_roles)) {
                $role = 'donor';
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try {
                $stmt = $this->pdo->prepare(
                    "INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)"
                );
                $stmt->execute([$fullname, $email, $hashed_password, $role]);

                $_SESSION['success'] = 'Registration successful! Please login.';
                header('Location: /bloodbank/login');
                exit;
            } catch (PDOException $e) {
                $_SESSION['error'] = 'Registration failed.';
                header('Location: /bloodbank/register');
                exit;
            }
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /bloodbank/login');
        exit;
    }
}
