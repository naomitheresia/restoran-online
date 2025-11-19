-- db_restoran.sql (restoran_fix_v2)
DROP DATABASE IF EXISTS restoran;
CREATE DATABASE restoran CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE restoran;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `role` enum('admin','kasir','pelanggan') NOT NULL DEFAULT 'pelanggan',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
);

CREATE TABLE kategori (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL
);

CREATE TABLE menu (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(30) NOT NULL UNIQUE,
  nama VARCHAR(150) NOT NULL,
  kategori_id INT,
  harga DECIMAL(12,2) NOT NULL,
  stok INT DEFAULT 0,
  deskripsi TEXT,
  gambar VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE SET NULL
);

CREATE TABLE transaksi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_code VARCHAR(40) NOT NULL UNIQUE,
  user_id INT NOT NULL,
  status ENUM('pending','proses','selesai','dibatalkan') DEFAULT 'pending',
  total DECIMAL(14,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE detail_transaksi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  transaksi_id INT NOT NULL,
  menu_id INT NOT NULL,
  harga DECIMAL(12,2) NOT NULL,
  qty INT NOT NULL,
  subtotal DECIMAL(14,2) NOT NULL,
  FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE,
  FOREIGN KEY (menu_id) REFERENCES menu(id) ON DELETE RESTRICT
);

INSERT INTO kategori (nama) VALUES ('Makanan'), ('Minuman'), ('Snack');

INSERT INTO menu (kode,nama,kategori_id,harga,stok,deskripsi) VALUES
('M001','Nasi Goreng Spesial',1,25000,20,'Nasi goreng spesial'),
('M002','Mie Ayam',1,20000,15,'Mie ayam'),
('D001','Es Teh Manis',2,8000,50,'Es teh');

-- Default users (passwords plain for easy login; registration will hash new ones)
INSERT INTO `users` (`username`, `password`, `fullname`, `email`, `phone`, `role`, `created_at`) VALUES
('admin','admin123','Admin Utama','admin@resto.com','081100000000','admin', '2025-11-13 10:03:34'),
('kasir','kasir123','Kasir Resto','kasir@resto.com','081122233344','kasir', '2025-11-13 10:05:00'),
('pelanggan','pelanggan123','Pelanggan Umum','pelanggan@resto.com','081233344455','pelanggan', '2025-11-13 10:07:42');
