<?php

class ProfileController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function update() {

        if (!isset($_SESSION['user_id'])) {
            header('Location: /bloodbank/login');
            exit;
        }

        $userId   = $_SESSION['user_id'];
        $fullname = trim($_POST['fullname'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($fullname === '') {
            $_SESSION['error'] = 'Name is required';
            header('Location: /bloodbank/profile');
            exit;
        }

        try {

            if ($password !== '') {
                $hashed = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $this->conn->prepare(
                    "UPDATE users SET fullname = ?, password = ? WHERE id = ?"
                );
                $stmt->bind_param("ssi", $fullname, $hashed, $userId);
                $stmt->execute();

            } else {
                $stmt = $this->conn->prepare(
                    "UPDATE users SET fullname = ? WHERE id = ?"
                );
                $stmt->bind_param("si", $fullname, $userId);
                $stmt->execute();
            }

            $_SESSION['user_name'] = $fullname;
            $_SESSION['success'] = 'Profile updated successfully';

        } catch (Exception $e) {
            $_SESSION['error'] = 'Update failed: ' . $e->getMessage();
        }

        header('Location: /bloodbank/profile');
        exit;
    }
}
