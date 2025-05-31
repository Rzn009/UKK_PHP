-- Buat database
CREATE DATABASE IF NOT EXISTS db_kantin;
USE db_kantin;

-- Buat tabel kantin
CREATE TABLE IF NOT EXISTS kantin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_kantin VARCHAR(100) NOT NULL,
    foto_kantin VARCHAR(255),
    deskripsi TEXT
);

-- Buat tabel menu
CREATE TABLE IF NOT EXISTS menu (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kantin_id INT,
    nama_menu VARCHAR(100) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    foto_menu VARCHAR(255),
    FOREIGN KEY (kantin_id) REFERENCES kantin(id) ON DELETE CASCADE
);

-- Buat tabel pesanan
CREATE TABLE IF NOT EXISTS pesanan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_pelanggan VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    total_harga DECIMAL(10,2) NOT NULL,
    tanggal_pesanan DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Buat tabel detail_pesanan
CREATE TABLE IF NOT EXISTS detail_pesanan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pesanan_id INT,
    menu_id INT,
    quantity INT NOT NULL,
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_id) REFERENCES menu(id) ON DELETE CASCADE
);

-- Insert data dummy untuk kantin
INSERT INTO kantin (nama_kantin, foto_kantin, deskripsi) VALUES
('Kantin Ibu Rika', 'kantin1.jpg', 'Kantin dengan berbagai menu makanan dan minuman yang lezat'),
('Kantin Batagor Mas Riki', 'kantin2.jpg', 'Kantin spesialis batagor dan makanan ringan'),
('Kantin Masakan Rumah Bu Eka', 'kantin3.jpg', 'Kantin dengan masakan rumahan yang enak');

-- Insert data dummy untuk menu
INSERT INTO menu (kantin_id, nama_menu, harga, stok, foto_menu) VALUES
-- Menu Kantin Ibu Rika
(1, 'Nasi Goreng', 15000, 20, 'nasgor.jpg'),
(1, 'Mie Goreng', 14000, 15, 'miegoreng.jpg'),
(1, 'Es Teh', 3000, 30, 'esteh.jpg'),
(1, 'Es Jeruk', 4000, 25, 'esjeruk.jpg'),

-- Menu Kantin Batagor Mas Riki
(2, 'Batagor', 10000, 25, 'batagor.jpg'),
(2, 'Siomay', 12000, 20, 'siomay.jpg'),
(2, 'Es Campur', 5000, 15, 'escampur.jpg'),
(2, 'Es Cendol', 4000, 20, 'escendol.jpg'),

-- Menu Kantin Masakan Rumah Bu Eka
(3, 'Nasi Uduk', 12000, 15, 'nasiuduk.jpg'),
(3, 'Nasi Kuning', 13000, 15, 'nasikuning.jpg'),
(3, 'Es Teh Tarik', 4000, 20, 'estarik.jpg'),
(3, 'Es Milo', 5000, 15, 'esmilo.jpg'); 