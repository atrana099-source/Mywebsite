CREATE DATABASE IF NOT EXISTS foodapp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE foodapp;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','customer') DEFAULT 'customer'
);

INSERT INTO users (username, password_hash, role)
VALUES ('admin', '$2y$10$mSxgk9oI8aJ4x1vQpKfMIO3M0G9Kqz1q8bQ3l1aJ4x1vQpKfMIO3', 'admin');
-- password: admin123  (bcrypt hash produced with password_hash('admin123', PASSWORD_DEFAULT))

CREATE TABLE items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  available TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(200),
  phone VARCHAR(50),
  address TEXT,
  total DECIMAL(10,2),
  status ENUM('received','preparing','out_for_delivery','delivered') DEFAULT 'received',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT,
  item_id INT,
  qty INT,
  price DECIMAL(10,2),
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE SET NULL
);
