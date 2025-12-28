<?php

class ProfileController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;

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

                $stmt = $this->pdo->prepare(
                    "UPDATE users SET fullname = ?, password = ? WHERE id = ?"
                );
                $stmt->execute([$fullname, $hashed, $userId]);

            } else {
                $stmt = $this->pdo->prepare(
                    "UPDATE users SET fullname = ? WHERE id = ?"
                );
                $stmt->execute([$fullname, $userId]);
            }

            $_SESSION['user_name'] = $fullname;
            $_SESSION['success'] = 'Profile updated successfully';

        } catch (PDOException $e) {
            $_SESSION['error'] = 'Update failed: ' . $e->getMessage();
        }

        header('Location: /bloodbank/profile');
        exit;
    }
}
