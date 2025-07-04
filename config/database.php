<?php
// Variabel untuk konfigurasi koneksi database
$host = 'localhost'; // Host database, biasanya 'localhost' untuk server lokal
$dbname = 'db_sneakeran'; // Nama database yang sudah dibuat sebelumnya (sesuai db_sneakeran.sql)
$username = 'root'; // Username database Anda (default XAMPP/WAMP: 'root')
$password = ''; // Password database Anda (default XAMPP/WAMP: kosong)

// Membuat koneksi ke database menggunakan mysqli_connect
$conn = mysqli_connect($host, $username, $password, $dbname);

// Memeriksa apakah koneksi berhasil atau gagal
if (!$conn) {
    // Jika koneksi gagal, hentikan eksekusi script dan tampilkan pesan error
    die("Koneksi gagal: " . mysqli_connect_error());
}
