-- SQL Script to create Database and Tables for Sumitra Shoes Centre
CREATE DATABASE IF NOT EXISTS shoes_store;
USE shoes_store;

-- 1. Users Table (Customer accounts)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- 2. Admins Table (Administrative accounts)
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- 3. Products Table (Shoes list)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL
);

-- 4. Orders Table (Recorded transactions)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    items TEXT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    transaction_id VARCHAR(255) NOT NULL
);

-- Insert a default Administrator (Password is "admin123")
-- Hash generated via password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO admins (username, password) VALUES 
('admin', '$2y$10$w8.3f619g7.a.v/A17pGpe0G4BfFqG9Q.yY2A7D8u1g3u0y.S63d6')
ON DUPLICATE KEY UPDATE username=username;

-- Insert premium shoe listings for immediate testing in XAMPP htdocs
INSERT INTO products (name, price, image, category) VALUES
('Sumitra Air Speed Runner', 4500.00, 'runner.jpg', 'Sports'),
('Classic Casual White Sneaker', 3200.00, 'sneaker.jpg', 'Casual'),
('Premium Oxford Formal Leather', 5800.00, 'oxford.jpg', 'Formal'),
('Retro Golden Trainer Sporty', 4900.00, 'trainer.jpg', 'Sports'),
('All-Weather Hiking Boots Active', 7500.00, 'boots.jpg', 'Boots'),
('Standard School Uniform Shoe', 1800.00, 'school.jpg', 'School');
