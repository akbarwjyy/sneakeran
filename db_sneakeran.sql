-- Membuat database dengan nama db_sneakeran
CREATE DATABASE db_sneakeran;

-- Menggunakan database db_sneakeran sebagai database aktif
USE db_sneakeran;

-- Membuat tabel 'admin' untuk menyimpan data administrator
CREATE TABLE admin (
    id_admin INT PRIMARY KEY AUTO_INCREMENT, -- Kolom ID admin, kunci utama, auto-increment
    username VARCHAR(50) NOT NULL,            -- Username admin, tidak boleh kosong
    password VARCHAR(255) NOT NULL           -- Password admin (disarankan di-hash), tidak boleh kosong
);

-- Membuat tabel 'users' untuk menyimpan data pengguna/pembeli
CREATE TABLE users (
    id_user INT PRIMARY KEY AUTO_INCREMENT, -- Kolom ID pengguna, kunci utama, auto-increment
    nama VARCHAR(100) NOT NULL,             -- Nama lengkap pengguna, tidak boleh kosong
    email VARCHAR(100) NOT NULL UNIQUE,     -- Email pengguna, tidak boleh kosong dan harus unik
    password VARCHAR(255) NOT NULL          -- Password pengguna (disarankan di-hash), tidak boleh kosong
);

-- Membuat tabel 'barang' untuk menyimpan data produk sepatu
CREATE TABLE barang (
    id_barang INT PRIMARY KEY AUTO_INCREMENT, -- Kolom ID barang, kunci utama, auto-increment
    nama_barang VARCHAR(100) NOT NULL,        -- Nama sepatu, tidak boleh kosong
    deskripsi TEXT,                           -- Deskripsi detail sepatu
    harga DECIMAL(10,2) NOT NULL,             -- Harga sepatu, tidak boleh kosong (format 10 digit total, 2 di belakang koma)
    stok INT NOT NULL,                        -- Jumlah stok sepatu, tidak boleh kosong
    gambar VARCHAR(255)                       -- Nama file gambar sepatu (path relatif), bisa kosong
);

-- Membuat tabel 'transaksi' untuk mencatat setiap pembelian
CREATE TABLE transaksi (
    id_transaksi INT PRIMARY KEY AUTO_INCREMENT, -- Kolom ID transaksi, kunci utama, auto-increment
    id_user INT,                                 -- ID pengguna yang melakukan transaksi (Foreign Key ke tabel 'users')
    id_barang INT,                               -- ID barang yang dibeli (Foreign Key ke tabel 'barang')
    jumlah INT NOT NULL,                         -- Jumlah barang yang dibeli dalam transaksi ini, tidak boleh kosong
    total_harga DECIMAL(10,2) NOT NULL,          -- Total harga transaksi, tidak boleh kosong
    tanggal_transaksi DATETIME NOT NULL,         -- Tanggal dan waktu transaksi dilakukan, tidak boleh kosong
    FOREIGN KEY (id_user) REFERENCES users(id_user),   -- Mendefinisikan id_user sebagai foreign key yang merujuk ke id_user di tabel users
    FOREIGN KEY (id_barang) REFERENCES barang(id_barang) -- Mendefinisikan id_barang sebagai foreign key yang merujuk ke id_barang di tabel barang
);