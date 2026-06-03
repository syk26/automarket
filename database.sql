CREATE DATABASE IF NOT EXISTS automarket;
USE automarket;

CREATE TABLE IF NOT EXISTS sellers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(255),
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    model VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    colour VARCHAR(50),
    location VARCHAR(100),
    price DECIMAL(10,2) NOT NULL,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES sellers(id) ON DELETE CASCADE
);

INSERT INTO sellers (name, address, phone, email, username, password) VALUES 
('Zhang Wei', 'Beijing Chaoyang District', '13812345678', 'zhangwei@example.com', 'zhangwei', '$2y$10$examplehash'),
('Li Ming', 'Shanghai Pudong', '13987654321', 'liming@example.com', 'liming', '$2y$10$examplehash');

INSERT INTO cars (seller_id, model, year, colour, location, price, image_path) VALUES 
(1, 'Toyota Camry', 2020, 'Silver', 'Beijing', 180000.00, 'images/camry1.jpg'),
(1, 'Honda Accord', 2021, 'Black', 'Shanghai', 200000.00, 'images/accord1.jpg'),
(2, 'BMW 3 Series', 2019, 'White', 'Guangzhou', 250000.00, 'images/bmw1.jpg');