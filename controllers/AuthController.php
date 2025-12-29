<?php

// Database connection is passed via constructor

class AuthController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;

        // ✅ Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

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
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $_SESSION['error'] = 'Email already registered';
                header('Location: /bloodbank/register');
                exit;
            }

            $allowed_roles = ['donor', 'staff'];
            if (!in_array($role, $allowed_roles)) {
                $role = 'donor';
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $this->conn->prepare(
                "INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("ssss", $fullname, $email, $hashed_password, $role);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Registration successful! Please login.';
                header('Location: /bloodbank/login');
                exit;
            } else {
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
