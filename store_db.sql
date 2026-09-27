CREATE DATABASE IF NOT EXISTS store_db;
USE store_db;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    category VARCHAR(50) NOT NULL DEFAULT 'Umum',
    price DECIMAL(12,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Data Awal Toko Barang Antik
INSERT INTO products (name, category, price, stock) VALUES
('Gramofon Pemutar Piringan Hitam 1940', 'Musik & Audio', 3500000.00, 1),
('Jam Dinding Kayu Jati Bandul', 'Dekorasi', 1250000.00, 2),
('Kamera Analog Rangefinder 1970', 'Fotografi', 850000.00, 3),
('Mesin Ketik Manual Vintage', 'Alat Kantor', 650000.00, 4);