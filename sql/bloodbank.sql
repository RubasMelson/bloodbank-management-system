-- =========================================
-- DATABASE: bloodbank
-- =========================================
CREATE DATABASE IF NOT EXISTS bloodbank;
USE bloodbank;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- =========================================
-- USERS TABLE
-- =========================================
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','staff','donor') DEFAULT 'donor',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default Admin
-- Email: admin@bloodbank.com
-- Password: password123
INSERT INTO users (fullname, email, password, role) VALUES (
  'Super Admin',
  'admin@bloodbank.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'admin'
);

-- =========================================
-- DONORS TABLE (VERY IMPORTANT – FIXED)
-- =========================================
CREATE TABLE donors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  fullname VARCHAR(100) NOT NULL,
  email VARCHAR(100),
  phone VARCHAR(20) NOT NULL,
  blood_group ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  city VARCHAR(50) NOT NULL,
  last_donation DATE DEFAULT NULL,
  status ENUM('pending','approved','rejected') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_donor_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================
-- BLOOD INVENTORY
-- =========================================
CREATE TABLE blood_inventory (
  id INT AUTO_INCREMENT PRIMARY KEY,
  blood_group ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') UNIQUE NOT NULL,
  units INT DEFAULT 0,
  last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO blood_inventory (blood_group, units) VALUES
('A+',0),('A-',0),('B+',0),('B-',0),
('AB+',0),('AB-',0),('O+',0),('O-',0);

-- =========================================
-- BLOOD REQUESTS
-- =========================================
CREATE TABLE requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_name VARCHAR(100) NOT NULL,
  hospital_name VARCHAR(100) NOT NULL,
  blood_group ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  units INT NOT NULL,
  contact_phone VARCHAR(20) NOT NULL,
  status ENUM('pending','approved','rejected','completed') DEFAULT 'pending',
  request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================
-- APPOINTMENTS (DONATION SCHEDULING)
-- =========================================
CREATE TABLE appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  appointment_date DATETIME NOT NULL,
  status ENUM('scheduled','completed','cancelled') DEFAULT 'scheduled',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_appointment_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
