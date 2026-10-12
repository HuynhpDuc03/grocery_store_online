CREATE DATABASE IF NOT EXISTS taphoa_online CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE taphoa_online;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  slug VARCHAR(120) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NOT NULL,
  name VARCHAR(180) NOT NULL,
  slug VARCHAR(200) NOT NULL UNIQUE,
  description TEXT,
  price DECIMAL(12,2) NOT NULL DEFAULT 0,
  image_url VARCHAR(500),
  stock INT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON UPDATE CASCADE
);

CREATE TABLE orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  customer_name VARCHAR(100) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  email VARCHAR(150),
  address VARCHAR(255) NOT NULL,
  note VARCHAR(500),
  total DECIMAL(12,2) NOT NULL DEFAULT 0,
  status ENUM('pending','confirmed','shipping','completed','cancelled') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE order_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NULL,
  product_name VARCHAR(180) NOT NULL,
  price DECIMAL(12,2) NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

INSERT INTO categories (name, slug) VALUES
('Rau củ quả tươi', 'rau-cu-qua-tuoi'),
('Gia vị & Dầu ăn', 'gia-vi-dau-an'),
('Đồ uống & Giải khát', 'do-uong-giai-khat');

INSERT INTO products (category_id, name, slug, description, price, image_url, stock) VALUES
((SELECT id FROM categories WHERE slug='rau-cu-qua-tuoi'), 'Cà chua sạch (500g)', 'ca-chua-sach-500g', 'Cà chua tươi ngon mỗi ngày.', 16000, 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=500&q=80', 50),
((SELECT id FROM categories WHERE slug='rau-cu-qua-tuoi'), 'Chuối già Nam Mỹ (1kg)', 'chuoi-gia-nam-my-1kg', 'Chuối chín tự nhiên, thơm ngon.', 28000, 'https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?auto=format&fit=crop&w=500&q=80', 40),
((SELECT id FROM categories WHERE slug='gia-vi-dau-an'), 'Dầu ăn thực vật (1 lít)', 'dau-an-thuc-vat-1-lit', 'Dầu ăn dùng cho gia đình.', 45000, 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?auto=format&fit=crop&w=500&q=80', 30),
((SELECT id FROM categories WHERE slug='do-uong-giai-khat'), 'Nước suối (500ml)', 'nuoc-suoi-500ml', 'Nước uống tinh khiết.', 6000, 'https://images.unsplash.com/photo-1548839140-29a749e1cf4d?auto=format&fit=crop&w=500&q=80', 100);
