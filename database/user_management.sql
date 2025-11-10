-- --------------------------------------------------------
-- Database: user_management
-- --------------------------------------------------------
CREATE DATABASE IF NOT EXISTS user_management;
USE user_management;

-- --------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_active TINYINT(1) DEFAULT 0,
    activation_token VARCHAR(255) DEFAULT NULL,
    reset_token VARCHAR(255) DEFAULT NULL,
    reset_expires DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `products`
-- --------------------------------------------------------
CREATE TABLE products (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT(11) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Sample data (optional)
-- --------------------------------------------------------
INSERT INTO users (name, email, password, is_active)
VALUES
('Admin Contoh', 'admin@example.com', '$2y$10$1hkjjZQ99h6a1uQh1GxXeOc1oZkik4cGB0PExqWhRzqULYJ7FtY0e', 1);

INSERT INTO products (name, description, price, stock)
VALUES
('Produk A', 'Deskripsi produk A', 12000.00, 15),
('Produk B', 'Deskripsi produk B', 8500.00, 30),
('Produk C', 'Deskripsi produk C', 20000.00, 10);
